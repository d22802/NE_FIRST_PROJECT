<?php
include 'db.php';

$stmt = $conn->prepare("SELECT id, full_name, student_id, is_active FROM users");
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(['users' => $users]);
?>
