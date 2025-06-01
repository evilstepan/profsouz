@extends('layouts.main')

@section('title', 'Telegram News')

@section('content')
{{-- Временно закомментированный контент для диагностики --}}
{{--
{{-- СЛАЙДЕР --}}
<div class="slider">
    <div class="slides">
        @foreach($slides as $slide)
            <div class="slide">
                <div class="text">
                    <h1>{{ $slide->title }}</h1>
                    <p>{{ $slide->description }}</p>
                    <a class="button" href="#">Читать</a>
                </div>
                <div class="image">
                    <img src="{{ asset($slide->image) }}" alt="">
                </div>
            </div>
        @endforeach
    </div>

    <a class="prev" onclick="plusSlides(-1)">❮</a>
    <a class="next" onclick="plusSlides(1)">❯</a>
</div>

{{-- Основной контент --}}
<section class="main-container1">
    <div class="main-container">
        {{-- МЕРОПРИЯТИЯ --}}
        <div class="content-section">
            <h2>Мероприятия</h2>
            <div class="event-list">
                @foreach($events as $event)
                    <div class="event-item">
                        <img alt="{{ $event->title }} Image" src="{{ asset($event->image) }}"/>
                        <h3>{{ $event->title }}</h3>
                        <p>{{ $event->description }}</p>
                        @if($event->link)
                            <a href="{{ $event->link }}">Подробнее</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- СТАТИСТИКА --}}
        <div class="content-section">
            <h2>РЕГИОНАЛЬНАЯ ОРГАНИЗАЦИЯ ОБЩЕРОССИЙСКОГО ПРОФСОЮЗА ОБРАЗОВАНИЯ В АЛЕКСАНДРОВЕ</h2>
            <div class="statistics">
                @foreach($statistics as $statistic)
                    <div class="statistic-item">
                        <h3 class="count" data-target="{{ $statistic->target }}">0</h3>
                        <p>{{ $statistic->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ЛИЧНЫЙ КАБИНЕТ (возможно, это должно быть на отдельной странице, но пока оставляем тут по структуре) --}}
<div class="profile-container" style="display: none;">
    <h2>Личный кабинет</h2>
    <div class="profile-info">
        <img id="profile-avatar" src="" alt="Аватар пользователя" width="100">
        <h3 id="profile-name"></h3>
        <p id="profile-email"></p>
    </div>
    <button onclick="logout()">Выход</button>
</div>

<script src="{{ asset('script/script.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.count');
        const speed = 1000; 

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target; 
                }
            };
            updateCount();
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const burgerMenu = document.querySelector(".burger-menu");
        const menu = document.querySelector(".menu");
        burgerMenu.addEventListener("click", function () {
            menu.classList.toggle("active");
        });
    });

    //  слайдер
    let slideIndex = 0;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        let slides = document.getElementsByClassName("slides")[0];
        let totalSlides = slides.children.length;
        if (n >= totalSlides) { slideIndex = 0 }
        if (n < 0) { slideIndex = totalSlides - 1 }
        slides.style.transform = `translateX(${-slideIndex * 100}%)`;
    }

    function autoSlide() {
        plusSlides(1);
        setTimeout(autoSlide, 5000); 
    }
    autoSlide();

    // формы
    function togglePassword() {
        var passwordInput = document.getElementById('password');
        var toggleIcon = document.querySelector('.toggle-password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // формы 2
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const date = document.getElementById('date').value;

        if (password !== document.getElementById('confirm-password').value) {
            alert("Пароли не совпадают!");
            return;
        }

        const user = { name, email, phone, password, date };
        let users = JSON.parse(localStorage.getItem('users')) || [];
        users.push(user);
        localStorage.setItem('users', JSON.stringify(users));
        alert("Регистрация прошла успешно!");
    });

    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        let users = JSON.parse(localStorage.getItem('users')) || [];

        const user = users.find(user => user.email === email && user.password === password);

        if (user) {
            localStorage.setItem('currentUser', JSON.stringify(user));
            alert("Вход выполнен успешно!");
            window.location.href = '/index7'; // Перенаправление после успешного входа
        } else {
            alert("Неверный email или пароль!");
        }
    });
</script>
@endsection
