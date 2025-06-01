<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Подтверждение участия в мероприятии</title>
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
            background-color: #6a1b9a;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .event-details {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
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
            <h1>Вы участвуете в мероприятии!</h1>
        </div>
        <div class="content">
            <p>Здравствуйте, {{ $participation->name }}!</p>
            
            <p>Вы успешно зарегистрировались на мероприятие:</p>
            
            <div class="event-details">
                <h2>{{ $event->name }}</h2>
                <p><strong>📅 Дата и время:</strong> {{ \Carbon\Carbon::parse($event->date_time)->format('d.m.Y H:i') }}</p>
                <p><strong>📍 Место проведения:</strong> {{ $event->location }}</p>
                @if($event->description)
                    <p><strong>📝 Описание:</strong><br>{{ $event->description }}</p>
                @endif
            </div>

            <p><strong>Важная информация:</strong></p>
            <ul>
                <li>Пожалуйста, приходите за 15 минут до начала мероприятия</li>
                <li>При себе иметь документ, удостоверяющий личность</li>
                <li>При необходимости возьмите с собой ручку и блокнот</li>
            </ul>

            <p>Ждем вас на мероприятии!</p>
        </div>
        <div class="footer">
            <p>Это письмо отправлено автоматически, пожалуйста, не отвечайте на него.</p>
        </div>
    </div>
</body>
</html> 