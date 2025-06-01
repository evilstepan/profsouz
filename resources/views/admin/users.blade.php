@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
@php
    // Функция для преобразования ролей на русский язык
    function translateRole($role)
    {
        switch ($role) {
            case 'user':
                return 'Пользователь';
            case 'admin':
                return 'Администратор';
            case 'organizer':
                return 'Организатор';
            default:
                return $role; // Возвращаем оригинальное значение, если роль неизвестна
        }
    }
@endphp
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
            <h1>Пользователи</h1>
        </div>

        <div class="admin-search-form">
            <div class="admin-search-wrapper">
                <input type="text" id="searchInput" class="admin-search-input" placeholder="Поиск по имени..." value="{{ request('search') }}">
                <div class="admin-search-btn">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="admin-cards">
            @forelse($users as $user)
            <div class="admin-card" data-name="{{ strtolower($user->full_name ?? $user->name) }}">
                <div class="admin-card-header">
                    <h3>{{ $user->full_name ?? $user->name }}</h3>
                    <span class="status-badge status-{{ $user->role }}">{{ translateRole($user->role) }}</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span>ФИО: {{ $user->full_name ?? $user->name }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email: {{ $user->email }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-phone"></i>
                        <span>Телефон: {{ $user->phone }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Дата регистрации: {{ $user->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-briefcase"></i>
                        <span>Должность: {{ $user->position ?? 'Не указана' }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-id-card"></i>
                        <span>Паспорт: {{ $user->passport_series ?? '' }} {{ $user->passport_number ?? '' }}</span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="update-role-form">
                            @csrf
                            @method('PUT')
                            <div class="role-controls">
                                <label for="role-{{ $user->id }}">Роль:</label>
                                <select name="role" id="role-{{ $user->id }}" class="admin-form-input role-select">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>{{ translateRole('user') }}</option>
                                    <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>{{ translateRole('organizer') }}</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>{{ translateRole('admin') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full-width">Сохранить</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                            <i class="fas fa-trash"></i>
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="admin-empty">
                <p>Пользователи не найдены</p>
            </div>
            @endforelse
        </div>

        <div class="admin-pagination">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.admin-card');
    const cardsContainer = document.querySelector('.admin-cards');
    let emptyState = document.querySelector('.admin-empty');

    // Создаем элемент для сообщения "не найдено", если его нет
    if (!emptyState) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'admin-empty';
        emptyDiv.innerHTML = '<p>Пользователи не найдены</p>';
        cardsContainer.appendChild(emptyDiv);
        emptyState = emptyDiv; // Update reference
    }

    const filterCards = () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let hasVisibleCards = false;

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const emailElement = card.querySelector('.admin-info-item:nth-child(1) span');
            const phoneElement = card.querySelector('.admin-info-item:nth-child(2) span');

            const email = emailElement ? emailElement.textContent.toLowerCase() : '';
            const phone = phoneElement ? phoneElement.textContent.toLowerCase() : '';

            if (name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                phone.includes(searchTerm)) {
                card.style.display = '';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        // Показываем/скрываем сообщение "Пользователи не найдены"
        if (emptyState) {
            emptyState.style.display = hasVisibleCards ? 'none' : 'block';
        }
    };

    searchInput.addEventListener('input', filterCards);

    // Триггерим событие input при загрузке страницы, если есть значение в поле поиска или если нет пользователей изначально
    // Проверяем, есть ли вообще какие-либо карточки пользователей на странице
    if (searchInput.value || cards.length === 0) {
         filterCards();
    } else {
         // Если есть пользователи и поле поиска пустое, убедимся, что сообщение скрыто
         if (emptyState) {
             emptyState.style.display = 'none';
         }
    }
});
</script>
@endsection