

// Маска для телефона
document.getElementById('phone').addEventListener('input', function(e) {
    let input = e.target.value.replace(/\D/g, ''); // Удаляем все нецифровые символы
    if (input.length > 11) input = input.substring(0, 11); // Ограничиваем длину до 11 цифр

    let formattedValue = '+7';
    if (input.length > 1) {
        formattedValue += '(' + input.substring(1, 4); // Добавляем первые 3 цифры после +7
        if (input.length >= 5) {
            formattedValue += ')-' + input.substring(4, 7); // Добавляем следующие 3 цифры
            if (input.length >= 8) {
                formattedValue += '-' + input.substring(7, 9); // Добавляем следующие 2 цифры
                if (input.length >= 10) {
                    formattedValue += '-' + input.substring(9, 11); // Добавляем последние 2 цифры
                }
            }
        }
    }

    e.target.value = formattedValue; // Устанавливаем отформатированное значение
});