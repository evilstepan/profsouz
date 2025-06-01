<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventParticipationConfirmation;

class EventParticipationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'comment' => 'nullable|string|max:1000'
        ]);

        try {
            $event = Event::findOrFail($request->event_id);
            
            // Отправляем email с подтверждением
            Mail::to($request->email)->send(new EventParticipationConfirmation($event, $request->all()));

            return response()->json([
                'success' => true,
                'message' => 'Спасибо за регистрацию! Мы отправили подтверждение на ваш email.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при отправке заявки. Пожалуйста, попробуйте позже.'
            ], 500);
        }
    }
} 