<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Event::where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Event $order)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders')
            ->with('success', 'Статус заявки успешно обновлен');
    }

    public function destroy(Event $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')
            ->with('success', 'Заявка успешно удалена');
    }
} 