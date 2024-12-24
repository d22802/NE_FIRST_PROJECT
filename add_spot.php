<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $spot_number = $data['spot_number'];

    // Проверка на уникальность номера парковочного места
    $stmt = $conn->prepare("SELECT * FROM parking_spots WHERE spot_number = ?");
    $stmt->bind_param("s", $spot_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Это парковочное место уже существует.']);
        exit();
    }

    // Добавляем новое место в базу данных
    $stmt = $conn->prepare("INSERT INTO parking_spots (spot_number) VALUES (?)");
    $stmt->bind_param("s", $spot_number);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
?>
