@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h2>Админ-панель</h2>
            <div class="admin-user-info">
                <p>{{ Auth::user()->name }}</p>
                <span>Администратор</span>
            </div>
        </div>
        <nav class="admin-nav">
            <a href="{{ route('admin.users') }}">
                <i class="fas fa-users"></i>
                Пользователи
            </a>
            <a href="{{ route('admin.registrations.index') }}">
                <i class="fas fa-user-plus"></i>
                Регистрации
            </a>
            <a href="{{ route('admin.orders') }}">
                <i class="fas fa-clipboard-list"></i>
                Заявки
            </a>
            <a href="{{ route('admin.events') }}">
                <i class="fas fa-calendar-alt"></i>
                Мероприятия
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    <div class="admin-main">
        <div class="admin-header">
            <h1>Главная</h1>
        </div>

        {{-- Здесь можно добавить сводку или основные метрики --}}
        <div class="admin-cards">
            <a href="{{ route('admin.users') }}" class="admin-card admin-card-link">
                <div class="admin-card-body">
                    <i class="fas fa-users admin-card-icon"></i>
                    <h3>Пользователи</h3>
                </div>
            </a>

            <a href="{{ route('admin.orders') }}" class="admin-card admin-card-link">
                <div class="admin-card-body">
                    <i class="fas fa-clipboard-list admin-card-icon"></i>
                    <h3>Заявки</h3>
                </div>
            </a>

            <a href="{{ route('admin.events') }}" class="admin-card admin-card-link">
                 <div class="admin-card-body">
                    <i class="fas fa-calendar-alt admin-card-icon"></i>
                    <h3>Мероприятия</h3>
                 </div>
            </a>

        </div>

    </div>
</div>
@endsection 