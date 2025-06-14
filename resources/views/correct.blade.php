<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка</title>
    <link rel="stylesheet" href="css/style1.css">
    <script src="script/correct.js" defer></script>
</head>
<body>

    <div class="login-container" id="loginContainer" style="display:none;">
        <form id="adminLoginForm">
            <h2>Введите пароль для доступа к админке</h2>
            <div class="form-group">
                <label for="admin-password">Пароль:</label>
                <input type="password" id="admin-password" required>
                <button type="button" id="togglePassword">👁️</button> 
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>

    <div class="sidebar" id="sidebar" style="display:none;">
        <img src="img/free-icon-boy-4537069.png" alt="Логотип">
        <h2>Админка</h2>
        <a href="correct.html">Управление пользователями</a>
        <a href="admin.html">Заявки пользователей</a> 
        <a id='logoutBtn' href="#">Выход</a> 
    </div>

    <section class="admin-panel" id="adminContent" style="display:none;">
        <h2>Управление пользователями</h2>
        
        <table id="userTable">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Дата рождения</th>
                    <th>Пароль</th> 
                    <th>Действия</th>
                    <th>Изгнать</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    
        <div id="editModal">
            <h3>Редактировать пользователя</h3>
            <form id="editForm">
                <input type="hidden" id="edit-index">
                <div class="form-group">
                    <label for="edit-name">Имя:</label>
                    <input type="text" id="edit-name" required>
                </div>

                <div class="form-group">
                    <label for="edit-email">Электронная почта:</label>
                    <input type="email" id="edit-email" required>
                </div>

                <div class="form-group">
                    <label for="edit-phone">Телефон:</label>
                    <input type="tel" id="edit-phone" required>
                </div>

                <div class="form-group">
                    <label for="edit-date">Дата рождения:</label>
                    <input type="date" id="edit-date" required>
                </div>

             
                <div class="form-group">
                    <label for="edit-password">Пароль:</label>
                    <input type='text' id='edit-password' required disabled>
                </div>

                <button type="submit">Сохранить изменения</button>
                <button type="button" onclick="closeModal()">Закрыть</button>
            </form>
        </div>

      
        <div id="deleteModal">
            <h3>Подтверждение удаления</h3>
            <p>Вы уверены, что хотите удалить этого пользователя?</p>
            <button id='confirmDeleteBtn'>Да, удалить</button>
            <button onclick='closeDeleteModal()'>Отмена</button>
        </div>

    </section>

</body>
</html>