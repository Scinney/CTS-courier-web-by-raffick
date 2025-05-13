<?php
// handlers/branch_action.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if (!$action || !$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Action and branch ID are required.']);
        exit;
    }

    try {
        if ($action === 'delete') {
            // Check if the branch exists
            $checkStmt = $conn->prepare("SELECT id FROM branches WHERE id = ?");
            if ($checkStmt === false) {
                throw new Exception("Check prepare failed: " . $conn->error);
            }
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Branch not found.");
            }
            $checkStmt->close();

            // Check for active parcels (not archived or deleted)
            $stmt = $conn->prepare("DELETE FROM branches WHERE id = ? AND id NOT IN (SELECT sender_branch_id FROM parcels WHERE status NOT IN ('archived', 'deleted') UNION SELECT receiver_branch_id FROM parcels WHERE status NOT IN ('archived', 'deleted'))");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Branch deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cannot delete branch with active parcels.']);
            }

            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Branch action error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}
?>