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
            <h1>Управление заявками на регистрацию</h1>
        </div>

        @if(session('success'))
            <div class="admin-success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-search-form">
            <div class="admin-search-wrapper">
                <input type="text" id="searchInput" class="admin-search-input" placeholder="Поиск по имени, email или телефону..." value="{{ request('search') }}">
                <div class="admin-search-btn">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="admin-cards">
            @forelse($pendingUsers as $user)
            <div class="admin-card" data-search="{{ strtolower($user->full_name . ' ' . $user->email . ' ' . $user->phone) }}">
                <div class="admin-card-header">
                    <h3>{{ $user->full_name }}</h3>
                    <span class="status-badge status-pending">На рассмотрении</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-section">
                        <h4>Личные данные</h4>
                        <div class="admin-info-item">
                            <i class="fas fa-user"></i>
                            <span>ФИО: {{ $user->full_name }}</span>
                        </div>
                        <div class="admin-info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Дата рождения: {{ $user->date_of_birth->format('d.m.Y') }}</span>
                        </div>
                        <div class="admin-info-item">
                            <i class="fas fa-envelope"></i>
                            <span>Email: {{ $user->email }}</span>
                        </div>
                        <div class="admin-info-item">
                            <i class="fas fa-phone"></i>
                            <span>Телефон: {{ $user->phone }}</span>
                        </div>
                    </div>

                    <div class="admin-info-section">
                        <h4>Трудовая информация</h4>
                        <div class="admin-info-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Должность: {{ $user->position }}</span>
                        </div>
                    </div>

                    <div class="admin-info-section">
                        <h4>Паспортные данные</h4>
                        <div class="admin-info-item">
                            <i class="fas fa-id-card"></i>
                            <span>Серия и номер: {{ $user->passport_series }} {{ $user->passport_number }}</span>
                        </div>
                        <div class="admin-info-item">
                            <i class="fas fa-calendar"></i>
                            <span>Дата выдачи: {{ $user->passport_issue_date->format('d.m.Y') }}</span>
                        </div>
                        <div class="admin-info-item">
                            <i class="fas fa-building"></i>
                            <span>Кем выдан: {{ $user->passport_issued_by }}</span>
                        </div>
                    </div>

                    <div class="admin-info-item">
                        <i class="fas fa-clock"></i>
                        <span>Подана: {{ $user->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <form action="{{ route('admin.registrations.approve', $user) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i>
                            Одобрить
                        </button>
                    </form>

                    <button onclick="showRejectModal({{ $user->id }})" class="btn btn-danger">
                        <i class="fas fa-times"></i>
                        Отклонить
                    </button>

                    <!-- Модальное окно для отказа -->
                    <div id="rejectModal{{ $user->id }}" class="modal">
                        <div class="modal-content">
                            <h3>Отклонить заявку</h3>
                            <form id="rejectForm{{ $user->id }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="rejection_reason">Причина отказа:</label>
                                    <textarea name="rejection_reason" id="rejection_reason" required class="admin-form-input"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger">Подтвердить отказ</button>
                                    <button type="button" onclick="closeRejectModal({{ $user->id }})" class="btn btn-secondary">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="admin-empty">
                <i class="fas fa-user-plus"></i>
                <p>Нет новых заявок на рассмотрение</p>
                <span>Все заявки на регистрацию отображаются здесь</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация поиска
    const searchInput = document.getElementById('searchInput');
    const cardsContainer = document.querySelector('.admin-cards');
    const cards = cardsContainer.querySelectorAll('.admin-card');
    let emptyState = cardsContainer.querySelector('.admin-empty');

    // Создаем элемент для сообщения "не найдено", если его нет
    if (!emptyState) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'admin-empty';
        emptyDiv.innerHTML = '<i class="fas fa-user-plus"></i><p>Нет новых заявок на рассмотрение</p><span>Все заявки на регистрацию отображаются здесь</span>';
        cardsContainer.appendChild(emptyDiv);
        emptyState = emptyDiv;
    }

    const filterCards = () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let hasVisibleCards = false;

        cards.forEach(card => {
            const searchData = card.dataset.search;

            if (searchData.includes(searchTerm)) {
                card.style.display = '';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            emptyState.style.display = hasVisibleCards ? 'none' : 'block';
        }
    };

    searchInput.addEventListener('input', filterCards);
    filterCards(); // Инициализация фильтрации

    // Скрываем все модальные окна при загрузке страницы
    document.querySelectorAll('.modal').forEach(modal => {
        modal.style.display = 'none';
    });
});

// Функции для управления модальным окном отказа
function showRejectModal(userId) {
    const modal = document.getElementById(`rejectModal${userId}`);
    const form = document.getElementById(`rejectForm${userId}`);
    
    if (!modal || !form) {
        console.error('Modal or form not found');
        return;
    }
    
    // Устанавливаем правильный URL для формы
    form.action = `/admin/registrations/${userId}/reject`;
    
    // Показываем модальное окно
    modal.style.display = 'block';
}

function closeRejectModal(userId) {
    const modal = document.getElementById(`rejectModal${userId}`);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Закрытие модального окна при клике вне его содержимого
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        const userId = event.target.id.replace('rejectModal', '');
        closeRejectModal(userId);
    }
}
</script>
@endsection 