<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class EventOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Attempting to order event', ['user_id' => Auth::id()]);

            $validated = $request->validate([
                'event-name' => 'required|string|max:255',
                'event-date-time' => 'required|date',
                'event-location' => 'required|string|max:255',
                'responsible-person' => 'required|string|max:255',
                'participants' => 'required|string|max:1000',
                'event-image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
            ]);

            // Сохраняем изображение, если оно было загружено
            $imagePath = null;
            if ($request->hasFile('event-image')) {
                $imagePath = $request->file('event-image')->store('event_images', 'public');
            }

            // Создаем новое мероприятие
            $event = new \App\Models\Event();
            $event->name = $validated['event-name'];
            $event->date_time = Carbon::parse($validated['event-date-time'])->format('Y-m-d H:i:s');
            $event->location = $validated['event-location'];
            $event->responsible_person = $validated['responsible-person'];
            $event->participants = $validated['participants'];
            $event->description = $validated['description'] ?? null;
            $event->image_path = $imagePath;
            $event->user_id = Auth::id();
            $event->status = 'pending';
            $event->save();

            Log::info('Event ordered successfully', [
                'event_id' => $event->id,
                'user_id' => Auth::id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Заказ мероприятия успешно отправлен на рассмотрение.'
                ]);
            }

            return redirect()->back()->with('success', 'Заказ мероприятия успешно отправлен на рассмотрение.');

        } catch (ValidationException $e) {
            Log::error('Validation error during event order', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка валидации при заказе мероприятия',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Error ordering event', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Произошла ошибка при заказе мероприятия: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Произошла ошибка при заказе мероприятия: ' . $e->getMessage())
                ->withInput();
        }
    }
}
