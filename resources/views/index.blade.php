<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Организация</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>
<body>
    <!-- ШАПКА -->
    <div class="navbar">
        <p class="logo-text">
            <img alt="Organization Logo" height="50" src="{{ asset('img/logo.png') }}" width="150"/>
            Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове
        </p>
        <div>
            <a href="{{ url('about') }}">О нас</a>
            <a href="{{ route('meropriat') }}">Мероприятия</a>
            <a href="{{ route('calendar') }}">Календарь событий</a>
            <a href="{{ route('lich') }}">Заказать мероприятие</a>
            <a id="userLink" href="{{ route('login') }}">Вход/Регистрация</a>
            <img id="avatar" src="" alt="Avatar" style="display:none; width: 40px; height: 40px; border-radius: 50%;"/>
            <div class="icon-container">
                <div class="profile-icon" id="profile-icon" style="display:none;"></div> 
            </div>
        </div>
    </div>

    <!-- СЛАЙДЕР -->
    <div class="slider">
        <div class="slides">
            <!-- Слайды -->
            @foreach ($slides as $slide)
                <div class="slide">
                    <div class="text">
                        <h1>{{ $slide->title }}</h1>
                        <p>{{ $slide->description }}</p>
                        <a class="button" href="#">{{ $slide->button_text }}</a>
                    </div>
                    <div class="image">
                        <img alt="{{ $slide->alt }}" height="800" src="{{ $slide->image_url }}" width="400"/>
                    </div>
                </div>
            @endforeach
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
    </div>

    <section class="main-container1">
        <div class="main-container">
            <!-- МЕРОПРИЯТИЯ -->
            <div class="content-section">
                <h2>Мероприятия</h2>
                <div class="event-list">
                    @foreach ($events as $event)
                        <div class="event-item">
                            <img alt="{{ $event->title }} Image" src="{{ asset($event->image) }}"/>
                            <h3>{{ $event->title }}</h3>
                            <p>{{ $event->description }}</p>
                            <a href="#">Подробнее</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- СТАТИСТИКА -->
            <div class="content-section">
                <h2>РЕГИОНАЛЬНАЯ ОРГАНИЗАЦИЯ ОБЩЕРОССИЙСКОГО ПРОФСОЮЗА ОБРАЗОВАНИЯ В АЛЕКСАНДРОВЕ</h2>
                <div class="statistics">
                    @foreach ($statistics as $statistic)
                        <div class="statistic-item">
                            <h3 class="count" data-target="{{ $statistic['target'] }}">0</h3>
                            <p>{{ $statistic['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ФУТЕР -->
    <div class="footer">
        <div class="footer-left">
            <img alt="Organization Logo" height="50" src="{{ asset('img/logo.png') }}" width="50"/>
            <p>Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове</p>
        </div>
        <div class="footer-right">
            <div class="resources">
                <p>Наши контакты:</p>
                <div class="contact-info">
                    <p>Телефон: 8-800-555-35-35</p>
                    <p>Email: info@example.com</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
