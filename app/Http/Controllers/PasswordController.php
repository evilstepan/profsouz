<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        try {
            Log::info('Attempting to update password', ['user_id' => Auth::id()]);

            // Log the request data before validation
            Log::info('Password update request data (raw)', ['data' => $request->all(), 'user_id' => Auth::id()]);

            // Manually parse input for PUT requests with form data
            if ($request->isMethod('PUT') && $request->header('Content-Type') && str_contains($request->header('Content-Type'), 'multipart/form-data')) {
                 // This is a common issue with PUT and multipart/form-data
                 // PHP's built-in input parsing doesn't populate $_POST for PUT
                 // We can access raw body, but parsing multipart is complex.
                 // A simpler approach for common cases is to read the raw input
                 // and potentially use parse_str if application/x-www-form-urlencoded
                 // is used, but FormData sends multipart.
                 // Given the logs show empty data, let's log the raw body if available
                 $rawInput = file_get_contents('php://input');
                 Log::info('Raw PUT input', ['input' => $rawInput, 'user_id' => Auth::id()]);
                 // Note: Parsing multipart/form-data from raw input is non-trivial.
                 // If raw input is empty, the issue is before reaching PHP.
                 // If raw input has data but $request->all() is empty, parsing failed.
            }
            
            // If it's not a multipart PUT, or if Laravel's parser should work,
            // rely on $request->all()
            $requestData = $request->all();

            Log::info('Password update request data (processed)', ['data' => $requestData, 'user_id' => Auth::id()]);

            // Re-run validation with potentially corrected request data
            $validator = \Illuminate\Support\Facades\Validator::make($requestData, [
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', 'confirmed', Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                ],
            ], [
                'current_password.required' => 'Пожалуйста, введите текущий пароль',
                'current_password.current_password' => 'Неверный текущий пароль',
                'new_password.required' => 'Пожалуйста, введите новый пароль',
                'new_password.confirmed' => 'Пароли не совпадают',
                'new_password.min' => 'Новый пароль должен содержать минимум 8 символов',
                'new_password.mixed' => 'Новый пароль должен содержать заглавные и строчные буквы',
                'new_password.numbers' => 'Новый пароль должен содержать цифры',
                'new_password.symbols' => 'Новый пароль должен содержать специальные символы',
            ]);

            if ($validator->fails()) {
                 throw new ValidationException($validator);
            }

            $validated = $validator->validated();

            $user = Auth::user();
            
            if (!$user) {
                Log::error('User not found during password update');
                return response()->json([
                    'success' => false,
                    'message' => 'Пользователь не найден'
                ], 404);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            Log::info('Password updated successfully', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Пароль успешно изменен'
            ]);

        } catch (ValidationException $e) {
            Log::error('Validation error during password update', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating password', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при смене пароля: ' . $e->getMessage()
            ], 500);
        }
    }
} 