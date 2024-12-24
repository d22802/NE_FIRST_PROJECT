document.addEventListener('DOMContentLoaded', function () {
    const spotsContainer = document.getElementById('parking-spots');
    const adminRequestsContainer = document.getElementById('registration-requests');
    const parkingSpotsList = document.getElementById('parking-spots-list');

    // Пример данных для парковочных мест
    const parkingSpots = [
        { id: 1, number: 'A1', taken: false },
        { id: 2, number: 'A2', taken: true },
        { id: 3, number: 'A3', taken: false },
        // добавьте остальные места
    ];

    // Отображение парковочных мест на главной странице
    parkingSpots.forEach(spot => {
        const spotElement = document.createElement('div');
        spotElement.className = `spot ${spot.taken ? 'taken' : 'free'}`;
        spotElement.textContent = spot.taken ? `Место ${spot.number} (Занято)` : `Место ${spot.number} (Свободно)`;

        spotElement.addEventListener('click', () => {
            if (!spot.taken) {
                // Логика резервирования места
                alert(`Вы выбрали место ${spot.number}`);
                reserveSpot(spot.id);
            }
        });

        spotsContainer.appendChild(spotElement);
    });

    // Запросы на регистрацию (Функция заглушка для демонстрации)
    function loadRegistrationRequests() {
        const requests = [
            { id: 1, full_name: 'Иванов Иван', student_id: '12345' },
            { id: 2, full_name: 'Петрова Анна', student_id: '67890' }
        ];

        requests.forEach(request => {
            const requestElement = document.createElement('div');
            requestElement.className = 'request';
            requestElement.textContent = `${request.full_name} (${request.student_id})`;
            adminRequestsContainer.appendChild(requestElement);
        });
    }
    loadRegistrationRequests();

    // Функция резервирования мест (шаблон)
    function reserveSpot(spotId) {
        fetch('reserve_spot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ spotId: spotId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Место успешно зарезервировано!');
                // Обновить статус места
            } else {
                alert('Ошибка резервирования места: ' + data.message);
            }
        });
    }

    // Загрузка истории парковок
    document.getElementById('load-reservations-btn').addEventListener('click', function() {
        fetch('load_reservations.php')
        .then(response => response.json())
        .then(data => {
            const reservationsList = document.getElementById('reservations-list');
            reservationsList.innerHTML = ''; // Очистка списка
            data.reservations.forEach(reservation => {
                const reservationElement = document.createElement('div');
                reservationElement.className = 'reservation';
                reservationElement.textContent = `Место: ${reservation.spot_number}, Время: ${new Date(reservation.start_time).toLocaleString()}`;
                reservationsList.appendChild(reservationElement);
            });
        });
    });

    // Загружаем список пользователей
    document.getElementById('load-users-btn').addEventListener('click', function() {
        fetch('load_users.php')
        .then(response => response.json())
        .then(data => {
            const usersList = document.getElementById('users-list');
            usersList.innerHTML = ''; // Очистка списка
            data.users.forEach(user => {
                const userElement = document.createElement('div');
                userElement.className = 'user';
                userElement.textContent = `${user.full_name} (${user.student_id})`;

                const toggleButton = document.createElement('button');
                toggleButton.textContent = user.is_active ? 'Деактивировать' : 'Активировать';
                toggleButton.addEventListener('click', () => {
                    toggleUser(user.id, !user.is_active);
                });

                userElement.appendChild(toggleButton);
                usersList.appendChild(userElement);
            });
        });
    });

    function toggleUser(userId, isActive) {
        fetch('toggle_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: userId, isActive: isActive })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Статус пользователя обновлен!');
                // Обновить список пользователей
                document.getElementById('load-users-btn').click();
            } else {
                alert('Ошибка: ' + data.message);
            }
        });
    }

    // Добавить новое парковочное место
    document.getElementById('add-spot-btn').addEventListener('click', function() {
        const spotNumber = prompt('Введите номер нового парковочного места:');
        if (spotNumber) {
            fetch('add_spot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ spot_number: spotNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Парковочное место успешно добавлено!');
                    // Обновить список мест
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const parkingSpotsList = document.getElementById('parking-spots-list');
    const usersList = document.getElementById('users-list');

    // Загрузка парковочных мест (если необходимо)
    function loadParkingSpots() {
        fetch('load_parking_spots.php') // Создайте этот файл для загрузки парковочных мест
            .then(response => response.json())
            .then(data => {
                parkingSpotsList.innerHTML = ''; // Очистка списка
                data.spots.forEach(spot => {
                    const spotElement = document.createElement('div');
                    spotElement.className = 'parking-spot';
                    spotElement.textContent = `Парковочное место #${spot.spot_number}`;
                    parkingSpotsList.appendChild(spotElement);
                });
            });
    }

    // Добавление нового парковочного места
    document.getElementById('add-spot-btn').addEventListener('click', function() {
        const spotNumber = prompt('Введите номер нового парковочного места:');
        if (spotNumber) {
            fetch('add_spot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ spot_number: spotNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Парковочное место успешно добавлено!');
                    loadParkingSpots(); // Обновить список мест
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        }
    });

    // Загрузка пользователей
    document.getElementById('load-users-btn').addEventListener('click', function() {
        fetch('load_users.php')
        .then(response => response.json())
        .then(data => {
            usersList.innerHTML = ''; // Очистка списка
            data.users.forEach(user => {
                const userElement = document.createElement('div');
                userElement.className = 'user';
                userElement.textContent = `${user.full_name} (${user.student_id})`;
                usersList.appendChild(userElement);
            });
        });
    });

    // Добавление нового пользователя
    document.getElementById('add-user-btn').addEventListener('click', function() {
        const fullName = prompt('Введите ФИО нового пользователя:');
        const studentId = prompt('Введите номер студенческого билета нового пользователя:');
        const password = prompt('Введите пароль нового пользователя:');
        
        if (fullName && studentId && password) {
            fetch('register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ full_name: fullName, student_id: studentId, password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Пользователь успешно добавлен!');
                    loadUsers(); // Обновить список пользователей
                } else {
                    alert('Ошибка: ' + data.message);
                }
            });
        }
    });
});