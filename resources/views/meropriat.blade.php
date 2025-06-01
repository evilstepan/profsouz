@extends('layouts.main')

@section('title', 'О нас')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/meropriat.css') }}">
@endsection

@section('content')

    <section class="main-container1">
    <div class="main-container">
        <div class="content-section">
            <h2>Календарь событий</h2> {{-- Изменил заголовок на "Календарь событий" --}}

            <!-- Вывод сообщений об ошибках и успехе -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Получаем только мероприятия со статусом 'accepted' и готовим данные для JavaScript --}}
            @php
                $acceptedEvents = App\Models\Event::where('status', 'accepted')->get()->map(function($event) {
                    $event->image_url = $event->image_path ? asset('storage/' . $event->image_path) : asset('img/mer.WEBP');
                    $event->current_participants = $event->getCurrentParticipantsCount();
                    $event->has_available_spots = $event->hasAvailableSpots();
                    return $event;
                });
            @endphp

            {{-- Контейнер для календаря --}}
            <div id="calendar-container">
                {{-- Заголовок календаря (месяц и год) и кнопки навигации --}}
                <div class="calendar-header">
                    <button id="prevMonth" class="btn"><i class="fas fa-chevron-left"></i></button>
                    <h2 id="currentMonthYear"></h2>
                    <button id="nextMonth" class="btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                {{-- Дни недели --}}
                <div class="calendar-weekdays"></div>
                {{-- Сетка дней месяца --}}
                <div class="calendar-days"></div>
            </div>

            {{-- Контейнер для всплывающей карточки мероприятия --}}
            <div id="event-popup" class="event-popup" style="display: none;">
                <div class="popup-content">
                    <button class="close-popup">&times;</button>
                    <div class="event-image">
                        <img id="popup-image" src="" alt="Event Image">
                    </div>
                    <div class="event-details">
                        <h3 id="popup-title"></h3>
                        <div class="event-info">
                            <p><i class="fas fa-calendar"></i> <span id="popup-date"></span></p>
                            <p><i class="fas fa-map-marker-alt"></i> <span id="popup-location"></span></p>
                            <p><i class="fas fa-user"></i> <span id="popup-responsible"></span></p>
                            <p><i class="fas fa-users"></i> <span id="popup-participants"></span></p>
                        </div>
                        <div class="event-description">
                            <p id="popup-description"></p>
                        </div>
                        <div class="event-actions">
                            <button id="participate-btn" class="btn-participate">Участвовать</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Передаем подготовленные данные о мероприятиях в JavaScript --}}
            <script>
                const events = @json($acceptedEvents);
                window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
                console.log('Loaded events:', events);
            </script>

        </div>
    </div>
</section>

{{-- Подключаем JS файл для календаря --}}
<script src="{{ asset('js/meropriat.js') }}"></script>





@endsection