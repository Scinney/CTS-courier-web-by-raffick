<!-- delivery.php -->
<div class="card">
    <div class="user-management-header">
        <h2>Delivery Management</h2>
        <button onclick="openAssignDeliveryModal()" class="btn btn-primary">➕ Assign Delivery</button>
    </div>
</div>

<!-- Assign Delivery Modal -->
<div id="assignDeliveryModal" class="modal">
    <div class="modal-content card">
        <span class="close" onclick="closeAssignDeliveryModal()">×</span>
        <div class="modal-header">
            <h3 id="assign-delivery-modal-title">Assign Delivery</h3>
        </div>
        <form id="assign-delivery-form" method="post" action="handlers/assign_delivery.php">
            <input type="hidden" name="DeliveryID" id="delivery-id">
            <div class="form-group">
                <label for="parcel_id">Parcel ID:</label>
                <select name="ParcelID" id="parcel_id" class="form-control" required>
                    <option value="">Select Parcel</option>
                    <?php
                    include './db/connection.php';
                    $parcels = $connection->query("SELECT ParcelID, Sender, Receiver, DeliveryStatusID, ds.StatusName 
                                                  FROM Parcels p 
                                                  JOIN DeliveryStatus ds ON p.DeliveryStatusID = ds.DeliveryStatusID 
                                                  WHERE p.DeliveryStatusID IN (1, 2)"); // Pending or In Transit only
                    while ($parcel = $parcels->fetch_assoc()) {
                        $parcelId = htmlspecialchars($parcel['ParcelID'], ENT_QUOTES);
                        $sender = htmlspecialchars($parcel['Sender'], ENT_QUOTES);
                        $receiver = htmlspecialchars($parcel['Receiver'], ENT_QUOTES);
                        $status = htmlspecialchars($parcel['StatusName'], ENT_QUOTES);
                        echo "<option value='{$parcelId}'>{$parcelId} (To: {$receiver}, From: {$sender}, Status: {$status})</option>";
                    }
                    $connection->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="driver_id">Driver:</label>
                <select name="DriverID" id="driver_id" class="form-control" required>
                    <option value="">Select Driver</option>
                    <?php
                    include './db/connection.php';
                    $drivers = $connection->query("SELECT DriverID, FirstName, LastName, ContactNumber 
                                                  FROM Drivers WHERE IsActive = 1");
                    while ($driver = $drivers->fetch_assoc()) {
                        $driverId = htmlspecialchars($driver['DriverID'], ENT_QUOTES);
                        $name = htmlspecialchars($driver['FirstName'] . ' ' . $driver['LastName'], ENT_QUOTES);
                        $contact = htmlspecialchars($driver['ContactNumber'], ENT_QUOTES);
                        echo "<option value='{$driverId}'>{$name} ({$contact})</option>";
                    }
                    $connection->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="assigned_branch_id">Assigned Branch:</label>
                <select name="AssignedBranchID" id="assigned_branch_id" class="form-control" required>
                    <option value="">Select Branch</option>
                    <?php
                    include './db/connection.php';
                    $branches = $connection->query("SELECT BranchID, Name, City FROM Branches WHERE Operational = 1");
                    while ($branch = $branches->fetch_assoc()) {
                        $branchId = htmlspecialchars($branch['BranchID'], ENT_QUOTES);
                        $name = htmlspecialchars($branch['Name'], ENT_QUOTES);
                        $city = htmlspecialchars($branch['City'], ENT_QUOTES);
                        echo "<option value='{$branchId}'>{$name} ({$city})</option>";
                    }
                    $connection->close();
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Assign Delivery</button>
        </form>
    </div>
</div>

<!-- Delivery Status Update Table -->
<div class="card">
    <h3>Update Delivery Status</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>Delivery ID</th>
                <th>Parcel ID</th>
                <th>Driver</th>
                <th>Assigned Branch</th>
                <th>Current Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="deliveries-body">
            <?php
            include './db/connection.php';
            $deliveries = $connection->query("SELECT d.DeliveryID, d.ParcelID, CONCAT(dr.FirstName, ' ', dr.LastName) AS DriverName, 
                                             b.Name AS BranchName, ds.StatusName AS DeliveryStatus
                                             FROM Deliveries d
                                             JOIN Drivers dr ON d.DriverID = dr.DriverID
                                             JOIN Branches b ON d.AssignedBranchID = b.BranchID
                                             JOIN DeliveryStatus ds ON d.DeliveryStatusID = ds.DeliveryStatusID");
            while ($delivery = $deliveries->fetch_assoc()) {
                $deliveryId = htmlspecialchars($delivery['DeliveryID'], ENT_QUOTES);
                $parcelId = htmlspecialchars($delivery['ParcelID'], ENT_QUOTES);
                $driverName = htmlspecialchars($delivery['DriverName'], ENT_QUOTES);
                $branchName = htmlspecialchars($delivery['BranchName'], ENT_QUOTES);
                $status = htmlspecialchars($delivery['DeliveryStatus'], ENT_QUOTES);

                $deliveryJson = htmlspecialchars(json_encode([
                    'DeliveryID' => $delivery['DeliveryID'],
                    'ParcelID' => $delivery['ParcelID'],
                    'DriverID' => $delivery['DriverID'],
                    'AssignedBranchID' => $delivery['AssignedBranchID'],
                    'DeliveryStatusID' => $delivery['DeliveryStatusID']
                ]), ENT_QUOTES);

                echo "<tr>
                        <td>{$deliveryId}</td>
                        <td>{$parcelId}</td>
                        <td>{$driverName}</td>
                        <td>{$branchName}</td>
                        <td>{$status}</td>
                        <td>
                            <button class='btn btn-warning update-status-btn' data-delivery='{$deliveryJson}'>Update Status</button>
                        </td>
                      </tr>";
            }
            $connection->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Delivery Performance Tracking -->
<div class="card">
    <h3>Delivery Performance</h3>
    <div id="performance-stats">
        <?php
        include './db/connection.php';
        $totalDeliveries = $connection->query("SELECT COUNT(*) as count FROM Deliveries")->fetch_assoc()['count'];
        $completedDeliveries = $connection->query("SELECT COUNT(*) as count FROM Deliveries WHERE DeliveryStatusID = 3")->fetch_assoc()['count']; // Delivered (new ID 3)
        $avgDeliveryTime = $connection->query("SELECT AVG(TIMESTAMPDIFF(HOUR, AssignedAt, DeliveredAt)) as avg_time 
                                              FROM Deliveries WHERE DeliveredAt IS NOT NULL")->fetch_assoc()['avg_time'] ?: 0;

        echo "<p>Total Deliveries: {$totalDeliveries}</p>";
        echo "<p>Completed Deliveries: {$completedDeliveries}</p>";
        echo "<p>Success Rate: " . ($totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100, 2) : 0) . "%</p>";
        echo "<p>Average Delivery Time: " . ($avgDeliveryTime ? round($avgDeliveryTime, 2) . " hours" : "N/A") . "</p>";
        $connection->close();
        ?>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="modal">
    <div class="modal-content card">
        <span class="close" onclick="closeUpdateStatusModal()">×</span>
        <div class="modal-header">
            <h3>Update Delivery Status</h3>
        </div>
        <form id="update-status-form" method="post" action="handlers/update_delivery_status.php">
            <input type="hidden" name="DeliveryID" id="update-delivery-id">
            <div class="form-group">
                <label for="new_status">New Status:</label>
                <select name="DeliveryStatusID" id="new_status" class="form-control" required>
                    <?php
                    include './db/connection.php';
                    $statuses = $connection->query("SELECT DeliveryStatusID, StatusName FROM DeliveryStatus");
                    while ($status = $statuses->fetch_assoc()) {
                        $statusId = htmlspecialchars($status['DeliveryStatusID'], ENT_QUOTES);
                        $statusName = htmlspecialchars($status['StatusName'], ENT_QUOTES);
                        echo "<option value='{$statusId}'>{$statusName}</option>";
                    }
                    $connection->close();
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update Status</button>
        </form>
    </div>
</div>

<script>
    function openAssignDeliveryModal() {
        document.getElementById('assign-delivery-form').reset();
        document.getElementById('assign-delivery-modal-title').innerText = 'Assign Delivery';
        document.getElementById('delivery-id').value = '';
        document.getElementById('assignDeliveryModal').style.display = 'flex';
    }

    function closeAssignDeliveryModal() {
        document.getElementById('assignDeliveryModal').style.display = 'none';
    }

    function openUpdateStatusModal(deliveryData) {
        document.getElementById('update-delivery-id').value = deliveryData.DeliveryID;
        document.getElementById('updateStatusModal').style.display = 'flex';
    }

    function closeUpdateStatusModal() {
        document.getElementById('updateStatusModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
        const assignModal = document.getElementById('assignDeliveryModal');
        const updateModal = document.getElementById('updateStatusModal');
        if (event.target === assignModal) assignModal.style.display = 'none';
        if (event.target === updateModal) updateModal.style.display = 'none';
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Update status button listener
        const updateStatusButtons = document.querySelectorAll('.update-status-btn');
        updateStatusButtons.forEach(button => {
            button.addEventListener('click', function () {
                const deliveryData = JSON.parse(this.dataset.delivery);
                openUpdateStatusModal(deliveryData);
            });
        });

        // Assign delivery form submission
        document.getElementById('assign-delivery-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('handlers/assign_delivery.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    closeAssignDeliveryModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while assigning the delivery: ' + error.message);
            });
        });

        // Update status form submission
        document.getElementById('update-status-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('handlers/update_delivery_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    closeUpdateStatusModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status: ' + error.message);
            });
        });
    });
</script>

<?php
echo '<style>';
include 'users.php';
echo '</style>';
?>