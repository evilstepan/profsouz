

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
            <a href="<?php echo e(route('admin.users')); ?>">
                <i class="fas fa-users"></i>
                Пользователи
            </a>
            <a href="<?php echo e(route('admin.registrations.index')); ?>">
                <i class="fas fa-user-plus"></i>
                Регистрации
            </a>
            <a href="<?php echo e(route('admin.orders')); ?>">
                <i class="fas fa-clipboard-list"></i>
                Заявки
            </a>
            <a href="<?php echo e(route('admin.events')); ?>">
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
            <h1>Главная</h1>
        </div>

        
        <div class="admin-cards">
            <a href="<?php echo e(route('admin.users')); ?>" class="admin-card admin-card-link">
                <div class="admin-card-body">
                    <i class="fas fa-users admin-card-icon"></i>
                    <h3>Пользователи</h3>
                </div>
            </a>

            <a href="<?php echo e(route('admin.orders')); ?>" class="admin-card admin-card-link">
                <div class="admin-card-body">
                    <i class="fas fa-clipboard-list admin-card-icon"></i>
                    <h3>Заявки</h3>
                </div>
            </a>

            <a href="<?php echo e(route('admin.events')); ?>" class="admin-card admin-card-link">
                 <div class="admin-card-body">
                    <i class="fas fa-calendar-alt admin-card-icon"></i>
                    <h3>Мероприятия</h3>
                 </div>
            </a>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/admin/index.blade.php ENDPATH**/ ?>