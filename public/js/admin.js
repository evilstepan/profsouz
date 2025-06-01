document.addEventListener('DOMContentLoaded', function() {
    // Переключение вкладок
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            
            // Активация пункта меню
            document.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Показ соответствующей вкладки
            document.querySelectorAll('.content-tab').forEach(tab => tab.style.display = 'none');
            document.getElementById(`${tabId}-tab`).style.display = 'block';
        });
    });

    // Обработка изменения статуса
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const eventId = this.getAttribute('data-event-id');
            const status = this.value;

            fetch(`/admin/events/${eventId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Удаление мероприятия
    document.querySelectorAll('.delete-event').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Вы уверены, что хотите удалить это мероприятие?')) {
                const eventId = this.getAttribute('data-event-id');
                
                fetch(`/admin/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    this.closest('tr').remove();
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});