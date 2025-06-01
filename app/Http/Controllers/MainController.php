<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index7()
    {
        // Пример данных (замените на данные из вашей базы данных)
        $slides = [
            (object) ['title' => 'Зарядись энергией профсоюза!', 'description' => 'Описание слайда 1', 'image' => 'img/321.webp'],
            (object) ['title' => 'Конкурс студенческих агитационных бригад', 'description' => 'Описание слайда 2', 'image' => 'img/mer.webp'],
            (object) ['title' => 'Аудит образовательных организаций', 'description' => 'Описание слайда 3', 'image' => 'img/543543.webp']
        ];

        $events = [
            (object) ['title' => 'С ДНЕМ МОЛОДЕЖИ', 'description' => 'Сегодня мы отмечаем День молодежи...', 'image' => 'img/den_molodezhi.webp', 'link' => '#'],
            (object) ['title' => '23 ФЕВРАЛЯ!', 'description' => 'С 23 февраля, настоящие защитники!', 'image' => 'img/23.jpg', 'link' => null],
            (object) ['title' => 'ДЕНЬ ТРУДА', 'description' => 'С Днем труда! Этот день символизирует уважение...', 'image' => 'img/29042022expert_konsul_sovet_1_may.jpg', 'link' => null]
        ];

        $statistics = [
            (object) ['target' => 60, 'description' => 'профсоюзных организаций'],
            (object) ['target' => 8000, 'description' => 'членов профсоюза'],
            (object) ['target' => 34418450, 'description' => 'средств направлено на помощь']
        ];

        return view('main', compact('slides', 'events', 'statistics'));
    }
}


