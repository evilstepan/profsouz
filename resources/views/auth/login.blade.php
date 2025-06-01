<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
</head>
<body>
    <form id="loginForm" class="signup" method="POST" action="{{ route('login') }}">
        @csrf
        <h2>Вход в личный кабинет</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="login-email">Электронная почта:</label>
            <input type="email" id="login-email" name="email" required 
                   value="{{ old('email') }}" 
                   class="@error('email') is-invalid @enderror">
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="login-password">Пароль:</label>
            <input type="password" id="login-password" name="password" required 
                   class="@error('password') is-invalid @enderror">
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">ВОЙТИ В ЛИЧНЫЙ КАБИНЕТ</button>

        <a href="{{ route('register') }}" id="register-link">Зарегистрироваться</a>

        <div class="icon-container">
            <div class="profile-icon" id="profile-icon"></div>
        </div>
    </form>
    <div id="messageContainer"></div>
</body>
</html>