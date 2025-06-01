

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
            <h1>Принятые мероприятия</h1>
        </div>

        <?php if(session('success')): ?>
            <div class="admin-success-message">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="admin-search-form">
            <div class="admin-search-wrapper">
                <input type="text" id="eventSearchInput" class="admin-search-input" placeholder="Поиск по названию, месту или ответственному..." value="<?php echo e(request('search')); ?>">
                <div class="admin-search-btn">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="admin-cards">
            <?php $__empty_1 = true; $__currentLoopData = $events->where('status', 'accepted'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="admin-card" data-search="<?php echo e(strtolower($event->name . ' ' . $event->location . ' ' . $event->responsible_person)); ?>">
                <div class="admin-card-header">
                    <h3><?php echo e($event->name); ?></h3>
                    <span class="status-badge status-active">Принято</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo e(\Carbon\Carbon::parse($event->date_time)->format('d.m.Y H:i')); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo e($event->location); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span><?php echo e($event->responsible_person); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Принято: <?php echo e(\Carbon\Carbon::parse($event->updated_at)->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <form action="<?php echo e(route('admin.events.destroy', $event)); ?>" method="POST" class="delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить это мероприятие?')">
                            <i class="fas fa-trash"></i>
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="admin-empty">
                <i class="fas fa-calendar-alt"></i>
                <p>Нет принятых мероприятий</p>
                <span>Все принятые мероприятия отображаются здесь</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="admin-pagination">
            <?php echo e($events->links()); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/admin/events.blade.php ENDPATH**/ ?>