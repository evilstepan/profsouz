function saveProfile() {
    const form = document.getElementById('edit-profile-form');
    const formData = new FormData(form);
    
    // Очищаем предыдущие ошибки
    clearMessages();
    
    // Показываем индикатор загрузки
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    
    // Форматируем телефон перед отправкой
    let phone = formData.get('phone').replace(/\D/g, '');
    if (phone.length === 10 && phone[0] === '9') {
        phone = '7' + phone;
    }
    phone = '+7 ' + phone.substring(1, 4) + ' ' + 
            phone.substring(4, 7) + '-' + 
            phone.substring(7, 9) + '-' + 
            phone.substring(9, 11);
    
    // Собираем данные формы
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        phone: phone
    };
    
    fetch(form.action, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw { status: response.status, data: data };
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Успешное обновление
            document.getElementById('user-name').innerText = data.user.name;
            document.getElementById('user-email').innerText = `Email: ${data.user.email}`;
            document.getElementById('user-phone').innerText = `Телефон: ${data.user.phone}`;
            
            // Удаляем классы ошибок
            form.querySelectorAll('.input-error').forEach(input => {
                input.classList.remove('input-error');
            });
            
            // Восстанавливаем кнопку перед закрытием формы
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            toggleEditForm();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Ошибка при обновлении профиля', 'error');
            // Восстанавливаем кнопку при ошибке
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.status === 422) {
            // Обработка ошибок валидации
            const errors = error.data.errors;
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(`edit-${field}`);
                if (input) {
                    input.classList.add('input-error');
                    showMessage(errors[field][0], 'error-message', input);
                }
            });
            showNotification('Пожалуйста, исправьте ошибки в форме', 'error');
        } else {
            showNotification('Произошла ошибка при обновлении профиля', 'error');
        }
        // Восстанавливаем кнопку при ошибке
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// Закрытие всех форм
function closeAllForms() {
    const forms = [
        document.getElementById('edit-form'),
        document.getElementById('events-list'),
        document.getElementById('order-event-form'),
        document.getElementById('accepted-events-list') // Добавлено
    ];

    forms.forEach(form => {
        if (form) {
            form.style.display = 'none'; // Скрываем элемент
        }
    });
}

// Переключение формы редактирования профиля
function toggleEditForm() {
    const editForm = document.getElementById('edit-form');
    const eventsList = document.getElementById('events-list');
    const orderEventForm = document.getElementById('order-event-form');
    const acceptedEventsList = document.getElementById('accepted-events-list');
    const dashboardCards = document.querySelector('.dashboard-cards');

    if (editForm) {
        editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
    } else {
        console.error('Элемент с id "edit-form" не найден');
    }

    if (eventsList) {
        eventsList.style.display = 'none';
    }

    if (orderEventForm) {
        orderEventForm.style.display = 'none';
    }

    if (acceptedEventsList) {
        acceptedEventsList.style.display = 'none';
    }

    if (dashboardCards) {
        dashboardCards.style.display = editForm && editForm.style.display === 'block' ? 'none' : 'grid';
    }
}

// Переключение списка мероприятий
function toggleEventsList() {
    const editForm = document.getElementById('edit-form');
    const eventsList = document.getElementById('events-list');
    const orderEventForm = document.getElementById('order-event-form');
    const acceptedEventsList = document.getElementById('accepted-events-list');
    const dashboardCards = document.querySelector('.dashboard-cards');

    eventsList.style.display = eventsList.style.display === 'none' ? 'block' : 'none';
    editForm.style.display = 'none';
    orderEventForm.style.display = 'none';
    acceptedEventsList.style.display = 'none';
    dashboardCards.style.display = eventsList.style.display === 'block' ? 'none' : 'grid';
}

// Переключение формы заказа мероприятия
function toggleOrderEventForm() {
    const editForm = document.getElementById('edit-form');
    const eventsList = document.getElementById('events-list');
    const orderEventForm = document.getElementById('order-event-form');
    const acceptedEventsList = document.getElementById('accepted-events-list');
    const dashboardCards = document.querySelector('.dashboard-cards');

    orderEventForm.style.display = orderEventForm.style.display === 'none' ? 'block' : 'none';
    editForm.style.display = 'none';
    eventsList.style.display = 'none';
    acceptedEventsList.style.display = 'none';
    dashboardCards.style.display = orderEventForm.style.display === 'block' ? 'none' : 'grid';
}

// Переключение списка принятых мероприятий
function toggleAcceptedEventsList() {
    const editForm = document.getElementById('edit-form');
    const eventsList = document.getElementById('events-list');
    const orderEventForm = document.getElementById('order-event-form');
    const acceptedEventsList = document.getElementById('accepted-events-list');
    const dashboardCards = document.querySelector('.dashboard-cards');

    acceptedEventsList.style.display = acceptedEventsList.style.display === 'none' ? 'block' : 'none';
    editForm.style.display = 'none';
    eventsList.style.display = 'none';
    orderEventForm.style.display = 'none';
    dashboardCards.style.display = acceptedEventsList.style.display === 'block' ? 'none' : 'grid';
}

