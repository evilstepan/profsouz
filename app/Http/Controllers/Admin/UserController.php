<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('status', '!=', 'rejected');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Пользователь успешно удален');
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        // Проверяем, что текущий пользователь администратор
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Валидируем запрос
        $request->validate([
            'role' => ['required', 'string', Rule::in(['user', 'organizer', 'admin'])],
        ]);

        // Не позволяем администратору изменить свою собственную роль
        if (Auth::id() === $user->id && $request->role !== 'admin') {
            return redirect()->route('admin.users')->with('error', 'Нельзя изменить свою собственную роль администратора.');
        }

        // Обновляем роль пользователя
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Роль пользователя успешно обновлена.');
    }
} 