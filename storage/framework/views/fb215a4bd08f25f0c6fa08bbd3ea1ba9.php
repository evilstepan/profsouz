

<?php $__env->startSection('title', 'Telegram News'); ?>

<?php $__env->startSection('content'); ?>


<div class="slider">
    <div class="slides">
        <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="slide">
                <div class="text">
                    <h1><?php echo e($slide->title); ?></h1>
                    <p><?php echo e($slide->description); ?></p>
                    <a class="button" href="#">Читать</a>
                </div>
                <div class="image">
                    <img src="<?php echo e(asset($slide->image)); ?>" alt="">
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <a class="prev" onclick="plusSlides(-1)">❮</a>
    <a class="next" onclick="plusSlides(1)">❯</a>
</div>


<section class="main-container1">
    <div class="main-container">
        
        <div class="content-section">
            <h2>Мероприятия</h2>
            <div class="event-list">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event-item">
                        <img alt="<?php echo e($event->title); ?> Image" src="<?php echo e(asset($event->image)); ?>"/>
                        <h3><?php echo e($event->title); ?></h3>
                        <p><?php echo e($event->description); ?></p>
                        <?php if($event->link): ?>
                            <a href="<?php echo e($event->link); ?>">Подробнее</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="content-section">
            <h2>РЕГИОНАЛЬНАЯ ОРГАНИЗАЦИЯ ОБЩЕРОССИЙСКОГО ПРОФСОЮЗА ОБРАЗОВАНИЯ В АЛЕКСАНДРОВЕ</h2>
            <div class="statistics">
                <?php $__currentLoopData = $statistics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statistic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="statistic-item">
                        <h3 class="count" data-target="<?php echo e($statistic->target); ?>">0</h3>
                        <p><?php echo e($statistic->description); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>


<div class="profile-container" style="display: none;">
    <h2>Личный кабинет</h2>
    <div class="profile-info">
        <img id="profile-avatar" src="" alt="Аватар пользователя" width="100">
        <h3 id="profile-name"></h3>
        <p id="profile-email"></p>
    </div>
    <button onclick="logout()">Выход</button>
</div>

<script src="<?php echo e(asset('script/script.js')); ?>" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.count');
        const speed = 1000; 

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target; 
                }
            };
            updateCount();
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const burgerMenu = document.querySelector(".burger-menu");
        const menu = document.querySelector(".menu");
        burgerMenu.addEventListener("click", function () {
            menu.classList.toggle("active");
        });
    });

    //  слайдер
    let slideIndex = 0;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        let slides = document.getElementsByClassName("slides")[0];
        let totalSlides = slides.children.length;
        if (n >= totalSlides) { slideIndex = 0 }
        if (n < 0) { slideIndex = totalSlides - 1 }
        slides.style.transform = `translateX(${-slideIndex * 100}%)`;
    }

    function autoSlide() {
        plusSlides(1);
        setTimeout(autoSlide, 5000); 
    }
    autoSlide();

    // формы
    function togglePassword() {
        var passwordInput = document.getElementById('password');
        var toggleIcon = document.querySelector('.toggle-password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // формы 2
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const date = document.getElementById('date').value;

        if (password !== document.getElementById('confirm-password').value) {
            alert("Пароли не совпадают!");
            return;
        }

        const user = { name, email, phone, password, date };
        let users = JSON.parse(localStorage.getItem('users')) || [];
        users.push(user);
        localStorage.setItem('users', JSON.stringify(users));
        alert("Регистрация прошла успешно!");
    });

    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        let users = JSON.parse(localStorage.getItem('users')) || [];

        const user = users.find(user => user.email === email && user.password === password);

        if (user) {
            localStorage.setItem('currentUser', JSON.stringify(user));
            alert("Вход выполнен успешно!");
            window.location.href = '/index7'; // Перенаправление после успешного входа
        } else {
            alert("Неверный email или пароль!");
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/index7.blade.php ENDPATH**/ ?>