// Отмена мероприятия
function cancelEvent(eventName) {
    console.log("Отменяем мероприятие с именем:", eventName);
    openDeleteModal(eventName);
}

// Открытие модального окна для удаления
function openDeleteModal(eventName) {
    const deleteModal = document.getElementById("deleteModal");

    if (deleteModal) {
        deleteModal.style.display = "block";
        document.getElementById("deleteConfirmationMessage").innerText = `Вы уверены, что хотите удалить мероприятие "${eventName}"?`;

        document.getElementById("confirmDeleteBtn").onclick = function () {
            deleteEvent(eventName);
            closeDeleteModal();
        };

        document.getElementById("cancelDeleteBtn").onclick = function () {
            closeDeleteModal();
        };
    } else {
        console.error("Модальное окно для удаления не найдено.");
    }
}

// Удаление мероприятия
function deleteEvent(eventName) {
    const currentUser = JSON.parse(sessionStorage.getItem("currentUser"));
    let acceptedEvents = JSON.parse(localStorage.getItem("acceptedEvents")) || [];

    const updatedEvents = acceptedEvents.filter(event => event.name !== eventName || event.userId !== currentUser.email);

    if (updatedEvents.length !== acceptedEvents.length) {
        console.log("Мероприятие удалено, обновляем localStorage");
        localStorage.setItem("acceptedEvents", JSON.stringify(updatedEvents));

        const eventCard = document.getElementById(`event-${eventName}`);
        if (eventCard) {
            eventCard.remove();
            console.log(`Удалён элемент с ID: event-${eventName}`);
        } else {
            console.warn(`Элемент с ID: event-${eventName} не найден в DOM`);
        }
    } else {
        console.warn("Мероприятие с таким именем не найдено или уже удалено.");
    }
}

// Закрытие модального окна удаления
function closeDeleteModal() {
    const deleteModal = document.getElementById("deleteModal");

    if (deleteModal) {
        deleteModal.style.display = "none";
    }
}

// Загрузка мероприятий
function loadEvents() {
    const currentUser = JSON.parse(sessionStorage.getItem("currentUser"));
    const orders = JSON.parse(localStorage.getItem("eventOrders")) || [];
    const userOrders = orders.filter(order => order.userId === currentUser.email);
    const tbody = document.querySelector('#events-list tbody');

    tbody.innerHTML = '';

    userOrders.forEach(order => {
        const row = document.createElement("tr");
        const imageSrc = order.imageDataUrl || 'img/edinstvo.WEBP';

        row.innerHTML =
            `<td>${order.code}</td>` +
            `<td>${order.name}</td>` +
            `<td>${order.dateTime}</td>` +
            `<td>${order.location}</td>` +
            `<td>${order.responsible}</td>` +
            `<td>${order.participants}</td>` +
            `<td>${order.description}</td>` +
            `<td><img src="${imageSrc}" alt="${order.name} Image" style="width: 50px; height: auto;" /></td>` +
            `<td>${order.status}</td>`;

        tbody.appendChild(row);
    });
}

// Заказ мероприятия
function orderEvent(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Client-side validation (optional but good practice)
    if (!validateForm(form)) {
        showNotification('Пожалуйста, заполните все обязательные поля', 'error');
        return;
    }

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Show loading indicator
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Ошибка сервера: ' + response.status);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Заказ мероприятия успешно отправлен', 'success');
            form.reset();
            toggleOrderEventForm(); // Скрываем форму после успешной отправки
        } else {
            throw new Error(data.message || 'Ошибка при заказе мероприятия');
        }
    })
    .catch(error => {
        console.error('Error ordering event:', error);
        showNotification(error.message || 'Произошла ошибка при заказе мероприятия', 'error');
    })
    .finally(() => {
        // Restore button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// Отображение сообщений
function showMessage(message, className, inputField) {
    clearMessages();

    const messageElement = document.createElement('div');
    messageElement.className = className;
    messageElement.textContent = message;

    if (inputField) {
        inputField.parentNode.insertBefore(messageElement, inputField);
    } else {
        document.getElementById('messageContainer').appendChild(messageElement);
    }
}

// Очистка сообщений
function clearMessages() {
    const messages = document.querySelectorAll('.error-message, .success-message');
    messages.forEach(message => message.remove());
}

// Выход из системы
function logout() {
    sessionStorage.removeItem('currentUser');
    window.location.href = '/';
}

// Инициализация при загрузке страницы
document.addEventListener("DOMContentLoaded", () => {
    // Find the order event form and attach the event listener
    const orderEventForm = document.querySelector('#order-event-form form');
    if (orderEventForm) {
        orderEventForm.addEventListener('submit', orderEvent);
    }

    const letterOnlyFields = ['event-name', 'event-location', 'event-responsible', 'event-description'];
    letterOnlyFields.forEach(id => {
        const elem = document.getElementById(id);
        if (elem) {
            elem.addEventListener('keypress', (event) => {
                if (/\d/.test(event.key)) {
                    event.preventDefault();
                }
            });
        }
    });

    // Setup form submissions
    const editProfileForm = document.getElementById('edit-profile-form');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', (e) => {
            e.preventDefault();
            saveProfile();
        });
    }

    // Setup image previews
    setupImagePreview();

    // Setup modal close buttons
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        const deleteConfirmationMessage = document.getElementById('deleteConfirmationMessage');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

        cancelDeleteBtn.addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });
    }

    // Setup navigation items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('active');
                }
            }
        });
    });

    // Setup password change functionality
    setupPasswordChange();

    // Setup phone mask
    setupPhoneMask();
});

