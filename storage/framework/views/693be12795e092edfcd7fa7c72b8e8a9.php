<!DOCTYPE html>
<html>
<head>
    <title>Ваша заявка одобрена</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .credentials {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ваша заявка одобрена!</h1>
        </div>
        
        <div class="content">
            <p>Здравствуйте, <?php echo e($user->name); ?>!</p>
            
            <p>Ваша заявка на регистрацию в системе профсоюза была одобрена администратором.</p>
            
            <div class="credentials">
                <h3>Ваши учетные данные для входа:</h3>
                <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                <p><strong>Пароль:</strong> <?php echo e($password); ?></p>
                <p><strong>Номер членства:</strong> <?php echo e($user->membership_number); ?></p>
            </div>
            
            <p>Для входа в систему используйте указанные выше email и пароль.</p>
            
            <p><strong>Важно:</strong> После первого входа в систему рекомендуется сменить пароль в личном кабинете.</p>
        </div>
        
        <div class="footer">
            <p>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.</p>
        </div>
    </div>
</body>
</html> <?php /**PATH D:\xamp\профиль+роли\Ковалев степан меропритие начало\end (2)\end\resources\views/emails/registration-approved.blade.php ENDPATH**/ ?>