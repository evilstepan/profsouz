<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Order; // Убедитесь, что модель существует
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Получаем данные, необходимые для главной страницы админ-панели (например, сводку)
        // В данном случае пока просто загрузим представление admin.index
        return view('admin.index');
    }

    public function showUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function showOrders()
    {
        $events = Event::all(); // Получаем все мероприятия
        return view('admin.orders', compact('events')); // Передаем переменную в представление
    }

    public function showEvents()
    {
        $events = Event::all();
        return view('admin.events', compact('events'));
    }

    public function destroyEvent(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events')->with('success', 'Мероприятие удалено');
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $event->status = $request->status;
        $event->save();

        if ($event->status === 'accepted') {
            // Логика для добавления мероприятия в meropriat
            return redirect()->route('admin.orders')->with('success', 'Мероприятие принято.');
        } elseif ($event->status === 'rejected') {
            // Удаляем мероприятие
            $event->delete();
            return redirect()->route('admin.events')->with('success', 'Мероприятие отклонено и удалено.');
        }

        // Изменено: перенаправление на /admin/orders при статусе pending
        return redirect()->route('admin.orders')->with('success', 'Статус мероприятия обновлен.');
    }

    public function destroyUser(User $user)
    {
        // Удаляем пользователя
        $user->delete();

        // Возвращаем обновленный список пользователей
        return redirect()->route('admin.users')->with('success', 'Пользователь удален'); // Перенаправляем с сообщением об успехе
    }

    public function destroyOrder(Order $order)
    {
        $order->delete();

        return redirect('http://127.0.0.1:8000/admin/orders')->with('success', 'Заказ удален'); // Изменено на указанный URL
    }
}
