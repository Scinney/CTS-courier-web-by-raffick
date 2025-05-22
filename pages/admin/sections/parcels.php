<!-- parcels.php -->
<div class="card">
    <div class="user-management-header">
        <h2>Parcel Management</h2>
        <button onclick="toggleAddParcelTable()" class="btn btn-primary">➕ Add Parcel</button>
    </div>
    <div class="form-group" style="margin-top: 15px;">
        <label for="search-parcels">Search Parcels:</label>
        <input type="text" id="search-parcels" class="form-control" placeholder="Search by Parcel ID, Sender, Receiver, or Status">
    </div>
</div>

<!-- Add Parcel Table -->
<div class="card" id="add-parcel-table" style="display: none;">
    <h3>Add New Parcel</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>Parcel ID</th>
                <th>Sender</th>
                <th>Sender Branch</th>
                <th>Sender Contact</th>
                <th>Receiver</th>
                <th>Receiver Branch</th>
                <th>Receiver Contact</th>
                <th>Weight (kg)</th>
                <th>Declared Value (MWK)</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <form id="parcel-form" method="post" action="handlers/save_parcels.php">
                <tr>
                    <td><span id="display-parcel-id">Auto-generated</span><input type="hidden" name="ParcelID" id="ParcelID"></td>
                    <td><input type="text" name="Sender" id="Sender" class="form-control" required></td>
                    <td>
                        <select name="SenderBranchID" id="SenderBranchID" class="form-control" required>
                            <option value="">Select Sender Branch</option>
                            <?php
                            include './db/connection.php';
                            $branches = $connection->query("SELECT * FROM Branches WHERE Operational = 1");
                            while ($branch = $branches->fetch_assoc()) {
                                $branchId = htmlspecialchars($branch['BranchID'], ENT_QUOTES);
                                $branchName = htmlspecialchars($branch['Name'], ENT_QUOTES);
                                $city = htmlspecialchars($branch['City'], ENT_QUOTES);
                                echo "<option value='{$branchId}'>{$branchName} ({$city})</option>";
                            }
                            $connection->close();
                            ?>
                        </select>
                    </td>
                    <td><input type="text" name="SenderContact" id="SenderContact" class="form-control" required placeholder="e.g., 0999123456"></td>
                    <td><input type="text" name="Receiver" id="Receiver" class="form-control" required></td>
                    <td>
                        <select name="ReceiverBranchID" id="ReceiverBranchID" class="form-control" required>
                            <option value="">Select Receiver Branch</option>
                            <?php
                            include './db/connection.php';
                            $branches = $connection->query("SELECT * FROM Branches WHERE Operational = 1");
                            while ($branch = $branches->fetch_assoc()) {
                                $branchId = htmlspecialchars($branch['BranchID'], ENT_QUOTES);
                                $branchName = htmlspecialchars($branch['Name'], ENT_QUOTES);
                                $city = htmlspecialchars($branch['City'], ENT_QUOTES);
                                echo "<option value='{$branchId}'>{$branchName} ({$city})</option>";
                            }
                            $connection->close();
                            ?>
                        </select>
                    </td>
                    <td><input type="text" name="ReceiverContact" id="ReceiverContact" class="form-control" required placeholder="e.g., 0888765432"></td>
                    <td><input type="number" step="0.01" name="WeightKg" id="WeightKg" class="form-control" required></td>
                    <td><input type="number" step="0.01" name="DeclaredValueMWK" id="DeclaredValueMWK" class="form-control" required></td>
                    <td>
                        <select name="PaymentStatusID" id="PaymentStatusID" class="form-control" required>
                            <option value="">Select Payment Status</option>
                            <?php
                            include './db/connection.php';
                            $paymentStatuses = $connection->query("SELECT * FROM Payment");
                            while ($status = $paymentStatuses->fetch_assoc()) {
                                $statusId = htmlspecialchars($status['PaymentStatusID'], ENT_QUOTES);
                                $statusName = htmlspecialchars($status['StatusName'], ENT_QUOTES);
                                echo "<option value='{$statusId}'>{$statusName}</option>";
                            }
                            $connection->close();
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="DeliveryStatusID" id="DeliveryStatusID" class="form-control" required>
                            <option value="">Select Delivery Status</option>
                            <?php
                            include './db/connection.php';
                            $deliveryStatuses = $connection->query("SELECT * FROM DeliveryStatus");
                            while ($status = $deliveryStatuses->fetch_assoc()) {
                                $statusId = htmlspecialchars($status['DeliveryStatusID'], ENT_QUOTES);
                                $statusName = htmlspecialchars($status['StatusName'], ENT_QUOTES);
                                echo "<option value='{$statusId}'>{$statusName}</option>";
                            }
                            $connection->close();
                            ?>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success">Save Parcel</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleAddParcelTable()">Cancel</button>
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
</div>

