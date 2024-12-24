<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'),
    true);
    $spot_id = $data['spotId'];
    $user_id = $_SESSION['user_id'];

    // Проверка, что место свободно
    $stmt = $conn->prepare("SELECT is_taken FROM parking_spots WHERE id = ?");
    $stmt->bind_param("i", $spot_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $spot = $result->fetch_assoc();

    if ($spot['is_taken']) {
        echo json_encode(['success' => false, 'message' => 'Место занято.']);
        exit();
    }

    // Резервируем место
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, spot_id, start_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $spot_id);
    $stmt->execute();

    // Обновляем статус парковочного места
    $stmt = $conn->prepare("UPDATE parking_spots SET is_taken = TRUE WHERE id = ?");
    $stmt->bind_param("i", $spot_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
?>
