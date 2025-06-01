{{-- resources/views/admin/orders.blade.php --}}
@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .admin-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        padding: 20px;
    }

    .admin-card {
        flex: 0 0 calc(25% - 23px);
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        border: 1px solid #e0e0e0;
        margin-bottom: 30px;
    }

    .admin-card:nth-child(4n) {
        margin-right: 0;
    }

    @media (max-width: 1200px) {
        .admin-card {
            flex: 0 0 calc(33.333% - 20px);
        }
    }

    @media (max-width: 900px) {
        .admin-card {
            flex: 0 0 calc(50% - 15px);
        }
    }

    @media (max-width: 600px) {
        .admin-card {
            flex: 0 0 100%;
        }
    }
</style>
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
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Пользователи
            </a>
            <a href="{{ route('admin.registrations.index') }}" class="{{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                Регистрации
            </a>
            <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                Заявки
            </a>
            <a href="{{ route('admin.events') }}" class="{{ request()->routeIs('admin.events') ? 'active' : '' }}">
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
            <h1>Заявки на мероприятия</h1>
        </div>

        @if(session('success'))
            <div class="admin-success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-cards">
            @forelse($orders as $order)
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3>{{ $order->name }}</h3>
                    <span class="status-badge status-pending">В ожидании</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $order->user->name }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($order->date_time)->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $order->location }}</span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <div class="status-controls">
                        <select name="status" class="admin-form-input">
                            <option value="">Выберите действие</option>
                            <option value="accepted">Принять</option>
                            <option value="rejected">Отклонить</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="status-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" class="status-input">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i>
                                Обновить
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                                <i class="fas fa-trash"></i>
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="admin-empty">
                <i class="fas fa-clipboard-list"></i>
                <p>Нет заявок на рассмотрение</p>
                <span>Все заявки обработаны или ожидают новых</span>
            </div>
            @endforelse
        </div>

        <div class="admin-pagination">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.admin-form-input');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('.admin-card-footer').querySelector('.status-form');
            const input = form.querySelector('.status-input');
            input.value = this.value;
        });
    });
});
</script>
@endsection