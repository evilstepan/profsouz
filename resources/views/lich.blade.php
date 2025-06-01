<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Личный кабинет</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/kabinet.css') }}">
    <script src="{{ asset('js/lich.js') }}" defer></script>
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="profile-card">
                @if (Auth::check())
                    <a class="profile-link" href="{{ route('index7') }}">
                        <div class="profile-image">
                            <img src="{{ asset('img/free-icon-boy-4537069.png') }}" alt="Фото профиля" id="profile-pic">
                        </div>
                    </a>
                    <div class="profile-info">
                        <h2 id="user-name">{{ Auth::user()->full_name }}</h2>
                        <p id="user-email">{{ Auth::user()->email }}</p>
                        <p id="user-phone">{{ Auth::user()->phone }}</p>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="profile-link">
                        <div class="profile-image">
                            <img src="{{ asset('img/free-icon-boy-4537069.png') }}" alt="Фото профиля" id="profile-pic">
                        </div>
                    </a>
                @endif
            </div>

            <nav class="main-nav">
                @if(request()->routeIs('lich'))
                    @if(Auth::user()->role === 'organize')
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=orders'">
                            <i class="fas fa-clipboard-list"></i>
                            Заявки
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=events'">
                            <i class="fas fa-calendar-alt"></i>
                            Мероприятия
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=create'">
                            <i class="fas fa-calendar-plus"></i>
                            Заказать мероприятие
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="nav-item">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Выход
                            </button>
                        </form>
                    @else
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=edit'">
                            <i class="fas fa-user-edit"></i>
                            Редактировать профиль
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=history'">
                            <i class="fas fa-history"></i>
                            Мои мероприятия
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=create'">
                            <i class="fas fa-calendar-plus"></i>
                            Заказать мероприятие
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=accepted'">
                            <i class="fas fa-check-circle"></i>
                            Принятые мероприятия
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="nav-item">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Выйти
                            </button>
                        </form>
                    @endif
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="nav-item">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Выйти
                        </button>
                    </form>
                @endif
            </nav>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Личный кабинет</h1>
                <div class="content-nav">
                    @if(Auth::user()->role === 'organize')
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=orders'">
                            <i class="fas fa-clipboard-list"></i>
                            Заявки
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=events'">
                            <i class="fas fa-calendar-alt"></i>
                            Мероприятия
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=create'">
                            <i class="fas fa-calendar-plus"></i>
                            Заказать мероприятие
                        </button>
                    @else
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=edit'">
                            <i class="fas fa-user-edit"></i>
                            Редактировать профиль
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=history'">
                            <i class="fas fa-history"></i>
                            Мои мероприятия
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=create'">
                            <i class="fas fa-calendar-plus"></i>
                            Заказать мероприятие
                        </button>
                        <button class="nav-item" onclick="window.location.href='{{ route('lich') }}?tab=accepted'">
                            <i class="fas fa-check-circle"></i>
                            Принятые мероприятия
                        </button>
                    @endif
                </div>
            </div>

            <div class="content-body">
                @if(Auth::user()->role === 'organize')
                    @if(request()->get('tab') === 'orders')
                        <div class="card orders-list">
                            <h3>Заявки на мероприятия</h3>
                            <div class="search-box">
                                <input type="text" id="searchEvent" placeholder="Поиск по названию мероприятия..." onkeyup="searchEvents()">
                            </div>
                            <div class="events-grid">
                                @forelse($orderedEvents as $event)
                                    <div class="event-card" data-name="{{ strtolower($event->name) }}">
                                        <div class="event-image">
                                            @if ($event->image_path)
                                                <img src="{{ asset('storage/'.$event->image_path) }}" alt="Изображение">
                                            @else
                                                <img src="{{ asset('img/mer.WEBP') }}" alt="Изображение по умолчанию">
                                            @endif
                                        </div>
                                        <div class="event-content">
                                            <h4>{{ $event->name }}</h4>
                                            <div class="event-details">
                                                <p><i class="fas fa-calendar"></i> {{ $event->date_time ? $event->date_time->format('Y-m-d H:i') : 'Дата не указана' }}</p>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                                                <p><i class="fas fa-user"></i> {{ $event->responsible_person }}</p>
                                                <p><i class="fas fa-users"></i> {{ $event->getCurrentParticipantsCount() }}/{{ $event->max_participants ?? '∞' }}</p>
                                                @if($event->description)
                                                    <p><i class="fas fa-info-circle"></i> {{ strip_tags($event->description) }}</p>
                                                @endif
                                            </div>
                                            <div class="event-actions">
                                                <form action="{{ route('event.status.update', $event->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="status-controls">
                                                        <select name="status" class="status-select">
                                                            <option value="pending" {{ $event->status === 'pending' ? 'selected' : '' }}>Ожидает</option>
                                                            <option value="accepted" {{ $event->status === 'accepted' ? 'selected' : '' }}>Принято</option>
                                                            <option value="rejected" {{ $event->status === 'rejected' ? 'selected' : '' }}>Отклонено</option>
                                                        </select>
                                                        <button type="submit" class="btn-update">
                                                            <i class="fas fa-sync-alt"></i> Обновить
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="no-events">Нет заявок на мероприятия.</p>
                                @endforelse
                            </div>
                        </div>
                    @elseif(request()->get('tab') === 'events')
                        <div class="card events-list">
                            <h3>Мои мероприятия</h3>
                            <div class="search-box">
                                <input type="text" id="searchMyEvent" placeholder="Поиск по названию мероприятия..." onkeyup="searchMyEvents()">
                            </div>
                            <div class="events-grid">
                                @forelse($myEvents as $event)
                                    <div class="event-card" data-name="{{ strtolower($event->name) }}">
                                        <div class="event-image">
                                            @if($event->image_path)
                                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->name }}">
                                            @else
                                                <img src="{{ asset('img/mer.WEBP') }}" alt="Default event image">
                                            @endif
                                        </div>
                                        <div class="event-content">
                                            <h4>{{ $event->name }}</h4>
                                            <div class="event-details">
                                                <p><i class="fas fa-calendar"></i> {{ $event->date_time }}</p>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                                                <p><i class="fas fa-user"></i> {{ $event->responsible_person }}</p>
                                                @if($event->description)
                                                    <p><i class="fas fa-info-circle"></i> {{ $event->description }}</p>
                                                @endif
                                            </div>
                                            <div class="event-actions">
                                                <form action="{{ route('event.destroy', $event->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger" onclick="return confirm('Вы уверены, что хотите удалить это мероприятие?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="no-events">У вас пока нет созданных мероприятий.</p>
                                @endforelse
                            </div>
                            <div class="add-event-button">
                                <button onclick="toggleOrderEventForm()" class="btn-primary">
                                    <i class="fas fa-plus"></i> Создать мероприятие
                                </button>
                            </div>
                        </div>
                    @elseif(request()->get('tab') === 'create')
                        <div class="card create-event">
                            <h3>Создать мероприятие</h3>
                            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="event-name">Название мероприятия</label>
                                    <input type="text" id="event-name" name="event-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="event-date-time">Дата и время</label>
                                    <input type="datetime-local" id="event-date-time" name="event-date-time" 
                                           min="{{ now()->format('Y-m-d\TH:i') }}" 
                                           max="{{ now()->addYears(10)->format('Y-m-d\TH:i') }}" 
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="event-location">Место проведения</label>
                                    <input type="text" id="event-location" name="event-location" required>
                                </div>
                                <div class="form-group">
                                    <label for="responsible-person">Ответственное лицо</label>
                                    <input type="text" id="responsible-person" name="responsible-person" required>
                                </div>
                                <div class="form-group">
                                    <label for="max-participants">Максимальное количество участников</label>
                                    <input type="number" id="max-participants" name="max-participants" min="1">
                                </div>
                                <div class="form-group">
                                    <label for="event-image">Изображение</label>
                                    <input type="file" id="event-image" name="event-image" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <textarea id="description" name="description" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn-submit">Создать мероприятие</button>
                            </form>
                        </div>
                    @endif
                @else
                    @if(request()->get('tab') === 'edit')
                        <div class="card edit-form">
                            <h3>Редактировать профиль</h3>
                            <form id="edit-profile-form" action="{{ route('profile.update') }}" method="POST" onsubmit="event.preventDefault(); saveProfile();">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="edit-full-name">ФИО</label>
                                    <input type="text" name="full_name" id="edit-full-name" value="{{ Auth::user()->full_name }}" required />
                                </div>
                                <div class="form-group">
                                    <label for="edit-email">Email</label>
                                    <input type="email" name="email" id="edit-email" value="{{ Auth::user()->email }}" required />
                                </div>
                                <div class="form-group">
                                    <label for="edit-phone">Телефон</label>
                                    <input type="tel" name="phone" id="edit-phone" value="{{ Auth::user()->phone }}" required />
                                </div>
                                <button type="submit" class="btn-primary">Сохранить изменения</button>
                            </form>
                            <div class="password-change-link">
                                <a href="{{ route('password.change') }}" class="btn-secondary">
                                    <i class="fas fa-key"></i> Изменить пароль
                                </a>
                            </div>
                        </div>
                    @elseif(request()->get('tab') === 'history')
                        <div class="card orders-list">
                            <h3>Мои мероприятия</h3>
                            <div class="filter-controls">
                                <input type="text" id="searchMyHistoryEvents" placeholder="Поиск по названию..." onkeyup="filterMyHistoryEvents()">
                                <select id="statusFilterMyHistoryEvents" onchange="filterMyHistoryEvents()">
                                    <option value="">Все статусы</option>
                                    <option value="pending">Ожидает</option>
                                    <option value="accepted">Принято</option>
                                    <option value="rejected">Отклонено</option>
                                </select>
                            </div>
                            <div class="events-grid">
                                @forelse($orderedEvents as $event)
                                    <div class="event-card">
                                        <div class="event-image">
                                            @if ($event->image_path)
                                                <img src="{{ asset('storage/'.$event->image_path) }}" 
                                                    alt="Изображение">
                                            @else
                                                <img src="{{ asset('img/mer.WEBP') }}" alt="Изображение по умолчанию">
                                            @endif
                                        </div>
                                        <div class="event-content">
                                            <h4>{{ $event->name }}</h4>
                                            <div class="event-details">
                                                <p><i class="fas fa-calendar"></i> {{ $event->date_time ? $event->date_time->format('Y-m-d H:i') : 'Дата не указана' }}</p>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</p>
                                                <p><i class="fas fa-user"></i> {{ $event->responsible_person }}</p>
                                                <p><i class="fas fa-users"></i> {{ $event->getCurrentParticipantsCount() }}/{{ $event->max_participants ?? '∞' }}</p>
                                                @if($event->description)
                                                    <p><i class="fas fa-info-circle"></i> {{ strip_tags($event->description) }}</p>
                                                @endif
                                            </div>
                                            <div class="event-actions">
                                                <span class="status-badge {{ strtolower($event->status ?? 'pending') }}">
                                                    {{ $event->status ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="no-events">Нет заказанных мероприятий.</p>
                                @endforelse
                            </div>
                        </div>
                    @elseif(request()->get('tab') === 'create')
                        <div class="card create-event">
                            <h3>Создать мероприятие</h3>
                            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="event-name">Название мероприятия</label>
                                    <input type="text" id="event-name" name="event-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="event-date-time">Дата и время</label>
                                    <input type="datetime-local" id="event-date-time" name="event-date-time" 
                                           min="{{ now()->format('Y-m-d\TH:i') }}" 
                                           max="{{ now()->addYears(10)->format('Y-m-d\TH:i') }}" 
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="event-location">Место проведения</label>
                                    <input type="text" id="event-location" name="event-location" required>
                                </div>
                                <div class="form-group">
                                    <label for="responsible-person">Ответственное лицо</label>
                                    <input type="text" id="responsible-person" name="responsible-person" required>
                                </div>
                                <div class="form-group">
                                    <label for="max-participants">Максимальное количество участников</label>
                                    <input type="number" id="max-participants" name="max-participants" min="1">
                                </div>
                                <div class="form-group">
                                    <label for="event-image">Изображение</label>
                                    <input type="file" id="event-image" name="event-image" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <textarea id="description" name="description" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn-submit">Создать мероприятие</button>
                            </form>
                        </div>
                    @elseif(request()->get('tab') === 'accepted')
                        <div class="card accepted-events-list">
                            <h3>Принятые мероприятия</h3>
                            <div class="events-grid">
                                @if($acceptedEvents->count() > 0)
                                    @foreach($acceptedEvents as $acceptedEvent)
                                        <div class="event-card">
                                            <div class="event-image">
                                                @if($acceptedEvent->event->image_path)
                                                    <img src="{{ Storage::url($acceptedEvent->event->image_path) }}" 
                                                        alt="{{ $acceptedEvent->event->name }}">
                                                @else
                                                    <img src="{{ asset('img/mer.WEBP') }}" alt="Default event image">
                                                @endif
                                            </div>
                                            <div class="event-content">
                                                <h4>{{ $acceptedEvent->event->name }}</h4>
                                                <div class="event-details">
                                                    <p><i class="fas fa-calendar"></i> {{ $acceptedEvent->event->date_time }}</p>
                                                    <p><i class="fas fa-map-marker-alt"></i> {{ $acceptedEvent->event->location }}</p>
                                                    <p><i class="fas fa-user"></i> {{ $acceptedEvent->event->responsible_person }}</p>
                                                    @if($acceptedEvent->event->description)
                                                        <p><i class="fas fa-info-circle"></i> {{ $acceptedEvent->event->description }}</p>
                                                    @endif
                                                </div>
                                                <form action="{{ route('accepted-events.destroy', $acceptedEvent->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger" 
                                                        onclick="return confirm('Вы уверены, что хотите отменить участие?')">
                                                        Отменить участие
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="no-events">Вы пока не приняли участие ни в одном мероприятии.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </main>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3>Подтверждение удаления</h3>
            <p id="deleteConfirmationMessage"></p>
            <div class="modal-actions">
                <button id="confirmDeleteBtn" class="btn-danger">Да, удалить</button>
                <button id="cancelDeleteBtn" class="btn-secondary">Отмена</button>
            </div>
        </div>
    </div>

    <script>
        function toggleEditForm() {
            const form = document.getElementById('edit-form');
            if (!form) return;
            
            const isHidden = form.style.display === 'none';
            form.style.display = isHidden ? 'block' : 'none';
            
            document.querySelectorAll('.card').forEach(card => {
                if (card.id !== 'edit-form') {
                    card.style.display = 'none';
                }
            });
        }

        function toggleEventsList() {
            const list = document.getElementById('events-list');
            if (!list) return;
            
            const isHidden = list.style.display === 'none';
            list.style.display = isHidden ? 'block' : 'none';
            
            document.querySelectorAll('.card').forEach(card => {
                if (card.id !== 'events-list') {
                    card.style.display = 'none';
                }
            });
        }

        function toggleOrderEventForm() {
            const form = document.getElementById('order-event-form');
            if (!form) return;
            
            const isHidden = form.style.display === 'none';
            form.style.display = isHidden ? 'block' : 'none';
            
            document.querySelectorAll('.card').forEach(card => {
                if (card.id !== 'order-event-form') {
                    card.style.display = 'none';
                }
            });
        }

        function toggleAcceptedEventsList() {
            const list = document.getElementById('accepted-events-list');
            if (!list) return;
            
            const isHidden = list.style.display === 'none';
            list.style.display = isHidden ? 'block' : 'none';
            
            document.querySelectorAll('.card').forEach(card => {
                if (card.id !== 'accepted-events-list') {
                    card.style.display = 'none';
                }
            });
        }

        function searchEvents() {
            const input = document.getElementById('searchEvent');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('.event-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchMyEvents() {
            const input = document.getElementById('searchMyEvent');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('.events-list .event-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterMyHistoryEvents() {
            const searchInput = document.getElementById('searchMyHistoryEvents');
            const statusFilter = document.getElementById('statusFilterMyHistoryEvents');
            const filterText = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value;

            const cards = document.querySelectorAll('.orders-list .event-card'); // Используем orders-list, так как этот класс окружает события в истории

            cards.forEach(card => {
                const nameElement = card.querySelector('h4');
                const statusElement = card.querySelector('.status-badge');

                if (!nameElement || !statusElement) {
                    // Пропустить карточки, которые не содержат нужных элементов
                    return;
                }

                const eventName = nameElement.innerText.toLowerCase();
                const eventStatus = statusElement.innerText.toLowerCase().trim(); // Учитываем пробелы и регистр

                const matchesSearch = eventName.includes(filterText);
                const matchesStatus = selectedStatus === '' || eventStatus === selectedStatus;

                if (matchesSearch && matchesStatus) {
                    card.style.display = ''; // Показать карточку
                } else {
                    card.style.display = 'none'; // Скрыть карточку
                }
            });
        }

        // Добавляем класс active для текущей вкладки
        document.addEventListener('DOMContentLoaded', function() {
            const currentTab = new URLSearchParams(window.location.search).get('tab') || 'orders';
            const navItems = document.querySelectorAll('.content-nav .nav-item');
            
            navItems.forEach(item => {
                const href = item.getAttribute('onclick');
                if (href && href.includes(`tab=${currentTab}`)) {
                    item.classList.add('active');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const maxParticipantsInput = document.getElementById('max-participants');
            const unlimitedCheckbox = document.getElementById('unlimited-participants');

            unlimitedCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    maxParticipantsInput.value = '';
                    maxParticipantsInput.disabled = true;
                } else {
                    maxParticipantsInput.disabled = false;
                }
            });

            maxParticipantsInput.addEventListener('input', function() {
                if (this.value) {
                    unlimitedCheckbox.checked = false;
                }
            });

            // Валидация даты
            const dateTimeInput = document.getElementById('event-date-time');
            
            // Устанавливаем минимальную дату (текущая дата)
            const now = new Date();
            const minDate = now.toISOString().slice(0, 16);
            dateTimeInput.min = minDate;
            
            // Устанавливаем максимальную дату (текущая дата + 10 лет)
            const maxDate = new Date(now.getFullYear() + 10, now.getMonth(), now.getDate());
            dateTimeInput.max = maxDate.toISOString().slice(0, 16);
            
            // Форматируем ввод даты
            dateTimeInput.addEventListener('input', function(e) {
                const value = e.target.value;
                if (value) {
                    const date = new Date(value);
                    const formattedDate = date.toISOString().slice(0, 16);
                    e.target.value = formattedDate;
                }
            });
        });
    </script>

    <style>
    .card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .btn-refresh {
        padding: 8px 16px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-refresh:hover {
        background-color: #357abd;
    }

    .event-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }

    .event-content {
        padding: 15px;
    }

    .event-details {
        margin: 15px 0;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
    }

    .event-details p {
        margin: 8px 0;
        color: #333;
    }

    .event-details i {
        margin-right: 8px;
        color: #4a90e2;
    }

    .event-actions {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .status-select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        font-size: 14px;
        cursor: pointer;
        min-width: 150px;
    }

    .status-select:hover {
        border-color: #4a90e2;
    }

    .status-select:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .refresh-button {
        margin-bottom: 15px;
        text-align: right;
    }

    .status-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-update {
        padding: 8px 16px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-update:hover {
        background-color: #357abd;
    }

    .main-nav {
        display: flex;
        flex-direction: column;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        color: #fff;
        text-decoration: none;
        border: none;
        background: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .nav-item:hover {
        background: #f5f5f5;
        color: #666;
    }

    .nav-item:hover i {
        color: #666;
    }

    .nav-item i {
        width: 20px;
        text-align: center;
        color: #fff;
    }

    .logout-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        color: #fff;
        font: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0;
    }

    .logout-btn:hover {
        color: #666;
    }

    .logout-btn i {
        color: #fff;
    }

    .logout-btn:hover i {
        color: #666;
    }

    #logout-form {
        margin-top: auto;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .search-box input:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .content-header {
        margin-bottom: 30px;
    }

    .content-nav {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding: 5px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .content-nav .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: #666;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .content-nav .nav-item::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #4a90e2;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .content-nav .nav-item:hover {
        color: #4a90e2;
        background: rgba(74, 144, 226, 0.05);
    }

    .content-nav .nav-item:hover::before {
        width: 80%;
    }

    .content-nav .nav-item i {
        font-size: 1.1em;
        color: inherit;
        transition: transform 0.3s ease;
    }

    .content-nav .nav-item:hover i {
        transform: translateY(-2px);
    }

    /* Стиль для активной вкладки */
    .content-nav .nav-item.active {
        color: #4a90e2;
        background: rgba(74, 144, 226, 0.1);
    }

    .content-nav .nav-item.active::before {
        width: 80%;
    }

    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
        .content-nav {
            flex-wrap: wrap;
            justify-content: center;
        }

        .content-nav .nav-item {
            padding: 10px 15px;
            font-size: 0.9em;
        }
    }

    .max-participants-input {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        margin: 0;
    }

    .checkbox-group label {
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }

    .order-event-form {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="datetime-local"],
    .form-group input[type="number"],
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }

    .form-actions {
        margin-top: 30px;
        text-align: center;
    }

    .btn-primary {
        padding: 12px 24px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #357abd;
    }

    .btn-primary i {
        font-size: 18px;
    }
    </style>
</body>
</html>
