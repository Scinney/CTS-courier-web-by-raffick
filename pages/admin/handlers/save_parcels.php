<?php
// handlers/save_parcels.php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parcelID = filter_input(INPUT_POST, 'ParcelID', FILTER_SANITIZE_STRING);
    $sender = filter_input(INPUT_POST, 'Sender', FILTER_SANITIZE_STRING);
    $senderBranchID = filter_input(INPUT_POST, 'SenderBranchID', FILTER_VALIDATE_INT);
    $senderContact = filter_input(INPUT_POST, 'SenderContact', FILTER_SANITIZE_STRING);
    $receiver = filter_input(INPUT_POST, 'Receiver', FILTER_SANITIZE_STRING);
    $receiverBranchID = filter_input(INPUT_POST, 'ReceiverBranchID', FILTER_VALIDATE_INT);
    $receiverContact = filter_input(INPUT_POST, 'ReceiverContact', FILTER_SANITIZE_STRING);
    $weightKg = filter_input(INPUT_POST, 'WeightKg', FILTER_VALIDATE_FLOAT);
    $declaredValueMWK = filter_input(INPUT_POST, 'DeclaredValueMWK', FILTER_VALIDATE_FLOAT);
    $paymentStatusID = filter_input(INPUT_POST, 'PaymentStatusID', FILTER_VALIDATE_INT);
    $deliveryStatusID = filter_input(INPUT_POST, 'DeliveryStatusID', FILTER_VALIDATE_INT);

    // Validate required fields
    if (!$sender || !$senderBranchID || !$senderContact || !$receiver || !$receiverBranchID || !$receiverContact || !$weightKg || !$declaredValueMWK || !$paymentStatusID || !$deliveryStatusID) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Validate weight and declared value
    if ($weightKg <= 0 || $declaredValueMWK < 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Weight must be positive and declared value cannot be negative.']);
        exit;
    }

    // Validate branch IDs
    $stmt = $connection->prepare("SELECT BranchID FROM Branches WHERE BranchID IN (?, ?) AND Operational = 1");
    $stmt->bind_param("ii", $senderBranchID, $receiverBranchID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 2) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or non-operational branch selected.']);
        exit;
    }
    $stmt->close();

    // Validate contact numbers (Malawi format: 09 or 08 followed by 8 digits)
    if (!preg_match('/^0[89][0-9]{8}$/', $senderContact) || !preg_match('/^0[89][0-9]{8}$/', $receiverContact)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number format.']);
        exit;
    }

    // Validate payment and delivery status IDs
    $stmt = $connection->prepare("SELECT PaymentStatusID FROM Payment WHERE PaymentStatusID = ?");
    $stmt->bind_param("i", $paymentStatusID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid payment status.']);
        exit;
    }
    $stmt->close();

    $stmt = $connection->prepare("SELECT DeliveryStatusID FROM DeliveryStatus WHERE DeliveryStatusID = ?");
    $stmt->bind_param("i", $deliveryStatusID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid delivery status.']);
        exit;
    }
    $stmt->close();

    // Generate ParcelID for new parcels
    if (empty($parcelID)) {
        $timestamp = date('YmdHis');
        $random = sprintf("%04d", rand(0, 9999));
        $parcelID = "PARC-$timestamp-$random";
    }

    try {
        // Use INSERT ... ON DUPLICATE KEY UPDATE to handle both insert and update
        $stmt = $connection->prepare("
            INSERT INTO Parcels (ParcelID, Sender, SenderBranchID, SenderContact, Receiver, ReceiverBranchID, ReceiverContact, WeightKg, DeclaredValueMWK, PaymentStatusID, DeliveryStatusID, CreatedAt, UpdatedAt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                Sender = ?, SenderBranchID = ?, SenderContact = ?, Receiver = ?, ReceiverBranchID = ?, ReceiverContact = ?, WeightKg = ?, DeclaredValueMWK = ?, PaymentStatusID = ?, DeliveryStatusID = ?, UpdatedAt = NOW()
        ");
        $stmt->bind_param(
            "ssisssisdiissssisdii",
            $parcelID, $sender, $senderBranchID, $senderContact, $receiver, $receiverBranchID, $receiverContact, $weightKg, $declaredValueMWK, $paymentStatusID, $deliveryStatusID,
            $sender, $senderBranchID, $senderContact, $receiver, $receiverBranchID, $receiverContact, $weightKg, $declaredValueMWK, $paymentStatusID, $deliveryStatusID
        );

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Parcel saved successfully.', 'ParcelID' => $parcelID]);
        } else {
            throw new Exception('Failed to save parcel.');
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