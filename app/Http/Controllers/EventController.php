<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AcceptedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all()->map(function ($event) {
            $event->current_participants = $event->getCurrentParticipantsCount();
            $event->has_available_spots = $event->hasAvailableSpots();
            return $event;
        });
        return view('admin.events.index', compact('events'));
    }

    public function myEvents()
    {
        $orderedEvents = Auth::user()->events;
        return view('lich', compact('orderedEvents'));
    }

    public function store(Request $request)
    {
        // Валидация данных запроса
        $validated = $request->validate([
            'event-name' => 'required|string|max:255',
            'event-date-time' => 'required|date',
            'event-location' => 'required|string|max:255',
            'responsible-person' => 'required|string|max:255',
            'event-image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'max-participants' => 'nullable|integer|min:1'
        ]);

        // Обработка загрузки изображения
        $imagePath = null;
        if ($request->hasFile('event-image')) {
            $imageName = time() . '.' . $request->file('event-image')->getClientOriginalExtension();
            $imagePath = $request->file('event-image')->storeAs('images', $imageName, 'public');
        }

        // Создание события с ID аутентифицированного пользователя
        Auth::user()->events()->create([
            "name" => $validated['event-name'],
            "date_time" => $validated['event-date-time'],
            "location" => $validated['event-location'],
            "responsible_person" => $validated['responsible-person'],
            "image_path" => $imagePath,
            "description" => $validated['description'] ?? null,
            "user_id" => Auth::id(),
            "max_participants" => $request->input('max-participants')
        ]);

        return redirect('/lich')->with('success', 'Мероприятие успешно создано!');
    }

    public function participate(Request $request)
    {
        // Получаем мероприятие
        $event = \App\Models\Event::findOrFail($request->event_id);

        // Проверяем наличие свободных мест
        if (!$event->hasAvailableSpots()) {
            return response()->json([
                'success' => false,
                'error' => 'К сожалению, все места на это мероприятие уже заняты.'
            ]);
        }

        if (Auth::check()) {
            // Для авторизованных пользователей
            $request->validate([
                'event_id' => 'required|exists:events,id',
            ]);

            $existingParticipation = \App\Models\AcceptedEvent::where('user_id', \Auth::id())
                ->where('event_id', $request->event_id)
                ->exists();

            if ($existingParticipation) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Вы уже участвуете в этом мероприятии.'
                ]);
            }

            \App\Models\AcceptedEvent::create([
                'user_id' => \Auth::id(),
                'event_id' => $request->event_id,
            ]);

            $event->status = 'accepted';
            $event->save();

            // Обновляем количество участников
            $currentParticipants = $event->getCurrentParticipantsCount();
            $hasAvailableSpots = $event->hasAvailableSpots();

            return response()->json([
                'success' => true, 
                'message' => 'Вы успешно записались на мероприятие!',
                'current_participants' => $currentParticipants,
                'has_available_spots' => $hasAvailableSpots,
                'max_participants' => $event->max_participants
            ]);
        } else {
            // Для неавторизованных пользователей
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'comment' => 'nullable|string|max:1000'
            ]);

            // Проверяем, не подавал ли уже заявку пользователь с таким email
            $existingParticipation = \App\Models\EventParticipation::where('event_id', $request->event_id)
                ->where('email', $request->email)
                ->exists();

            if ($existingParticipation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Вы уже подавали заявку на участие в этом мероприятии.'
                ]);
            }

            // Создаем запись о заявке
            $participation = \App\Models\EventParticipation::create([
                'event_id' => $request->event_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'comment' => $request->comment,
                'status' => 'accepted' // Автоматически принимаем заявку
            ]);
            
            // Отправляем уведомление на email гостя
            try {
                \Mail::to($request->email)->send(new \App\Mail\GuestParticipationNotification($event, $participation));
            } catch (\Exception $e) {
                \Log::error('Failed to send email: ' . $e->getMessage());
            }

            // Обновляем количество участников
            $currentParticipants = $event->getCurrentParticipantsCount();
            $hasAvailableSpots = $event->hasAvailableSpots();

            return response()->json([
                'success' => true,
                'message' => 'Спасибо за регистрацию! Мы отправили подтверждение на ваш email.',
                'current_participants' => $currentParticipants,
                'has_available_spots' => $hasAvailableSpots,
                'max_participants' => $event->max_participants
            ]);
        }
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.orders')->with('success', 'Мероприятие успешно удалено.');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Валидация данных
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date',
            'location' => 'required|string|max:255',
            'responsible_person' => 'required|string|max:255',
        ]);

        // Обновление данных
        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Данные мероприятия обновлены.');
    }

    public function showOrderedEvents()
    {
        // Получаем заказанные мероприятия для текущего пользователя
        $orderedEvents = Event::where('user_id', auth()->id())->get();
        return view('lich', compact('orderedEvents'));
    }

    public function showAcceptedEvents()
    {
        $acceptedEvents = AcceptedEvent::where('user_id', auth()->id())
            ->with('event')
            ->get();

        return view('lich', compact('acceptedEvents'));
    }

    public function lich()
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, авторизуйтесь.');
        }

        if (Auth::user()->role === 'organize') {
            // Для организаторов показываем заявки со статусом pending и их собственные мероприятия
            $orderedEvents = Event::where('status', 'pending')->get();
            $myEvents = Event::where('user_id', Auth::id())->get();
            $acceptedEvents = collect(); // Пустая коллекция для организаторов
            return view('lich', compact('orderedEvents', 'acceptedEvents', 'myEvents'));
        }

        // Для обычных пользователей показываем их мероприятия
        $acceptedEvents = AcceptedEvent::where('user_id', Auth::id())->with('event')->get();
        $orderedEvents = Event::where('user_id', Auth::id())->get();

        return view('lich', compact('acceptedEvents', 'orderedEvents'));
    }

    public function destroyAcceptedEvent($id)
    {
        // Находим запись о принятом мероприятии
        $acceptedEvent = AcceptedEvent::findOrFail($id);

        // Проверяем, что текущий пользователь является владельцем записи
        if ($acceptedEvent->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет прав на удаление этого мероприятия.');
        }

        // Удаляем запись
        $acceptedEvent->delete();

        // Возвращаем пользователя с сообщением об успехе
        return redirect()->back()->with('success', 'Мероприятие успешно удалено из вашего списка.');
    }

    public function updateStatus(Request $request, Event $event)
    {
        // Проверяем, что пользователь является организатором
        if (Auth::user()->role !== 'organize') {
            return redirect()->back()->with('error', 'У вас нет прав для выполнения этого действия.');
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $event->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Статус мероприятия успешно обновлен');
    }
}
