<?php
// handlers/assign_delivery.php
include '../db/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

try {
    if (!isset($connection) || $connection === null) {
        throw new Exception('Database connection failed.');
    }

    $parcelID = filter_input(INPUT_POST, 'ParcelID', FILTER_SANITIZE_STRING);
    $driverID = filter_input(INPUT_POST, 'DriverID', FILTER_VALIDATE_INT);
    $assignedBranchID = filter_input(INPUT_POST, 'AssignedBranchID', FILTER_VALIDATE_INT);

    if (!$parcelID || !$driverID || !$assignedBranchID) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parcel ID, Driver ID, and Assigned Branch ID are required.']);
        exit;
    }

    // Check if parcel exists and is assignable (e.g., Pending or In Transit)
    $stmt = $connection->prepare("SELECT DeliveryStatusID FROM Parcels WHERE ParcelID = ? AND DeliveryStatusID IN (1, 2)");
    $stmt->bind_param("s", $parcelID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or unassignable parcel.']);
        exit;
    }
    $stmt->close();

    // Check if driver and branch exist
    $stmt = $connection->prepare("SELECT DriverID FROM Drivers WHERE DriverID = ? AND IsActive = 1");
    $stmt->bind_param("i", $driverID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or inactive driver.']);
        exit;
    }
    $stmt->close();

    $stmt = $connection->prepare("SELECT BranchID FROM Branches WHERE BranchID = ? AND Operational = 1");
    $stmt->bind_param("i", $assignedBranchID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or non-operational branch.']);
        exit;
    }
    $stmt->close();

    // Insert delivery record
    $stmt = $connection->prepare("INSERT INTO Deliveries (ParcelID, DriverID, AssignedBranchID, DeliveryStatusID) VALUES (?, ?, ?, 2)"); // Default to In Transit
    $stmt->bind_param("sii", $parcelID, $driverID, $assignedBranchID);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Delivery assigned successfully.']);
    } else {
        throw new Exception('Failed to assign delivery: ' . $connection->error);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$connection->close();
?>