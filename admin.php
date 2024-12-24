<?php
session_start();
include 'db.php';

// Проверка, является ли пользователь администратором
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user['is_admin']) {
    header("Location: login.html"); // если не администратор, перенаправляем на страницу входа
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Админ-панель</h1>
        <nav>
            <a href="logout.php">Выход</a>
        </nav>
    </header>
    <main>
        <h2>Парковочные места</h2>
        <div id="parking-spot-management" class="management">
            <button id="add-spot-btn">Добавить новое парковочное место</button>
            <div id="parking-spots-list">
                <!-- Здесь будет список парковочных мест -->
                <?php
                // Запрос на получение всех парковочных мест
                $stmt = $conn->prepare("SELECT * FROM parking_spots");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($spot = $result->fetch_assoc()) {
                    echo "<div class='parking-spot'>Парковочное место #{$spot['spot_number']}</div>";
                }
                ?>
            </div>
        </div>

        <h2>Управление пользователями</h2>
        <div id="user-management" class="management">
            <button id="load-users-btn">Загрузить пользователей</button>
            <div id="users-list">
                <!-- Здесь будет список пользователей -->
            </div>
            <button id="add-user-btn">Добавить нового пользователя</button>
        </div>
    </main>
    <script src="../js/script.js"></script>
</body>
</html>