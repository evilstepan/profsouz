document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;


    clearMessages();

    let users = JSON.parse(localStorage.getItem('users')) || [];
    const user = users.find(user => user.email === email && user.password === password);

    if (user) {
    
        sessionStorage.setItem('currentUser', JSON.stringify(user));
        window.location.href = 'index7.html'; 
    } else {
        showMessage("Неверный email или пароль.", 'error-message', 'login-email');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const initialUser = {
        name: "Ковалев Степан Васильевич",
        email: "aa@mail.ru",
        phone: "+7(906)-561-43-22",
        password: "321321", 
        date: "2005-03-03" 
    };
    
    let users = JSON.parse(localStorage.getItem('users')) || [];
    if (!users.some(existingUser => existingUser.email === initialUser.email)) {
        users.push(initialUser);
        localStorage.setItem('users', JSON.stringify(users));
        console.log("Начальный пользователь добавлен в localStorage.");
    } 
});


function showMessage(message, className, inputId) {
    const inputField = document.getElementById(inputId);
    
  
    clearMessages();


    const messageElement = document.createElement('div');
    messageElement.className = className;
    messageElement.textContent = message;

   
    inputField.parentNode.insertBefore(messageElement, inputField.nextSibling);
}


function clearMessages() {
 
    const messages = document.querySelectorAll('.error-message, .success-message');
    
    messages.forEach(message => message.remove());
}