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
            <h1>Принятые мероприятия</h1>
        </div>

        @if(session('success'))
            <div class="admin-success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-search-form">
            <div class="admin-search-wrapper">
                <input type="text" id="eventSearchInput" class="admin-search-input" placeholder="Поиск по названию, месту или ответственному..." value="{{ request('search') }}">
                <div class="admin-search-btn">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="admin-cards">
            @forelse($events->where('status', 'accepted') as $event)
            <div class="admin-card" data-search="{{ strtolower($event->name . ' ' . $event->location . ' ' . $event->responsible_person) }}">
                <div class="admin-card-header">
                    <h3>{{ $event->name }}</h3>
                    <span class="status-badge status-active">Принято</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($event->date_time)->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $event->responsible_person }}</span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Принято: {{ \Carbon\Carbon::parse($event->updated_at)->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить это мероприятие?')">
                            <i class="fas fa-trash"></i>
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="admin-empty">
                <i class="fas fa-calendar-alt"></i>
                <p>Нет принятых мероприятий</p>
                <span>Все принятые мероприятия отображаются здесь</span>
            </div>
            @endforelse
        </div>

        <div class="admin-pagination">
            {{ $events->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('eventSearchInput');
    const cardsContainer = document.querySelector('.admin-cards');
    const cards = cardsContainer.querySelectorAll('.admin-card');
    let emptyState = cardsContainer.querySelector('.admin-empty');

    // Создаем элемент для сообщения "не найдено", если его нет
    if (!emptyState) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'admin-empty';
        emptyDiv.innerHTML = '<i class="fas fa-calendar-alt"></i><p>Нет принятых мероприятий</p><span>Все принятые мероприятия отображаются здесь</span>'; // Используем тот же HTML, что и в Blade
        cardsContainer.appendChild(emptyDiv);
        emptyState = emptyDiv; // Update reference
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

        // Показываем/скрываем сообщение "Нет принятых мероприятий"
        if (emptyState) {
             emptyState.style.display = hasVisibleCards ? 'none' : 'block';
        }
         // Скрываем пагинацию если поиск активен и нет видимых карт
        const pagination = document.querySelector('.admin-pagination');
        if (pagination) {
             pagination.style.display = (searchTerm === '' || hasVisibleCards) ? '' : 'none';
        }
    };

    searchInput.addEventListener('input', filterCards);

    // Триггерим событие input при загрузке страницы, если есть значение в поле поиска или если нет мероприятий изначально
    // Проверяем, есть ли вообще какие-либо карточки мероприятий на странице
     if (searchInput.value || cards.length === 0) {
         filterCards();
    } else {
         // Если есть мероприятия и поле поиска пустое, убедимся, что сообщение скрыто
         if (emptyState) {
             emptyState.style.display = 'none';
         }
         const pagination = document.querySelector('.admin-pagination');
         if (pagination) {
              pagination.style.display = '';
         }
    }
});
</script>
@endsection