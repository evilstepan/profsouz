<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Проверяем, существует ли пользователь
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Пользователь с таким email не найден.',
            ]);
        }

        // Проверяем статус только для обычных пользователей
        if ($user->role !== 'admin' && $user->status !== 'approved') {
            return back()->withErrors([
                'email' => 'Ваша учетная запись еще не одобрена администратором.',
            ]);
        }

        // Пытаемся выполнить вход
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Перенаправляем в зависимости от роли пользователя
            if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('index7');
            }
        }

        return back()->withErrors([
            'email' => 'Предоставленные учетные данные не соответствуют нашим записям.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}