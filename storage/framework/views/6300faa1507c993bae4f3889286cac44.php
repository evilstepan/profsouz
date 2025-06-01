

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
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
?>
<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h2>Админ-панель</h2>
            <div class="admin-user-info">
                <p><?php echo e(Auth::user()->name); ?></p>
                <span>Администратор</span>
            </div>
        </div>
        <nav class="admin-nav">
            <a href="<?php echo e(route('admin.users')); ?>" class="<?php echo e(request()->routeIs('admin.users') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i>
                Пользователи
            </a>
            <a href="<?php echo e(route('admin.registrations.index')); ?>" class="<?php echo e(request()->routeIs('admin.registrations.*') ? 'active' : ''); ?>">
                <i class="fas fa-user-plus"></i>
                Регистрации
            </a>
            <a href="<?php echo e(route('admin.orders')); ?>" class="<?php echo e(request()->routeIs('admin.orders') ? 'active' : ''); ?>">
                <i class="fas fa-clipboard-list"></i>
                Заявки
            </a>
            <a href="<?php echo e(route('admin.events')); ?>" class="<?php echo e(request()->routeIs('admin.events') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-alt"></i>
                Мероприятия
            </a>
            <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Выйти
            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </nav>
    </div>

    <div class="admin-main">
        <div class="admin-header">
            <h1>Пользователи</h1>
        </div>

        <div class="admin-search-form">
            <div class="admin-search-wrapper">
                <input type="text" id="searchInput" class="admin-search-input" placeholder="Поиск по имени..." value="<?php echo e(request('search')); ?>">
                <div class="admin-search-btn">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="admin-cards">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="admin-card" data-name="<?php echo e(strtolower($user->full_name ?? $user->name)); ?>">
                <div class="admin-card-header">
                    <h3><?php echo e($user->full_name ?? $user->name); ?></h3>
                    <span class="status-badge status-<?php echo e($user->role); ?>"><?php echo e(translateRole($user->role)); ?></span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span>ФИО: <?php echo e($user->full_name ?? $user->name); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email: <?php echo e($user->email); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-phone"></i>
                        <span>Телефон: <?php echo e($user->phone); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Дата регистрации: <?php echo e($user->created_at->format('d.m.Y')); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-briefcase"></i>
                        <span>Должность: <?php echo e($user->position ?? 'Не указана'); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-id-card"></i>
                        <span>Паспорт: <?php echo e($user->passport_series ?? ''); ?> <?php echo e($user->passport_number ?? ''); ?></span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <?php if(Auth::user()->role === 'admin'): ?>
                        <form action="<?php echo e(route('admin.users.updateRole', $user)); ?>" method="POST" class="update-role-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="role-controls">
                                <label for="role-<?php echo e($user->id); ?>">Роль:</label>
                                <select name="role" id="role-<?php echo e($user->id); ?>" class="admin-form-input role-select">
                                    <option value="user" <?php echo e($user->role === 'user' ? 'selected' : ''); ?>><?php echo e(translateRole('user')); ?></option>
                                    <option value="organizer" <?php echo e($user->role === 'organizer' ? 'selected' : ''); ?>><?php echo e(translateRole('organizer')); ?></option>
                                    <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>><?php echo e(translateRole('admin')); ?></option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full-width">Сохранить</button>
                        </form>
                    <?php endif; ?>
                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                            <i class="fas fa-trash"></i>
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="admin-empty">
                <p>Пользователи не найдены</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="admin-pagination">
            <?php echo e($users->links()); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/admin/users.blade.php ENDPATH**/ ?>