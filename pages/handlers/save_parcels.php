<?php
// handlers/save_parcels.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $parcel_id = filter_input(INPUT_POST, 'parcel_id', FILTER_SANITIZE_STRING);
    $sender_name = filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_STRING);
    $sender_branch_id = filter_input(INPUT_POST, 'sender_branch_id', FILTER_VALIDATE_INT);
    $sender_contact = filter_input(INPUT_POST, 'sender_contact', FILTER_SANITIZE_STRING);
    $receiver_name = filter_input(INPUT_POST, 'receiver_name', FILTER_SANITIZE_STRING);
    $receiver_branch_id = filter_input(INPUT_POST, 'receiver_branch_id', FILTER_VALIDATE_INT);
    $receiver_contact = filter_input(INPUT_POST, 'receiver_contact', FILTER_SANITIZE_STRING);
    $weight = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT);
    $declared_value = filter_input(INPUT_POST, 'declared_value', FILTER_VALIDATE_FLOAT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING) ?: 'In Transit';

    // Validate required fields
    if (!$parcel_id || !$sender_name || !$sender_branch_id || !$sender_contact || !$receiver_name || !$receiver_branch_id || !$receiver_contact || !$weight || !$declared_value) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate weight and declared value
    if ($weight <= 0 || $declared_value < 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Weight must be positive and declared value cannot be negative.']);
        exit;
    }

    // Validate branch IDs
    $stmt = $conn->prepare("SELECT id FROM branches WHERE id IN (?, ?) AND is_operational = 1");
    $stmt->bind_param("ii", $sender_branch_id, $receiver_branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 2) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or non-operational branch selected.']);
        exit;
    }
    $stmt->close();

    // Validate contact numbers (Malawi format: 09 or 08 followed by 8 digits)
    if (!preg_match('/^0[89][0-9]{8}$/', $sender_contact) || !preg_match('/^0[89][0-9]{8}$/', $receiver_contact)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number format.']);
        exit;
    }

    // Validate status
    $valid_statuses = ['In Transit', 'Out for Delivery', 'Delivered', 'Returned'];
    if ($status && !in_array($status, $valid_statuses)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid status value.']);
        exit;
    }

    try {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE parcels SET parcel_id = ?, sender_name = ?, sender_branch_id = ?, sender_contact = ?, receiver_name = ?, receiver_branch_id = ?, receiver_contact = ?, weight = ?, declared_value = ?, status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("ssissssdssi", $parcel_id, $sender_name, $sender_branch_id, $sender_contact, $receiver_name, $receiver_branch_id, $receiver_contact, $weight, $declared_value, $status, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO parcels (parcel_id, sender_name, sender_branch_id, sender_contact, receiver_name, receiver_branch_id, receiver_contact, weight, declared_value, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssissssdss", $parcel_id, $sender_name, $sender_branch_id, $sender_contact, $receiver_name, $receiver_branch_id, $receiver_contact, $weight, $declared_value, $status);
        }

        if ($stmt->execute()) {
            $new_id = $id > 0 ? $id : $conn->insert_id;
            echo json_encode(['status' => 'success', 'message' => 'Parcel saved successfully.', 'id' => $new_id]);
        } else {
            throw new Exception('Failed to save parcel.');
        }

        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>