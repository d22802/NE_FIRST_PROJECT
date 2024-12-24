<?php
session_start();
include 'db.php';

$stmt = $conn->prepare("SELECT * FROM parking_spots");
$stmt->execute();
$result = $stmt->get_result();
$spots = [];
while ($row = $result->fetch_assoc()) {
    $spots[] = $row;
}
echo json_encode(['spots' => $spots]);
?>