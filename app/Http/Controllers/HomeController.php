<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = [
            (object) [
                'title' => 'ЧИТАЙТЕ НОВОСТИ ОРГАНИЗАЦИИ В TELEGRAM',
                'description' => 'Telegram-канал «Сила Профсоюза в тебе»...',
                'button_text' => 'Читать',
                'image_url' => 'https://storage.googleapis.com/a1aa/image/VUV7ffTaevB5yocQSQWZNZ8XTJ1ZKezYGvqVhQy0LvqLadWOB.jpg',
                'alt' => 'Telegram Channel'
            ],
        ];

        $events = [
            (object) ['title' => 'С ДНЕМ МОЛОДЕЖИ', 'description' => 'Сегодня мы отмечаем День молодежи...', 'image' => 'img/den_molodezhi.webp'],
            (object) ['title' => '23 ФЕВРАЛЯ!', 'description' => 'С 23 февраля, настоящие защитники!', 'image' => 'img/23.jpg'],
            (object) ['title' => 'ДЕНЬ ТРУДА', 'description' => 'С Днем труда!', 'image' => 'img/29042022expert_konsul_sovet_1_may.jpg'],
        ];

        $statistics = [
            ['target' => 60, 'label' => 'профсоюзных организаций'],
            ['target' => 8000, 'label' => 'членов профсоюза'],
            ['target' => 34418450, 'label' => 'средств направлено на помощь'],
        ];

        return view('index', compact('slides', 'events', 'statistics'));
    }
}