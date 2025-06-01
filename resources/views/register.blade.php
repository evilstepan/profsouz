<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
    <script src="{{ asset('js/register.js') }}" defer></script>
</head>
<body>
    <section class="signup">
        <h2>Стань участником Организации</h2>
        <p>Посетители, зарегистрированные на сайте, получают рассылки, а также узнают, на какие мероприятия записаны.</p>
        <form id="registrationForm" method="POST" action="{{ route('register.submit') }}">
            @csrf
            
            <div class="form-section">
                <h3>Личные данные</h3>
                
                <div class="form-group">
                    <label for="last_name">Фамилия *</label>
                    <input type="text" id="last_name" name="last_name" required value="{{ old('last_name') }}">
                    @error('last_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="first_name">Имя *</label>
                    <input type="text" id="first_name" name="first_name" required value="{{ old('first_name') }}">
                    @error('first_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="middle_name">Отчество</label>
                    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                    @error('middle_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_of_birth">Дата рождения *</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" required max="{{ date('Y-m-d', strtotime('-18 years')) }}" value="{{ old('date_of_birth') }}">
                    @error('date_of_birth')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Электронная почта *</label>
                    <input type="email" id="email" name="email" required placeholder="example@mail.ru" value="{{ old('email') }}">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="+7(906)-561-43-22" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3>Трудовая информация</h3>
                
                <div class="form-group">
                    <label for="position">Должность/Специальность *</label>
                    <input type="text" id="position" name="position" required value="{{ old('position') }}">
                    @error('position')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3>Паспортные данные</h3>
                
                <div class="form-group">
                    <label for="passport_series">Серия паспорта *</label>
                    <input type="text" id="passport_series" name="passport_series" required maxlength="4" value="{{ old('passport_series') }}">
                    @error('passport_series')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="passport_number">Номер паспорта *</label>
                    <input type="text" id="passport_number" name="passport_number" required maxlength="6" value="{{ old('passport_number') }}">
                    @error('passport_number')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="passport_issue_date">Дата выдачи *</label>
                    <input type="date" id="passport_issue_date" name="passport_issue_date" required value="{{ old('passport_issue_date') }}">
                    @error('passport_issue_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="passport_issued_by">Кем выдан *</label>
                    <input type="text" id="passport_issued_by" name="passport_issued_by" required value="{{ old('passport_issued_by') }}">
                    @error('passport_issued_by')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="personal_data_agreement" name="personal_data_agreement" required value="1" {{ old('personal_data_agreement') ? 'checked' : '' }}>
                    <label for="personal_data_agreement">
                        Я даю согласие на обработку персональных данных *
                    </label>
                    @error('personal_data_agreement')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit">Отправить заявку</button>
        </form>
    </section>
</body>
</html>
