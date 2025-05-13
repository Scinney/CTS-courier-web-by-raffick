<?php
// handlers/save_branch.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $is_operational = isset($_POST['is_operational']) ? 1 : 0;

    // Validate required fields
    if (!$name || !$city || !$contact_number || !$address) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate city
    $valid_cities = ['Lilongwe', 'Blantyre', 'Mzuzu'];
    if (!in_array($city, $valid_cities)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid city selected.']);
        exit;
    }

    // Validate contact number
    if (!preg_match('/^0[89][0-9]{8}$/', $contact_number)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number format.']);
        exit;
    }

    try {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE branches SET name = ?, city = ?, contact_number = ?, address = ?, is_operational = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $city, $contact_number, $address, $is_operational, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO branches (name, city, contact_number, address, is_operational, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("ssssi", $name, $city, $contact_number, $address, $is_operational);
        }

        if ($stmt->execute()) {
            $new_id = $id > 0 ? $id : $conn->insert_id;
            echo json_encode(['status' => 'success', 'message' => 'Branch saved successfully.', 'id' => $new_id]);
        } else {
            throw new Exception('Failed to save branch.');
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