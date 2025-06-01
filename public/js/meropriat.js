// document.addEventListener("DOMContentLoaded", function() {
//     const eventListContainer = document.querySelector(".event-list");
    
//     // Проверяем, есть ли мероприятия в контейнере
//     if (!eventListContainer) {
//         console.error("Контейнер для мероприятий не найден");
//         return;
//     }
//     const eventCards = document.querySelectorAll(".event-card");
    
//     eventCards.forEach(card => {
//         const participateBtn = document.createElement("button");
//         participateBtn.textContent = "Принять участие";
//         participateBtn.classList.add("participate-btn");
//         participateBtn.style = "display: inline-block; padding: 10px 20px; background-color: #6a1b9a; color: #fff; text-decoration: none; font-size: 16px; border-radius: 5px;";
        
//         participateBtn.addEventListener("click", function() {
//             const eventData = {
//                 name: card.querySelector("h3").textContent,
//                 description: card.querySelector("p:last-child")?.textContent || "",
//                 image: card.querySelector("img")?.src || "img/mer.WEBP"
//             };
//             // Instead of calling a function that might use POST, we log the event data
//             console.log("Participating in event:", eventData);
//             showMessage("Вы успешно записаны на мероприятие!", "success-message", participateBtn);
//         });

//         // Добавляем кнопку после блока с деталями события
//         const detailsBlock = card.querySelector('.event-details');
//         detailsBlock.appendChild(participateBtn);

//         // Удаляем ссылку "Подробнее", так как она ведет к неопределенному маршруту
//         const detailsLink = detailsBlock.querySelector('.btn-primary');
//         if (detailsLink) {
//             detailsLink.remove();
//         }
//     });
// });

// function showMessage(message, className, buttonElement) {
//     const eventCard = buttonElement.closest('.event-card');
    
//     // Создаем или находим контейнер для сообщений
//     let messageContainer = eventCard.querySelector('.event-message');
//     if (!messageContainer) {
//         messageContainer = document.createElement('div');
//         messageContainer.className = 'event-message';
//         eventCard.insertBefore(messageContainer, eventCard.firstChild);
//     }

//     clearMessages(messageContainer);

//     const messageElement = document.createElement('div');
//     messageElement.className = className;
//     messageElement.textContent = message;
//     messageContainer.appendChild(messageElement);

//     setTimeout(() => {
//         clearMessages(messageContainer);
//     }, 5000);
// }

// function clearMessages(messageContainer) {
//     if (messageContainer) {
//         messageContainer.innerHTML = '';
//     }
// }

