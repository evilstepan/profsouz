<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        return view('admin.registrations.index', compact('pendingUsers'));
    }

    public function approve(Request $request, User $user)
    {
        try {
            // Генерируем уникальный номер членства
            $membershipNumber = 'PS' . date('Y') . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            
            // Генерируем временный пароль
            $password = Str::random(8);
            
            // Обновляем данные пользователя
            $user->update([
                'status' => 'approved',
                'membership_number' => $membershipNumber,
                'password' => Hash::make($password),
                'approved_at' => now(),
            ]);

            // Отправляем email с учетными данными
            Mail::send('emails.registration-approved', [
                'user' => $user,
                'password' => $password
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Ваша заявка одобрена');
            });

            return redirect()->back()->with('success', 'Заявка одобрена. Пользователю отправлены учетные данные.');
        } catch (\Exception $e) {
            Log::error('Ошибка при одобрении заявки: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при отправке email. Пожалуйста, проверьте настройки почты.');
        }
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $user->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            // Отправляем email с причиной отказа
            Mail::send('emails.registration-rejected', [
                'user' => $user,
                'reason' => $request->rejection_reason
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Ваша заявка отклонена');
            });

            return redirect()->back()->with('success', 'Заявка отклонена. Пользователю отправлено уведомление.');
        } catch (\Exception $e) {
            Log::error('Ошибка при отклонении заявки: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при отправке email. Пожалуйста, проверьте настройки почты.');
        }
    }
} 