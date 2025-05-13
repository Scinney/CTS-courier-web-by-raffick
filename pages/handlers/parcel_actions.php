<?php
// handlers/parcel_action.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (!$action || !$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Action and parcel ID are required.']);
        exit;
    }

    try {
        if ($action === 'archive') {
            // Check if the parcel exists and is not already archived
            $checkStmt = $conn->prepare("SELECT status FROM parcels WHERE id = ?");
            if ($checkStmt === false) {
                throw new Exception("Check prepare failed: " . $conn->error);
            }
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Parcel not found.");
            }
            $row = $result->fetch_assoc();
            if ($row['status'] === 'archived') {
                throw new Exception("Parcel is already archived.");
            }
            $checkStmt->close();

            // Update the parcel status to 'archived'
            $stmt = $conn->prepare("UPDATE parcels SET status = 'archived', updated_at = NOW() WHERE id = ?");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Parcel archived successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No changes made to the parcel.']);
            }

            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Parcel action error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>