document.addEventListener('DOMContentLoaded', () => {
    const calendarDays = document.querySelector('.calendar-days');
    const currentMonthYearHeader = document.getElementById('currentMonthYear');
    const prevMonthButton = document.getElementById('prevMonth');
    const nextMonthButton = document.getElementById('nextMonth');
    const eventPopup = document.getElementById('event-popup');

    let currentDate = new Date();
    let popupHideTimeout = null;

    function renderCalendar(date) {
        calendarDays.innerHTML = ''; // Очищаем предыдущие дни
        const year = date.getFullYear();
        const month = date.getMonth();

        // Устанавливаем заголовок месяца и года
        currentMonthYearHeader.textContent = `${date.toLocaleString('ru-RU', { month: 'long' })} ${year}`;

        // Получаем первый день месяца и последний день месяца
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();

        // Определяем день недели первого дня месяца (0 - воскресенье, 6 - суббота)
        const firstDayOfWeek = firstDayOfMonth.getDay();

        // Добавляем пустые ячейки перед первым днем месяца
        for (let i = 0; i < firstDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.classList.add('calendar-day', 'empty');
            calendarDays.appendChild(emptyDay);
        }

        // Добавляем дни месяца
        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            
            // Добавляем число дня для всех ячеек
            const dateNumber = document.createElement('div');
            dateNumber.classList.add('date-number');
            dateNumber.textContent = i;
            dayElement.appendChild(dateNumber);

            // Проверяем, есть ли события в этот день
            const eventsOnDay = events.filter(event => {
                const eventDate = new Date(event.date_time);
                const checkDate = new Date(year, month, i);
                eventDate.setHours(0, 0, 0, 0);
                checkDate.setHours(0, 0, 0, 0);
                return eventDate.getTime() === checkDate.getTime();
            });

            if (eventsOnDay.length > 0) {
                dayElement.classList.add('has-event');
                // Добавляем маркер
                const eventMarker = document.createElement('div');
                eventMarker.classList.add('event-marker');
                dayElement.appendChild(eventMarker);

                // Создаем карточку события (изначально скрытую)
                const eventCard = document.createElement('div');
                eventCard.classList.add('event-card');
                eventCard.setAttribute('data-event-id', eventsOnDay[0].id);
                const event = eventsOnDay[0];
                
                let imageUrl = '';
                if (event.image_path) {
                    imageUrl = event.image_path.startsWith('http') ? event.image_path : '/storage/' + event.image_path;
                }
                
                const eventDate = new Date(event.date_time);
                const formattedDate = eventDate.toLocaleDateString('ru-RU', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                const formattedTime = eventDate.toLocaleTimeString('ru-RU', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Ограничиваем длину описания для карточки
                const cardDescription = event.description || '';
                const maxCardLength = 100; // Максимальное количество символов для карточки
                const truncatedDescription = cardDescription.length > maxCardLength 
                    ? cardDescription.substring(0, maxCardLength) + '...' 
                    : cardDescription;

                eventCard.innerHTML = `
                <div class="event-card-content">
                    <h3>${event.name || ''}</h3>
                    <p><i class="fas fa-calendar"></i> ${formattedDate}</p>
                    <p><i class="fas fa-clock"></i> ${formattedTime}</p>
                    <p><i class="fas fa-map-marker-alt"></i> ${event.location || ''}</p>
                    <p class="participants-info"><i class="fas fa-users"></i> Свободных мест: ${event.max_participants ? event.max_participants - event.current_participants : '∞'}</p>
                    ${imageUrl ? `<div class="event-image"><img src="${imageUrl}" alt="${event.name}"></div>` : ''}
                    <p class="event-description">${truncatedDescription}</p>
                    <div class="event-card-buttons">
                        <button class="event-card-btn btn-participate" onclick="showParticipationForm(${event.id})" ${!event.has_available_spots ? 'disabled' : ''}>
                            <i class="fas fa-user-plus"></i> ${event.has_available_spots ? 'Участвовать' : 'Мест нет'}
                        </button>
                        <button class="event-card-btn btn-details" onclick="showEventDetails(${event.id})">
                            <i class="fas fa-info-circle"></i> Подробнее
                        </button>
                    </div>
                </div>
            `;
                
                dayElement.appendChild(eventCard);

                // Добавляем обработчики для показа/скрытия карточки
                dayElement.addEventListener('mouseenter', () => {
                    dayElement.classList.add('show-event');
                });

                dayElement.addEventListener('mouseleave', () => {
                    dayElement.classList.remove('show-event');
                });
            }

            calendarDays.appendChild(dayElement);
        }
    }

    function showEventPopup(event) {
        const popup = document.getElementById('event-popup');
        const title = document.getElementById('popup-title');
        const date = document.getElementById('popup-date');
        const image = document.getElementById('popup-image');
        const description = document.getElementById('popup-description');
        const location = document.getElementById('popup-location');
        const responsible = document.getElementById('popup-responsible');
        const participants = document.getElementById('popup-participants');
        const participateBtn = document.getElementById('participate-btn');

        // Форматируем дату
        const eventDate = new Date(event.date_time);
        const formattedDate = eventDate.toLocaleString('ru-RU', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Заполняем данные
        title.textContent = event.name;
        date.textContent = formattedDate;
        image.src = event.image_url;
        description.textContent = event.description || 'Описание отсутствует';
        location.textContent = event.location;
        responsible.textContent = event.responsible_person;
        participants.textContent = `${event.current_participants}/${event.max_participants || '∞'}`;

        // Настраиваем кнопку участия
        if (event.has_available_spots) {
            participateBtn.textContent = 'Участвовать';
            participateBtn.disabled = false;
            participateBtn.onclick = () => participateInEvent(event.id);
        } else {
            participateBtn.textContent = 'Мест нет';
            participateBtn.disabled = true;
        }

        // Показываем попап
        popup.style.display = 'flex';
    }

    function hideEventPopup() {
        // При этой логике скрытие попапа по таймауту не требуется
        // Скрытие будет происходить автоматически через CSS когда убирается :hover и класс show-event
        // const popup = document.getElementById('event-popup');
        // popup.style.display = 'none';
    }

    // Обработчики кнопок навигации
    prevMonthButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextMonthButton.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Изначально отрисовываем календарь для текущего месяца
    renderCalendar(currentDate);

    // Добавляем дни недели
    const weekdaysContainer = document.querySelector('.calendar-weekdays');
    const weekdays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    weekdaysContainer.innerHTML = '';

    weekdays.forEach(day => {
        const weekdayElement = document.createElement('div');
        weekdayElement.classList.add('weekday-name');
        weekdayElement.textContent = day;
        weekdaysContainer.appendChild(weekdayElement);
    });

    // После рендера календаря (или один раз после DOMContentLoaded):
    const popup = document.getElementById('event-popup'); // Этот элемент больше не используется для карточки
    // Удаляем старые обработчики наведения для этого элемента
    // popup.addEventListener('mouseenter', () => {
    //     clearTimeout(popupHideTimeout);
    // });
    // popup.addEventListener('mouseleave', () => {
    //     popupHideTimeout = setTimeout(() => {
    //         hideEventPopup();
    //     }, 300);
    // });
});

function showParticipationForm(eventId) {
    if (window.isAuthenticated) {
        // Для авторизованных отправляем POST-запрос на /events/participate
        fetch('/events/participate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                event_id: eventId
            })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                return response.json().then(data => {
                    throw new Error(data.error || 'Произошла ошибка при записи на мероприятие');
                });
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                showNotification(result.message || 'Вы успешно записались на мероприятие!', 'success');
                // Обновляем данные в массиве events
                const event = events.find(e => e.id === eventId);
                if (event) {
                    event.current_participants = result.current_participants;
                    event.has_available_spots = result.has_available_spots;
                    event.max_participants = result.max_participants;
                    
                    // Обновляем отображение в календаре
                    const calendarDay = document.querySelector(`.calendar-day[data-event-id="${eventId}"]`);
                    if (calendarDay) {
                        const eventCard = calendarDay.querySelector('.event-card');
                        if (eventCard) {
                            const participantsInfo = eventCard.querySelector('.participants-info');
                            if (participantsInfo) {
                                const availableSpots = result.max_participants ? result.max_participants - result.current_participants : '∞';
                                participantsInfo.innerHTML = `<i class="fas fa-users"></i> Свободных мест: ${availableSpots}`;
                            }

                            const participateBtn = eventCard.querySelector('.btn-participate');
                            if (participateBtn) {
                                participateBtn.disabled = !result.has_available_spots;
                                participateBtn.innerHTML = `<i class="fas fa-user-plus"></i> ${result.has_available_spots ? 'Участвовать' : 'Мест нет'}`;
                            }
                        }
                    }
                }
                // Перенаправляем на страницу принятых мероприятий
                setTimeout(() => {
                    window.location.href = '/lich?tab=accepted';
                }, 1200);
            } else {
                showNotification(result.error || 'Ошибка при записи на мероприятие', 'error');
            }
        })
        .catch(error => {
            showNotification(error.message || 'Ошибка при записи на мероприятие', 'error');
        });
        return;
    }

    // Для неавторизованных пользователей показываем форму
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Участие в мероприятии</h3>
            <form id="participationForm" class="participation-form">
                <input type="hidden" name="event_id" value="${eventId}">
                <div class="form-group">
                    <label for="name">Ваше имя *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="comment">Комментарий (необязательно)</label>
                    <textarea id="comment" name="comment" rows="3"></textarea>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Отправить заявку
                    </button>
                </div>
            </form>
        </div>
    `;

    document.body.appendChild(modal);

    // Обработчик закрытия модального окна
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.onclick = function() {
        document.body.removeChild(modal);
    };

    // Обработчик отправки формы
    const form = modal.querySelector('#participationForm');
    form.onsubmit = async function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch('/events/participate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Произошла ошибка при отправке формы');
            }

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                document.body.removeChild(modal);
                
                // Обновляем данные в массиве events
                const event = events.find(e => e.id === eventId);
                if (event) {
                    event.current_participants = result.current_participants;
                    event.has_available_spots = result.has_available_spots;
                    event.max_participants = result.max_participants;
                    
                    // Обновляем отображение в календаре
                    const calendarDay = document.querySelector(`.calendar-day[data-event-id="${eventId}"]`);
                    if (calendarDay) {
                        const eventCard = calendarDay.querySelector('.event-card');
                        if (eventCard) {
                            const participantsInfo = eventCard.querySelector('.participants-info');
                            if (participantsInfo) {
                                const availableSpots = result.max_participants ? result.max_participants - result.current_participants : '∞';
                                participantsInfo.innerHTML = `<i class="fas fa-users"></i> Свободных мест: ${availableSpots}`;
                            }

                            const participateBtn = eventCard.querySelector('.btn-participate');
                            if (participateBtn) {
                                participateBtn.disabled = !result.has_available_spots;
                                participateBtn.innerHTML = `<i class="fas fa-user-plus"></i> ${result.has_available_spots ? 'Участвовать' : 'Мест нет'}`;
                            }
                        }
                    }
                }
            } else {
                showNotification(result.error || 'Ошибка при отправке формы', 'error');
            }
        } catch (error) {
            showNotification(error.message || 'Произошла ошибка при отправке формы', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    };

    // Маска для телефона
    const phoneInput = form.querySelector('#phone');
    phoneInput.addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '+7 (' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
        ${message}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Закрытие попапа
document.querySelector('.close-popup').addEventListener('click', () => {
    document.getElementById('event-popup').style.display = 'none';
});

// Закрытие по клику вне попапа
document.getElementById('event-popup').addEventListener('click', (e) => {
    if (e.target === document.getElementById('event-popup')) {
        document.getElementById('event-popup').style.display = 'none';
    }
});