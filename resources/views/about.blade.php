@extends('layouts.main')

@section('title', 'О нас')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')


<div class="about-container">
    <!-- Hero секция -->
    <section class="about-hero">
        <div class="hero-content">
            <h1>Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове</h1>
            <p class="hero-subtitle">Защищаем права и интересы работников образования с 1990 года</p>
        </div>
    </section>

    <!-- Основные ценности -->
    <section class="values-section">
        <h2>Наши ценности</h2>
        <div class="values-grid">
            <div class="value-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Защита прав</h3>
                <p>Отстаиваем интересы работников образования на всех уровнях</p>
            </div>
            <div class="value-card">
                <i class="fas fa-hands-helping"></i>
                <h3>Поддержка</h3>
                <p>Оказываем всестороннюю помощь членам профсоюза</p>
            </div>
            <div class="value-card">
                <i class="fas fa-graduation-cap"></i>
                <h3>Развитие</h3>
                <p>Способствуем профессиональному росту педагогов</p>
            </div>
            <div class="value-card">
                <i class="fas fa-users"></i>
                <h3>Единство</h3>
                <p>Объединяем работников образования для достижения общих целей</p>
            </div>
        </div>
    </section>

    <!-- История организации -->
    <section class="history-section">
        <h2>Наша история</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>1990</h3>
                    <p>Основание организации</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>1995</h3>
                    <p>Расширение деятельности на весь регион</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>2005</h3>
                    <p>Внедрение новых программ поддержки</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>2024</h3>
                    <p>Современный этап развития</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Наши достижения -->
    <section class="achievements-section">
        <h2>Наши достижения</h2>
        <div class="achievements-grid">
            <div class="achievement-card">
                <div class="achievement-number">1000+</div>
                <p>Членов профсоюза</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">500+</div>
                <p>Успешно решенных вопросов</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">50+</div>
                <p>Образовательных учреждений</p>
            </div>
            <div class="achievement-card">
                <div class="achievement-number">30+</div>
                <p>Лет опыта работы</p>
            </div>
        </div>
    </section>

    <!-- О нас -->
    <section class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>О нас</h1>
                    <p>Профессиональные собрания науки и образования направлены на развитие и совершенствование профессиональных квалификаций в области науки и образования. Мы стремимся создать платформу для обмена опытом и знаниями между специалистами, а также поддерживать инновационные подходы в образовании.</p>
                    <p>Наша миссия — способствовать росту и развитию научной и образовательной деятельности, а также предоставлять возможности для профессионального роста и совершенствования.</p>
                </div>
                <!-- <div class="col-md-6">
                    <img src="{{ asset('images/about-us.jpg') }}" alt="О нас" class="img-fluid">
                </div> -->
            </div>
        </div>
    </section>

    <!-- Наша миссия -->
    <section class="our-mission">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Наша миссия</h2>
                    <p>Создание единой платформы для обмена знаниями и опытом между научными и образовательными учреждениями.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mission-box">
                        <i class="fas fa-book"></i>
                        <h3>Образование</h3>
                        <p>Содействие развитию инновационных образовательных программ.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-box">
                        <i class="fas fa-flask"></i>
                        <h3>Наука</h3>
                        <p>Поддержка научных исследований и разработок.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-box">
                        <i class="fas fa-users"></i>
                        <h3>Сообщество</h3>
                        <p>Создание сообщества единомышленников для обмена опытом.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
