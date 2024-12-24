<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    // Проверка данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? AND is_active = TRUE");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: main.html"); // Замените на вашу главную страницу
            exit();
        }
    }

    echo "Неверный студенческий билет или пароль!";
}
?>
