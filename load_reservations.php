<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT r.id, ps.spot_number, r.start_time FROM reservations r JOIN parking_spots ps ON r.spot_id = ps.id WHERE r.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode(['reservations' => $reservations]);
?>
