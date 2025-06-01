<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Смена пароля</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/kabinet.css')); ?>">
    <style>
        .password-container {
            position: relative;
            width: 100%;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            padding: 0;
            z-index: 2;
        }
        
        .password-toggle:hover {
            color: #333;
        }
        
        .password-input {
            padding-right: 40px;
        }
        
        .password-change-page {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .password-change-page h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
        }
        
        .back-link:hover {
            color: #333;
        }
        
        .back-link i {
            margin-right: 5px;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
        }

        .notification.success {
            background-color: #00b894;
        }

        .notification.error {
            background-color: #ff7675;
        }

        .notification i {
            font-size: 18px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .validation-error {
            color: #ff7675;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .form-group.error input {
            border-color: #ff7675;
        }
    </style>
</head>
<body>
    <div class="password-change-page">
        <a href="<?php echo e(route('lich')); ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Вернуться в личный кабинет
        </a>
        
        <h1>Смена пароля</h1>
        
        <div class="card">
            <form id="change-password-form" action="<?php echo e(route('password.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="form-group">
                    <label for="current_password">Текущий пароль</label>
                    <div class="password-container">
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               class="password-input"
                               required />
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="validation-error" id="current_password_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="new_password">Новый пароль</label>
                    <div class="password-container">
                        <input type="password" 
                               name="new_password" 
                               id="new_password" 
                               class="password-input"
                               required 
                               minlength="8" 
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                               title="Пароль должен содержать минимум 8 символов, включая цифры, заглавные и строчные буквы" />
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="validation-error" id="new_password_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirmation">Подтвердите новый пароль</label>
                    <div class="password-container">
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation" 
                               class="password-input"
                               required />
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="validation-error" id="new_password_confirmation_error"></span>
                </div>
                
                <button type="submit" class="btn-primary">Изменить пароль</button>
            </form>
        </div>
    </div>

    <script>
        function showNotification(message, type = 'error') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function clearErrors() {
            document.querySelectorAll('.validation-error').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-group').forEach(el => el.classList.remove('error'));
        }

        function showFieldError(fieldId, message) {
            const errorElement = document.getElementById(`${fieldId}_error`);
            const fieldContainer = document.getElementById(fieldId).parentElement.parentElement;
            if (errorElement) {
                errorElement.textContent = message;
                fieldContainer.classList.add('error');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    this.querySelector('i').className = `fas fa-eye${type === 'password' ? '' : '-slash'}`;
                });
            });

            // Form submission
            const form = document.getElementById('change-password-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearErrors();
                
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;
                const currentPassword = document.getElementById('current_password').value;
                
                // Валидация текущего пароля
                if (!currentPassword) {
                    showFieldError('current_password', 'Пожалуйста, введите текущий пароль');
                    return;
                }
                
                // Валидация нового пароля
                if (!newPassword) {
                    showFieldError('new_password', 'Пожалуйста, введите новый пароль');
                    return;
                }
                
                if (newPassword.length < 8) {
                    showFieldError('new_password', 'Новый пароль должен содержать минимум 8 символов');
                    return;
                }
                
                if (!/[A-Z]/.test(newPassword)) {
                    showFieldError('new_password', 'Пароль должен содержать хотя бы одну заглавную букву');
                    return;
                }
                
                if (!/[a-z]/.test(newPassword)) {
                    showFieldError('new_password', 'Пароль должен содержать хотя бы одну строчную букву');
                    return;
                }
                
                if (!/[0-9]/.test(newPassword)) {
                    showFieldError('new_password', 'Пароль должен содержать хотя бы одну цифру');
                    return;
                }
                
                if (!/[!@#$%^&*]/.test(newPassword)) {
                    showFieldError('new_password', 'Пароль должен содержать хотя бы один специальный символ (!@#$%^&*)');
                    return;
                }
                
                // Валидация подтверждения пароля
                if (!confirmPassword) {
                    showFieldError('new_password_confirmation', 'Пожалуйста, подтвердите новый пароль');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    showFieldError('new_password_confirmation', 'Пароли не совпадают');
                    return;
                }
                
                const formData = new FormData(this);
                
                // Показываем индикатор загрузки
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Изменение пароля...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Пароль успешно изменен', 'success');
                        setTimeout(() => {
                            window.location.href = '<?php echo e(route("lich")); ?>';
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Ошибка при смене пароля');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.errors) {
                        Object.keys(error.errors).forEach(key => {
                            showFieldError(key, error.errors[key][0]);
                        });
                    } else {
                        showNotification(error.message || 'Произошла ошибка при смене пароля');
                    }
                })
                .finally(() => {
                    // Восстанавливаем кнопку
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            });

            // Log form data before submission for debugging
            form.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                console.log('Form Data before Fetch:');
                for (let pair of formData.entries()) {
                    console.log(pair[0]+ ': ' + pair[1]);
                }
            });
        });
    </script>
</body>
</html> <?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/auth/change-password.blade.php ENDPATH**/ ?>