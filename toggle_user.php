<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $isActive = $data['isActive'];

    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $isActive, $userId);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
?>
