<?php
// handlers/save_branch.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branchID = filter_input(INPUT_POST, 'BranchID', FILTER_VALIDATE_INT) ?: 0;
    $name = filter_input(INPUT_POST, 'Name', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'City', FILTER_SANITIZE_STRING);
    $citySide = filter_input(INPUT_POST, 'CitySide', FILTER_SANITIZE_STRING);
    $contactNumber = filter_input(INPUT_POST, 'ContactNumber', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'Address', FILTER_SANITIZE_STRING);
    $operational = isset($_POST['Operational']) ? 1 : 0;

    // Validate required fields
    if (!$name || !$city || !$contactNumber || !$address) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Name, City, Contact Number, and Address are required.']);
        exit;
    }

    // Validate contact number (Malawi format: 09 or 08 followed by 8 digits)
    if (!preg_match('/^0[89][0-9]{8}$/', $contactNumber)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number format.']);
        exit;
    }

    // Validate city (optional: restrict to predefined list)
    $validCities = ['Lilongwe', 'Blantyre', 'Mzuzu'];
    if (!in_array($city, $validCities)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid city. Must be Lilongwe, Blantyre, or Mzuzu.']);
        exit;
    }

    try {
        if ($branchID > 0) {
            // Update existing branch
            $stmt = $connection->prepare("
                UPDATE Branches SET Name = ?, City = ?, CitySide = ?, ContactNumber = ?, Address = ?, Operational = ?, UpdatedAt = NOW()
                WHERE BranchID = ?
            ");
            $stmt->bind_param("sssssii", $name, $city, $citySide, $contactNumber, $address, $operational, $branchID);
        } else {
            // Insert new branch
            $stmt = $connection->prepare("
                INSERT INTO Branches (Name, City, CitySide, ContactNumber, Address, Operational, CreatedAt, UpdatedAt)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->bind_param("sssssi", $name, $city, $citySide, $contactNumber, $address, $operational);
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Branch saved successfully.', 'BranchID' => $branchID ?: $connection->insert_id]);
        } else {
            throw new Exception('Failed to save branch.');
        }

        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }

    $connection->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>