<?php
$host = '134.90.167.42:10306';
$db = 'project_Semenov';
$user = 'Semenov'; // замените на свое имя пользователя БД
$pass = 'EBaelC'; // замените на пароль БД

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
