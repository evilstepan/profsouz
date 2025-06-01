<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
            'phone.unique' => 'Пользователь с таким номером телефона уже зарегистрирован.',
            'last_name.required' => 'Пожалуйста, укажите фамилию.',
            'first_name.required' => 'Пожалуйста, укажите имя.',
            'email.required' => 'Пожалуйста, укажите email.',
            'email.email' => 'Пожалуйста, укажите корректный email.',
            'phone.required' => 'Пожалуйста, укажите номер телефона.',
            'date_of_birth.required' => 'Пожалуйста, укажите дату рождения.',
            'date_of_birth.before' => 'Вам должно быть не менее 18 лет.',
            'position.required' => 'Пожалуйста, укажите должность.',
            'passport_series.required' => 'Пожалуйста, укажите серию паспорта.',
            'passport_series.size' => 'Серия паспорта должна состоять из 4 цифр.',
            'passport_number.required' => 'Пожалуйста, укажите номер паспорта.',
            'passport_number.size' => 'Номер паспорта должен состоять из 6 цифр.',
            'passport_issue_date.required' => 'Пожалуйста, укажите дату выдачи паспорта.',
            'passport_issued_by.required' => 'Пожалуйста, укажите кем выдан паспорт.',
            'personal_data_agreement.required' => 'Необходимо дать согласие на обработку персональных данных.',
            'personal_data_agreement.accepted' => 'Необходимо дать согласие на обработку персональных данных.',
        ];

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'date_of_birth' => 'required|date_format:Y-m-d|before:-18 years',
            'position' => 'required|string|max:255',
            'passport_series' => 'required|string|size:4',
            'passport_number' => 'required|string|size:6',
            'passport_issue_date' => 'required|date_format:Y-m-d',
            'passport_issued_by' => 'required|string|max:255',
            'personal_data_agreement' => 'required|accepted',
        ], $messages);

        // Создаем пользователя со статусом pending
        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => Carbon::createFromFormat('Y-m-d', $request->date_of_birth),
            'position' => $request->position,
            'passport_series' => $request->passport_series,
            'passport_number' => $request->passport_number,
            'passport_issue_date' => Carbon::createFromFormat('Y-m-d', $request->passport_issue_date),
            'passport_issued_by' => $request->passport_issued_by,
            'personal_data_agreement' => true,
            'status' => 'pending',
            'password' => '', // Устанавливаем пустой пароль
            'role' => 'user' // Устанавливаем роль по умолчанию
        ]);

        // Отправляем уведомление пользователю о принятии заявки
        try {
            Mail::send('emails.registration-pending', [
                'user' => $user
            ], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Ваша заявка на регистрацию принята');
            });
        } catch (Exception $e) {
            // Логируем ошибку отправки email
            \Log::error('Failed to send registration email: ' . $e->getMessage());
        }

        return redirect()->route('registration.success')
                        ->with('success', 'Ваша заявка принята и находится на рассмотрении. После проверки данных администратором, вы получите письмо с вашими учетными данными для входа в систему.');
    }

    public function showRegistrationSuccess()
    {
        return view('auth.registration-success');
    }
}