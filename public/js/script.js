// Счетчики
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.count');
    const speed = 1000; 

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;
            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target; 
            }
        };

        updateCount();
    });
});

// Бургер меню
document.addEventListener("DOMContentLoaded", function () {
    const burgerMenu = document.querySelector(".burger-menu");
    const menu = document.querySelector(".menu");

    if (burgerMenu && menu) {
        burgerMenu.addEventListener("click", function () {
            menu.classList.toggle("active");
        });
    }
});

// Слайдер
let slideIndex = 0;

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    const slidesContainer = document.getElementsByClassName("slides")[0];
    if (!slidesContainer) return;

    const totalSlides = slidesContainer.children.length;
    if (n >= totalSlides) { slideIndex = 0 }
    if (n < 0) { slideIndex = totalSlides - 1 }
    slidesContainer.style.transform = `translateX(${-slideIndex * 100}%)`;
}

function autoSlide() {
    plusSlides(1);
    setTimeout(autoSlide, 5000); 
}

// Запускаем слайдер только если он есть на странице
document.addEventListener('DOMContentLoaded', function() {
    const slidesContainer = document.getElementsByClassName("slides")[0];
    if (slidesContainer) {
        showSlides(slideIndex);
        autoSlide();
    }
});

// Формы
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    if (!passwordInput || !toggleIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Выпадающее меню
function toggleDropdown() {
    const dropdown = document.getElementById("dropdown");
    if (dropdown) {
        dropdown.classList.toggle("show");
    }
}

window.onclick = function(event) {
    if (!event.target.matches('#profile-pic')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Формы регистрации и входа
document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    const loginForm = document.getElementById('loginForm');
    const userLink = document.getElementById('userLink');
    const avatar = document.getElementById('avatar');
    const profileIcon = document.getElementById('profile-icon');

    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const name = document.getElementById('name')?.value;
            const email = document.getElementById('email')?.value;
            const phone = document.getElementById('phone')?.value;
            const password = document.getElementById('password')?.value;
            const date = document.getElementById('date')?.value;

            if (!name || !email || !phone || !password || !date) {
                alert("Пожалуйста, заполните все поля!");
                return;
            }

            if (password !== document.getElementById('confirm-password')?.value) {
                alert("Пароли не совпадают!");
                return;
            }

            const user = { name, email, phone, password, date };
            let users = JSON.parse(localStorage.getItem('users')) || [];
            users.push(user);
            localStorage.setItem('users', JSON.stringify(users));

            alert("Регистрация прошла успешно!");
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const email = document.getElementById('login-email')?.value;
            const password = document.getElementById('login-password')?.value;

            if (!email || !password) {
                alert("Пожалуйста, заполните все поля!");
                return;
            }

            let users = JSON.parse(localStorage.getItem('users')) || [];
            const user = users.find(user => user.email === email && user.password === password);

            if (user) {
                alert("Вход успешен! Добро пожаловать, " + user.name);
                
                if (userLink) userLink.style.display = 'none';
                if (avatar) {
                    avatar.src = 'free-icon-boy-4537069.png';
                    avatar.style.display = 'block';
                }
                if (profileIcon) profileIcon.style.display = 'block';
                
                window.location.href = 'index.html';
            } else {
                alert("Неверный email или пароль.");
            }
        });
    }
});

const userLink = document.getElementById('userLink');
const avatar = document.getElementById('avatar');
const profileIcon = document.getElementById('profile-icon');

const isLoggedIn = false; 

if (isLoggedIn) {
    userLink.style.display = 'none'; 
    avatar.src = 'path_to_user_avatar.jpg'; 
    avatar.style.display = 'block'; 
    profileIcon.style.display = 'block'; 
}







       
        


        
   

     

 