<!-- Active Parcels Table -->
<div class="card">
    <h3>Active Parcels</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>Parcel ID</th>
                <th>Sender</th>
                <th>Sender Branch</th>
                <th>Sender Contact</th>
                <th>Receiver</th>
                <th>Receiver Branch</th>
                <th>Receiver Contact</th>
                <th>Weight (kg)</th>
                <th>Declared Value (MWK)</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="active-parcels-body">
            <?php
            include './db/connection.php';

            $parcels = $connection->query("SELECT p.*, ps.StatusName AS PaymentStatus, ds.StatusName AS DeliveryStatus, 
                                          sb.Name AS SenderBranchName, rb.Name AS ReceiverBranchName
                                     FROM Parcels p 
                                     JOIN Payment ps ON p.PaymentStatusID = ps.PaymentStatusID 
                                     JOIN DeliveryStatus ds ON p.DeliveryStatusID = ds.DeliveryStatusID 
                                     JOIN Branches sb ON p.SenderBranchID = sb.BranchID
                                     JOIN Branches rb ON p.ReceiverBranchID = rb.BranchID
                                     WHERE ds.StatusName NOT IN ('Delivered', 'Failed')");

            while ($row = $parcels->fetch_assoc()) {
                $parcelId = htmlspecialchars($row['ParcelID'], ENT_QUOTES);
                $senderName = htmlspecialchars($row['Sender'], ENT_QUOTES);
                $senderBranch = htmlspecialchars($row['SenderBranchName'], ENT_QUOTES);
                $senderContact = htmlspecialchars($row['SenderContact'], ENT_QUOTES);
                $receiverName = htmlspecialchars($row['Receiver'], ENT_QUOTES);
                $receiverBranch = htmlspecialchars($row['ReceiverBranchName'], ENT_QUOTES);
                $receiverContact = htmlspecialchars($row['ReceiverContact'], ENT_QUOTES);
                $weight = htmlspecialchars($row['WeightKg'], ENT_QUOTES);
                $declaredValue = htmlspecialchars($row['DeclaredValueMWK'], ENT_QUOTES);
                $paymentStatus = htmlspecialchars($row['PaymentStatus'], ENT_QUOTES);
                $deliveryStatus = htmlspecialchars($row['DeliveryStatus'], ENT_QUOTES);

                $parcelJson = htmlspecialchars(json_encode([
                    'id' => $row['ParcelID'],
                    'ParcelID' => $row['ParcelID'],
                    'Sender' => $row['Sender'],
                    'SenderBranchID' => $row['SenderBranchID'],
                    'SenderContact' => $row['SenderContact'],
                    'Receiver' => $row['Receiver'],
                    'ReceiverBranchID' => $row['ReceiverBranchID'],
                    'ReceiverContact' => $row['ReceiverContact'],
                    'WeightKg' => $row['WeightKg'],
                    'DeclaredValueMWK' => $row['DeclaredValueMWK'],
                    'PaymentStatusID' => $row['PaymentStatusID'],
                    'DeliveryStatusID' => $row['DeliveryStatusID']
                ]), ENT_QUOTES);

                echo "<tr class='parcel-row' data-parcel-id='{$parcelId}' data-sender-name='{$senderName}' data-receiver-name='{$receiverName}' data-payment-status='{$paymentStatus}' data-delivery-status='{$deliveryStatus}' data-sender-branch='{$senderBranch}' data-receiver-branch='{$receiverBranch}'>
                        <td>{$parcelId}</td>
                        <td>{$senderName}</td>
                        <td>{$senderBranch}</td>
                        <td>{$senderContact}</td>
                        <td>{$receiverName}</td>
                        <td>{$receiverBranch}</td>
                        <td>{$receiverContact}</td>
                        <td>{$weight}</td>
                        <td>{$declaredValue}</td>
                        <td>{$paymentStatus}</td>
                        <td>{$deliveryStatus}</td>
                        <td>
                            <button class='btn btn-warning edit-parcel-btn' data-parcel='{$parcelJson}'>Edit</button>
                            <button class='btn btn-info track-parcel-btn' data-id='{$row['ParcelID']}'>Track</button>
                            <button class='btn btn-secondary archive-parcel-btn' data-id='{$row['ParcelID']}'>Archive</button>
                        </td>
                      </tr>";
            }
            $connection->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Delivered/Failed Parcels Table -->
<div class="card">
    <h3>Delivered/Failed Parcels</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>Parcel ID</th>
                <th>Sender</th>
                <th>Sender Branch</th>
                <th>Sender Contact</th>
                <th>Receiver</th>
                <th>Receiver Branch</th>
                <th>Receiver Contact</th>
                <th>Weight (kg)</th>
                <th>Declared Value (MWK)</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="delivered-parcels-body">
            <?php
            include './db/connection.php';

            $parcels = $connection->query("SELECT p.*, ps.StatusName AS PaymentStatus, ds.StatusName AS DeliveryStatus, 
                                          sb.Name AS SenderBranchName, rb.Name AS ReceiverBranchName
                                     FROM Parcels p 
                                     JOIN Payment ps ON p.PaymentStatusID = ps.PaymentStatusID 
                                     JOIN DeliveryStatus ds ON p.DeliveryStatusID = ds.DeliveryStatusID 
                                     JOIN Branches sb ON p.SenderBranchID = sb.BranchID
                                     JOIN Branches rb ON p.ReceiverBranchID = rb.BranchID
                                     WHERE ds.StatusName IN ('Delivered', 'Failed')");

            while ($row = $parcels->fetch_assoc()) {
                $parcelId = htmlspecialchars($row['ParcelID'], ENT_QUOTES);
                $senderName = htmlspecialchars($row['Sender'], ENT_QUOTES);
                $senderBranch = htmlspecialchars($row['SenderBranchName'], ENT_QUOTES);
                $senderContact = htmlspecialchars($row['SenderContact'], ENT_QUOTES);
                $receiverName = htmlspecialchars($row['Receiver'], ENT_QUOTES);
                $receiverBranch = htmlspecialchars($row['ReceiverBranchName'], ENT_QUOTES);
                $receiverContact = htmlspecialchars($row['ReceiverContact'], ENT_QUOTES);
                $weight = htmlspecialchars($row['WeightKg'], ENT_QUOTES);
                $declaredValue = htmlspecialchars($row['DeclaredValueMWK'], ENT_QUOTES);
                $paymentStatus = htmlspecialchars($row['PaymentStatus'], ENT_QUOTES);
                $deliveryStatus = htmlspecialchars($row['DeliveryStatus'], ENT_QUOTES);

                echo "<tr class='parcel-row' data-parcel-id='{$parcelId}' data-sender-name='{$senderName}' data-receiver-name='{$receiverName}' data-payment-status='{$paymentStatus}' data-delivery-status='{$deliveryStatus}' data-sender-branch='{$senderBranch}' data-receiver-branch='{$receiverBranch}'>
                        <td>{$parcelId}</td>
                        <td>{$senderName}</td>
                        <td>{$senderBranch}</td>
                        <td>{$senderContact}</td>
                        <td>{$receiverName}</td>
                        <td>{$receiverBranch}</td>
                        <td>{$receiverContact}</td>
                        <td>{$weight}</td>
                        <td>{$declaredValue}</td>
                        <td>{$paymentStatus}</td>
                        <td>{$deliveryStatus}</td>
                        <td>
                            <button class='btn btn-info track-parcel-btn' data-id='{$row['ParcelID']}'>Track</button>
                        </td>
                      </tr>";
            }
            $connection->close();
            ?>
        </tbody>
    </table>
</div>

<script>
    function toggleAddParcelTable() {
        const table = document.getElementById('add-parcel-table');
        const isHidden = table.style.display === 'none' || table.style.display === '';
        table.style.display = isHidden ? 'block' : 'none';
        if (isHidden) {
            document.getElementById('parcel-form').reset();
            document.getElementById('display-parcel-id').innerText = 'Auto-generated';
            document.getElementById('ParcelID').value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Edit parcel button listener
        const editParcelButtons = document.querySelectorAll('.edit-parcel-btn');
        editParcelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const parcelData = JSON.parse(this.dataset.parcel);
                document.getElementById('display-parcel-id').innerText = parcelData.ParcelID;
                document.getElementById('ParcelID').value = parcelData.ParcelID;
                document.getElementById('Sender').value = parcelData.Sender;
                document.getElementById('SenderBranchID').value = parcelData.SenderBranchID;
                document.getElementById('SenderContact').value = parcelData.SenderContact;
                document.getElementById('Receiver').value = parcelData.Receiver;
                document.getElementById('ReceiverBranchID').value = parcelData.ReceiverBranchID;
                document.getElementById('ReceiverContact').value = parcelData.ReceiverContact;
                document.getElementById('WeightKg').value = parcelData.WeightKg;
                document.getElementById('DeclaredValueMWK').value = parcelData.DeclaredValueMWK;
                document.getElementById('PaymentStatusID').value = parcelData.PaymentStatusID;
                document.getElementById('DeliveryStatusID').value = parcelData.DeliveryStatusID;
                document.getElementById('add-parcel-table').style.display = 'block';
            });
        });

        // Track parcel button listener
        const trackParcelButtons = document.querySelectorAll('.track-parcel-btn');
        trackParcelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const parcelId = this.dataset.id;
                alert('Track functionality for parcel ID: ' + parcelId);
            });
        });

        // Archive parcel button listener
        const archiveParcelButtons = document.querySelectorAll('.archive-parcel-btn');
        archiveParcelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const parcelId = this.dataset.id;
                if (confirm("Are you sure you want to archive this parcel?")) {
                    fetch(`handlers/parcel_action.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${parcelId}&action=archive`
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while archiving the parcel: ' + error.message);
                    });
                }
            });
        });

        // Form submission with AJAX for parcel
        document.getElementById('parcel-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('handlers/save_parcels.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('add-parcel-table').style.display = 'none';
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the parcel: ' + error.message);
            });
        });

        // Search functionality for parcels
        const searchInput = document.getElementById('search-parcels');
        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('.parcel-row');

            rows.forEach(row => {
                const parcelId = row.dataset.parcelId.toLowerCase();
                const senderName = row.dataset.senderName.toLowerCase();
                const receiverName = row.dataset.receiverName.toLowerCase();
                const paymentStatus = row.dataset.paymentStatus.toLowerCase();
                const deliveryStatus = row.dataset.deliveryStatus.toLowerCase();
                const senderBranch = row.dataset.senderBranch.toLowerCase();
                const receiverBranch = row.dataset.receiverBranch.toLowerCase();

                const matches = parcelId.includes(searchText) || 
                               senderName.includes(searchText) || 
                               receiverName.includes(searchText) || 
                               paymentStatus.includes(searchText) || 
                               deliveryStatus.includes(searchText) ||
                               senderBranch.includes(searchText) ||
                               receiverBranch.includes(searchText);

                row.style.display = matches ? '' : 'none';
            });
        });
    });
</script>

<?php
echo '<style>';
include 'users.php';
echo '</style>';
?>