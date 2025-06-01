<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка принята</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style1.css')); ?>">
</head>
<body>
    <section class="signup">
        <h2>Заявка принята</h2>
        
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <p>Спасибо за вашу заявку на регистрацию в системе профсоюза.</p>
        <p>Ваша заявка находится на рассмотрении. После проверки данных администратором, вы получите письмо с вашими учетными данными для входа в систему.</p>
        
        <div class="form-group">
            <a href="<?php echo e(route('home')); ?>" class="button">Вернуться на главную</a>
        </div>
    </section>
</body>
</html> <?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/auth/registration-success.blade.php ENDPATH**/ ?>