


<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .admin-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        padding: 20px;
    }

    .admin-card {
        flex: 0 0 calc(25% - 23px);
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        border: 1px solid #e0e0e0;
        margin-bottom: 30px;
    }

    .admin-card:nth-child(4n) {
        margin-right: 0;
    }

    @media (max-width: 1200px) {
        .admin-card {
            flex: 0 0 calc(33.333% - 20px);
        }
    }

    @media (max-width: 900px) {
        .admin-card {
            flex: 0 0 calc(50% - 15px);
        }
    }

    @media (max-width: 600px) {
        .admin-card {
            flex: 0 0 100%;
        }
    }
</style>
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
            <h1>Заявки на мероприятия</h1>
        </div>

        <?php if(session('success')): ?>
            <div class="admin-success-message">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="admin-cards">
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3><?php echo e($order->name); ?></h3>
                    <span class="status-badge status-pending">В ожидании</span>
                </div>
                <div class="admin-card-body">
                    <div class="admin-info-item">
                        <i class="fas fa-user"></i>
                        <span><?php echo e($order->user->name); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo e(\Carbon\Carbon::parse($order->date_time)->format('d.m.Y H:i')); ?></span>
                    </div>
                    <div class="admin-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo e($order->location); ?></span>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <div class="status-controls">
                        <select name="status" class="admin-form-input">
                            <option value="">Выберите действие</option>
                            <option value="accepted">Принять</option>
                            <option value="rejected">Отклонить</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <form action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>" method="POST" class="status-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="status" class="status-input">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i>
                                Обновить
                            </button>
                        </form>
                        <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" class="delete-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                                <i class="fas fa-trash"></i>
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="admin-empty">
                <i class="fas fa-clipboard-list"></i>
                <p>Нет заявок на рассмотрение</p>
                <span>Все заявки обработаны или ожидают новых</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="admin-pagination">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.admin-form-input');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('.admin-card-footer').querySelector('.status-form');
            const input = form.querySelector('.status-input');
            input.value = this.value;
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/admin/orders.blade.php ENDPATH**/ ?>