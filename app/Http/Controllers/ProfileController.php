<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Получаем текущие значения пользователя
            $currentData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ];
            
            // Объединяем текущие данные с данными из запроса
            $data = array_merge($currentData, $request->only(['name', 'email', 'phone']));
            
            // Очищаем телефон от всех символов кроме цифр
            if (isset($data['phone'])) {
                $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
                // Добавляем +7 если номер начинается с 9
                if (strlen($data['phone']) === 10 && $data['phone'][0] === '9') {
                    $data['phone'] = '7' . $data['phone'];
                }
                // Форматируем номер
                $data['phone'] = '+7 ' . substr($data['phone'], 1, 3) . ' ' . 
                                substr($data['phone'], 4, 3) . '-' . 
                                substr($data['phone'], 7, 2) . '-' . 
                                substr($data['phone'], 9, 2);
            }
            
            $validated = $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => ['required', 'string', 'regex:/^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$/'],
            ], [
                'name.required' => 'Имя обязательно для заполнения',
                'email.required' => 'Email обязателен для заполнения',
                'email.email' => 'Введите корректный email адрес',
                'email.unique' => 'Этот email уже используется',
                'phone.required' => 'Телефон обязателен для заполнения',
                'phone.regex' => 'Введите корректный номер телефона (например: +7 999 123-45-67)',
            ]);

            // Обновляем только изменившиеся поля
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Данные профиля обновлены успешно',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении профиля: ' . $e->getMessage()
            ], 500);
        }
    }
}