<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Название сайта'); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <?php echo $__env->yieldContent('styles'); ?> 
</head>
<body>
    <!-- ШАПКА -->
    <div class="navbar">

        <p class="logo-text">
        <a href="<?php echo e(url('index')); ?>">
            <img alt="Organization Logo" height="50" src="<?php echo e(asset('img/logo.png')); ?>" width="150"/>
            Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове
        </a>
        </p>
        <div class="nav-barimg">
            <a href="<?php echo e(url('about')); ?>">О нас</a>
            <a href="<?php echo e(url('meropriat')); ?>">Календарь событий</a>
            <a href="<?php echo e(url('lich')); ?>">Заказать мероприятие</a>
            <?php if(Auth::check() && Auth::user()->role === 'admin'): ?> 
                <a href="<?php echo e(url('admin')); ?>">Панель администратора</a>
            <?php endif; ?>

            <?php if(auth()->guard()->guest()): ?> 
                <a href="<?php echo e(route('login')); ?>">Вход</a>
                <a href="<?php echo e(route('register')); ?>">Регистрация</a>
            <?php endif; ?>

            
            <?php if(auth()->guard()->check()): ?> 
            <div class="profile-container" onclick="toggleDropdown()" style="position: relative; display: inline-block;">
                <a href="<?php echo e(route('lich')); ?>" style="display: flex; align-items: center;">
                    <img id="profile-pic" src="<?php echo e(asset('img/free-icon-boy-4537069.png')); ?>" alt="Фотография профиля" style="cursor:pointer; width: 40px; height: 40px; border-radius: 50%;"/>
                    <p style="margin-left: 10px;"><?php echo e(Auth::user()->name ? explode(' ', Auth::user()->name)[0] : ''); ?></p>
                </a>
                <ul id="dropdown" class="dropdown-content" style="display: none; position: absolute; background-color: white; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1; right: 0; min-width: 160px; padding: 12px 16px; list-style: none;">
                    <li style="padding: 8px 0;"><a href="<?php echo e(url('lich')); ?>" style="text-decoration: none; color: black;">Профиль</a></li>
                    <li style="padding: 8px 0;"><a href="<?php echo e(url('lich')); ?>" style="text-decoration: none; color: black;">Мои заявки</a></li>
                    <li style="padding: 8px 0;">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" style="margin: 0;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" style="background: none; border: none; padding: 0; color: black; cursor: pointer; text-decoration: none; font-size: 1em;">Выход</button>
                        </form>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php echo $__env->yieldContent('content'); ?>

    <!-- ФУТЕР -->
    <div class="footer">
        <div class="footer-left">
            <img alt="Organization Logo" height="50" src="<?php echo e(asset('img/logo.png')); ?>" width="50"/>
            <p>Общероссийская организация профсоюза работников образования и науки Российской Федерации в Александрове</p>
        </div>
        <div class="footer-right">
            <div class="resources">
                <p>Наши контакты:</p>
                <div class="contact-info">
                    <p><svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.11-.21c1.21.49 2.53.76 3.88.76.55 0 1 .45 1 1v3.5c0 .55-.45 1-1 1C10.29 22 2 13.71 2 3.5 2 2.95 2.45 2.5 3 2.5H6.5c.55 0 1 .45 1 1 0 1.35.27 2.67.76 3.88.14.34.07.73-.21 1.11l-2.2 2.2z"/></svg> +7 (906) 561-43-22</p>
                    <p><svg height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg> Alex@eseur.ru</p>
                </div>
            </div>
        </div>
    </div>

    
    <script src="<?php echo e(asset('js/script.js')); ?>" defer></script>

    
    <?php echo $__env->yieldContent('scripts'); ?>

    
    <script>
    function toggleDropdown() {
        document.getElementById("dropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('#profile-pic')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    </script>

</body>
</html><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/layouts/main.blade.php ENDPATH**/ ?>