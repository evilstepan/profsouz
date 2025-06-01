<!DOCTYPE html>
<html>
<head>
    <title>Заявка отклонена</title>
</head>
<body>
    <h2>Здравствуйте, <?php echo e($user->name); ?>!</h2>
    
    <p>К сожалению, ваша заявка на регистрацию в системе профсоюза отклонена.</p>
    
    <p>Причина отказа:</p>
    <p><?php echo e($reason); ?></p>
    
    <p>Если вы считаете, что это произошло по ошибке, пожалуйста, свяжитесь с администрацией профсоюза.</p>
    
    <p>С уважением,<br>
    Администрация профсоюза</p>
</body>
</html> <?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/emails/registration-rejected.blade.php ENDPATH**/ ?>