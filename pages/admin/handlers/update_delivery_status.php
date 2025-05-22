<?php
// handlers/update_delivery_status.php
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

    $deliveryID = filter_input(INPUT_POST, 'DeliveryID', FILTER_VALIDATE_INT);
    $deliveryStatusID = filter_input(INPUT_POST, 'DeliveryStatusID', FILTER_VALIDATE_INT);

    if (!$deliveryID || !$deliveryStatusID) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Delivery ID and Status ID are required.']);
        exit;
    }

    // Validate delivery status
    $stmt = $connection->prepare("SELECT DeliveryStatusID FROM DeliveryStatus WHERE DeliveryStatusID = ?");
    $stmt->bind_param("i", $deliveryStatusID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid delivery status ID.']);
        exit;
    }
    $stmt->close();

    // Update delivery status
    $stmt = $connection->prepare("UPDATE Deliveries SET DeliveryStatusID = ?, UpdatedAt = NOW() WHERE DeliveryID = ?");
    $stmt->bind_param("ii", $deliveryStatusID, $deliveryID);
    if ($stmt->execute()) {
        // Sync with Parcels table
        $stmt = $connection->prepare("UPDATE Parcels p JOIN Deliveries d ON p.ParcelID = d.ParcelID 
                               SET p.DeliveryStatusID = ? WHERE d.DeliveryID = ?");
        $stmt->bind_param("ii", $deliveryStatusID, $deliveryID);
        $stmt->execute();
        $stmt->close();

        // Update DeliveredAt if status is Delivered (new ID 3)
        if ($deliveryStatusID == 3) {
            $stmt = $connection->prepare("UPDATE Deliveries SET DeliveredAt = NOW() WHERE DeliveryID = ?");
            $stmt->bind_param("i", $deliveryID);
            $stmt->execute();
            $stmt->close();
        }

        echo json_encode(['status' => 'success', 'message' => 'Delivery status updated successfully.']);
    } else {
        throw new Exception('Failed to update delivery status: ' . $connection->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}

$connection->close();
?>