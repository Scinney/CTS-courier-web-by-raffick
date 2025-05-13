<?php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);
    $action = $_POST['action'];

    if (!$userId || !in_array($action, ['suspend', 'delete'])) {
        http_response_code(400);
        echo "Invalid request";
        exit();
    }

    $newStatus = $action === 'suspend' ? 'suspended' : 'deleted';
    $stmt = $conn->prepare("UPDATE users2 SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $userId);

    if ($stmt->execute()) {
        echo "success";
        exit();
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
        exit();
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
    exit();
}
