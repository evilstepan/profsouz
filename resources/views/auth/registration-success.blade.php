<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка принята</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
</head>
<body>
    <section class="signup">
        <h2>Заявка принята</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <p>Спасибо за вашу заявку на регистрацию в системе профсоюза.</p>
        <p>Ваша заявка находится на рассмотрении. После проверки данных администратором, вы получите письмо с вашими учетными данными для входа в систему.</p>
        
        <div class="form-group">
            <a href="{{ route('home') }}" class="button">Вернуться на главную</a>
        </div>
    </section>
</body>
</html> 