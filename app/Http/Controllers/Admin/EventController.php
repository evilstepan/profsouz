<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'accepted')
            ->latest()
            ->paginate(10);

        return view('admin.events', compact('events'));
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events')
            ->with('success', 'Мероприятие успешно удалено');
    }
} 