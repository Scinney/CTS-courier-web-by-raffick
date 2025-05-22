<?php
// handlers/branch_action.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branchID = filter_input(INPUT_POST, 'BranchID', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($action !== 'delete' || !$branchID) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action or BranchID.']);
        exit;
    }

    try {
        // Check if branch is referenced by parcels
        $stmt = $connection->prepare("
            SELECT COUNT(*) as count 
            FROM Parcels 
            WHERE SenderBranchID = ? OR ReceiverBranchID = ?
        ");
        $stmt->bind_param("ii", $branchID, $branchID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete branch; it is referenced by parcels.']);
            exit;
        }
        $stmt->close();

        // Delete branch
        $stmt = $connection->prepare("DELETE FROM Branches WHERE BranchID = ?");
        $stmt->bind_param("i", $branchID);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Branch deleted successfully.']);
        } else {
            throw new Exception('Failed to delete branch.');
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