window.onpageshow = function(event) {
    if (event.persisted) {
        window.location.href = '/';
    }
};

// DOM Elements
const editForm = document.getElementById('edit-form');
const eventsList = document.getElementById('events-list');
const orderEventForm = document.getElementById('order-event-form');
const acceptedEventsList = document.getElementById('accepted-events-list');
const deleteModal = document.getElementById('deleteModal');
const deleteConfirmationMessage = document.getElementById('deleteConfirmationMessage');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

// Helper function to hide all sections
function hideAllSections() {
    const sections = [editForm, eventsList, orderEventForm, acceptedEventsList];
    sections.forEach(section => {
        if (section) {
            section.style.display = 'none';
        }
    });
}

// Form validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('input-error');
        } else {
            input.classList.remove('input-error');
        }
    });

    return isValid;
}

// Notification system
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Image preview for file inputs
function setupImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'image-preview';
                    
                    const container = input.parentElement;
                    const existingPreview = container.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    container.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    });
}

// Add smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Password change functionality
function setupPasswordChange() {
    const passwordForm = document.getElementById('change-password-form');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('new_password_confirmation');
    const currentPasswordInput = document.getElementById('current_password');

    if (passwordForm) {
        // Add password toggle buttons
        [newPasswordInput, confirmPasswordInput, currentPasswordInput].forEach(input => {
            if (input) {
                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.className = 'password-toggle';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
                
                input.parentElement.appendChild(toggleButton);
                
                toggleButton.addEventListener('click', () => {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    toggleButton.innerHTML = `<i class="fas fa-eye${type === 'password' ? '' : '-slash'}"></i>`;
                });
            }
        });

        // Password strength indicator
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', () => {
                const password = newPasswordInput.value;
                const strengthIndicator = document.createElement('div');
                strengthIndicator.className = 'password-strength';
                
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^A-Za-z0-9]/)) strength++;

                let strengthText = '';
                let strengthClass = '';
                
                switch(strength) {
                    case 0:
                    case 1:
                        strengthText = 'Слабый пароль';
                        strengthClass = 'weak';
                        break;
                    case 2:
                    case 3:
                        strengthText = 'Средний пароль';
                        strengthClass = 'medium';
                        break;
                    case 4:
                    case 5:
                        strengthText = 'Сильный пароль';
                        strengthClass = 'strong';
                        break;
                }

                strengthIndicator.textContent = strengthText;
                strengthIndicator.className = `password-strength ${strengthClass}`;

                const existingIndicator = newPasswordInput.parentElement.querySelector('.password-strength');
                if (existingIndicator) {
                    existingIndicator.remove();
                }
                newPasswordInput.parentElement.appendChild(strengthIndicator);
            });
        }

        // Form validation
        passwordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                showNotification('Пароли не совпадают', 'error');
                return;
            }

            if (newPasswordInput.value.length < 8) {
                showNotification('Пароль должен содержать минимум 8 символов', 'error');
                return;
            }

            const formData = new FormData(passwordForm);
            
            fetch(passwordForm.action, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Пароль успешно изменен', 'success');
                    passwordForm.reset();
                } else {
                    showNotification(data.message || 'Ошибка при смене пароля', 'error');
                }
            })
            .catch(error => {
                showNotification('Произошла ошибка при смене пароля', 'error');
                console.error('Error:', error);
            });
        });
    }
}

// Добавляем маску для телефона
function setupPhoneMask() {
    const phoneInput = document.getElementById('edit-phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            e.target.value = !x[2] ? x[1] : '+7 ' + x[1] + ' ' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '');
        });
    }
}