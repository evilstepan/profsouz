<!DOCTYPE html>
<html>
<head>
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
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
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
            <h1>Подтверждение участия в мероприятии</h1>
        </div>
        
        <div class="content">
            <p>Здравствуйте, {{ $participantData['name'] }}!</p>
            
            <p>Спасибо за регистрацию на мероприятие "{{ $event->name }}".</p>
            
            <h2>Детали мероприятия:</h2>
            <ul>
                <li><strong>Дата и время:</strong> {{ \Carbon\Carbon::parse($event->date_time)->format('d.m.Y H:i') }}</li>
                <li><strong>Место проведения:</strong> {{ $event->location }}</li>
            </ul>

            <h2>Ваши данные:</h2>
            <ul>
                <li><strong>Имя:</strong> {{ $participantData['name'] }}</li>
                <li><strong>Email:</strong> {{ $participantData['email'] }}</li>
                <li><strong>Телефон:</strong> {{ $participantData['phone'] }}</li>
                @if(!empty($participantData['comment']))
                    <li><strong>Комментарий:</strong> {{ $participantData['comment'] }}</li>
                @endif
            </ul>

            <p>Мы будем рады видеть вас на мероприятии!</p>
        </div>

        <div class="footer">
            <p>Это письмо сформировано автоматически, пожалуйста, не отвечайте на него.</p>
        </div>
    </div>
</body>
</html> 