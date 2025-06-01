<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Название сайта')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles') {{-- Секция для добавления уникальных стилей страницы --}}
</head>
<body>
    <!-- ШАПКА -->
    <div class="navbar">

        <p class="logo-text">
        <a href="{{ url('index') }}">
            <img alt="Organization Logo" height="50" src="{{ asset('img/logo.png') }}" width="150"/>
            Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове
        </a>
        </p>
        <div class="nav-barimg">
            <a href="{{ url('about') }}">О нас</a>
            <a href="{{ url('meropriat') }}">Календарь событий</a>
            <a href="{{ url('lich') }}">Заказать мероприятие</a>
            @if(Auth::check() && Auth::user()->role === 'admin') {{-- Проверка, авторизован ли пользователь и является ли он админом --}}
                <a href="{{ url('admin') }}">Панель администратора</a>
            @endif

            @guest {{-- Ссылки для неавторизованных пользователей --}}
                <a href="{{ route('login') }}">Вход</a>
                <a href="{{ route('register') }}">Регистрация</a>
            @endguest

            {{-- Профиль пользователя и выпадающее меню --}}
            @auth {{-- Проверяем, авторизован ли пользователь --}}
            <div class="profile-container" onclick="toggleDropdown()" style="position: relative; display: inline-block;">
                <a href="{{ route('lich') }}" style="display: flex; align-items: center;">
                    <img id="profile-pic" src="{{ asset('img/free-icon-boy-4537069.png') }}" alt="Фотография профиля" style="cursor:pointer; width: 40px; height: 40px; border-radius: 50%;"/>
                    <p style="margin-left: 10px;">{{ Auth::user()->name ? explode(' ', Auth::user()->name)[0] : '' }}</p>
                </a>
                <ul id="dropdown" class="dropdown-content" style="display: none; position: absolute; background-color: white; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1; right: 0; min-width: 160px; padding: 12px 16px; list-style: none;">
                    <li style="padding: 8px 0;"><a href="{{ url('lich') }}" style="text-decoration: none; color: black;">Профиль</a></li>
                    <li style="padding: 8px 0;"><a href="{{ url('lich') }}" style="text-decoration: none; color: black;">Мои заявки</a></li>
                    <li style="padding: 8px 0;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; padding: 0; color: black; cursor: pointer; text-decoration: none; font-size: 1em;">Выход</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>

    {{-- Основное содержимое страницы --}}
    @yield('content')

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
                    <p><svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.11-.21c1.21.49 2.53.76 3.88.76.55 0 1 .45 1 1v3.5c0 .55-.45 1-1 1C10.29 22 2 13.71 2 3.5 2 2.95 2.45 2.5 3 2.5H6.5c.55 0 1 .45 1 1 0 1.35.27 2.67.76 3.88.14.34.07.73-.21 1.11l-2.2 2.2z"/></svg> +7 (906) 561-43-22</p>
                    <p><svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg> Alex@eseur.ru</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Базовые скрипты --}}
    <script src="{{ asset('js/script.js') }}" defer></script>

    {{-- Секция для добавления уникальных скриптов страницы --}}
    @yield('scripts')

    {{-- Скрипт для выпадающего меню профиля (перенесено сюда, так как используется в шапке) --}}
    <script>
    function toggleDropdown() {
        document.getElementById("dropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('#profile-pic')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    </script>

</body>
</html>