<?php
// Include database connection
include '../db/connection.php';

// Ensure connection is valid
if (!$connection) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Check if required POST parameters are set
if (!isset($_POST['id']) || !isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing id or action parameter']);
    exit;
}

$user_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
$action = $_POST['action'];

if ($user_id === false || !in_array($action, ['suspend', 'delete', 'unsuspend'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID or action']);
    exit;
}

// Prepare the SQL statement based on the action
$status = match ($action) {
    'suspend' => 'suspended',
    'delete' => 'deleted',
    'unsuspend' => 'active'
};
$stmt = $connection->prepare("UPDATE users SET status = ? WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param('si', $status, $user_id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to execute action: ' . $stmt->error]);
}

$stmt->close();
$connection->close();
?>