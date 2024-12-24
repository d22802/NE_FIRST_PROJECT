<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $student_id = $_POST['student_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Добавить пользователя в БД
    $stmt = $conn->prepare("INSERT INTO users (full_name, student_id, password, is_active) VALUES (?, ?, ?, FALSE)");
    $stmt->bind_param("sss", $full_name, $student_id, $password);
    $stmt->execute();

    header("Location: login.html");
    exit();
}
?>
