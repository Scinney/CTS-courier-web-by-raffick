<!-- parcels.php -->
<div class="card">
    <div class="user-management-header">
        <h2>Parcel Management</h2>
        <button onclick="openAddParcelModal()" class="btn btn-primary">➕ Add Parcel</button>
    </div>
    <div class="form-group" style="margin-top: 15px;">
        <label for="search-parcels">Search Parcels:</label>
        <input type="text" id="search-parcels" class="form-control" placeholder="Search by Parcel ID, Sender, Receiver, or Status">
    </div>
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
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="active-parcels-body">
            <?php
            include './db/connection.php';

            $parcels = $conn->query("SELECT p.*, sb.name AS sender_branch_name, rb.name AS receiver_branch_name 
                                     FROM parcels p 
                                     JOIN branches sb ON p.sender_branch_id = sb.id 
                                     JOIN branches rb ON p.receiver_branch_id = rb.id 
                                     WHERE p.status NOT IN ('delivered', 'archived', 'deleted')");

            while ($row = $parcels->fetch_assoc()) {
                $parcelId = htmlspecialchars($row['parcel_id'], ENT_QUOTES);
                $senderName = htmlspecialchars($row['sender_name'], ENT_QUOTES);
                $senderBranch = htmlspecialchars($row['sender_branch_name'], ENT_QUOTES);
                $senderContact = htmlspecialchars($row['sender_contact'], ENT_QUOTES);
                $receiverName = htmlspecialchars($row['receiver_name'], ENT_QUOTES);
                $receiverBranch = htmlspecialchars($row['receiver_branch_name'], ENT_QUOTES);
                $receiverContact = htmlspecialchars($row['receiver_contact'], ENT_QUOTES);
                $weight = htmlspecialchars($row['weight'], ENT_QUOTES);
                $declaredValue = htmlspecialchars($row['declared_value'], ENT_QUOTES);
                $status = htmlspecialchars($row['status'], ENT_QUOTES);

                $parcelJson = htmlspecialchars(json_encode([
                    'id' => $row['id'],
                    'parcel_id' => $row['parcel_id'],
                    'sender_name' => $row['sender_name'],
                    'sender_branch_id' => $row['sender_branch_id'],
                    'sender_contact' => $row['sender_contact'],
                    'receiver_name' => $row['receiver_name'],
                    'receiver_branch_id' => $row['receiver_branch_id'],
                    'receiver_contact' => $row['receiver_contact'],
                    'weight' => $row['weight'],
                    'declared_value' => $row['declared_value'],
                    'status' => $row['status']
                ]), ENT_QUOTES);

                echo "<tr class='parcel-row' data-parcel-id='{$parcelId}' data-sender-name='{$senderName}' data-receiver-name='{$receiverName}' data-status='{$status}'>
                        <td>{$parcelId}</td>
                        <td>{$senderName}</td>
                        <td>{$senderBranch}</td>
                        <td>{$senderContact}</td>
                        <td>{$receiverName}</td>
                        <td>{$receiverBranch}</td>
                        <td>{$receiverContact}</td>
                        <td>{$weight}</td>
                        <td>{$declaredValue}</td>
                        <td>{$status}</td>
                        <td>
                            <button class='btn btn-warning edit-parcel-btn' data-parcel='{$parcelJson}'>Edit</button>
                            <button class='btn btn-info track-parcel-btn' data-id='{$row['id']}'>Track</button>
                            <button class='btn btn-secondary archive-parcel-btn' data-id='{$row['id']}'>Archive</button>
                        </td>
                      </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Delivered/Archived Parcels Table -->
<div class="card">
    <h3>Delivered/Archived Parcels</h3>
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
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="delivered-parcels-body">
            <?php
            include './db/connection.php';

            $parcels = $conn->query("SELECT p.*, sb.name AS sender_branch_name, rb.name AS receiver_branch_name 
                                     FROM parcels p 
                                     JOIN branches sb ON p.sender_branch_id = sb.id 
                                     JOIN branches rb ON p.receiver_branch_id = rb.id 
                                     WHERE p.status IN ('delivered', 'archived')");

            while ($row = $parcels->fetch_assoc()) {
                $parcelId = htmlspecialchars($row['parcel_id'], ENT_QUOTES);
                $senderName = htmlspecialchars($row['sender_name'], ENT_QUOTES);
                $senderBranch = htmlspecialchars($row['sender_branch_name'], ENT_QUOTES);
                $senderContact = htmlspecialchars($row['sender_contact'], ENT_QUOTES);
                $receiverName = htmlspecialchars($row['receiver_name'], ENT_QUOTES);
                $receiverBranch = htmlspecialchars($row['receiver_branch_name'], ENT_QUOTES);
                $receiverContact = htmlspecialchars($row['receiver_contact'], ENT_QUOTES);
                $weight = htmlspecialchars($row['weight'], ENT_QUOTES);
                $declaredValue = htmlspecialchars($row['declared_value'], ENT_QUOTES);
                $status = htmlspecialchars($row['status'], ENT_QUOTES);

                echo "<tr class='parcel-row' data-parcel-id='{$parcelId}' data-sender-name='{$senderName}' data-receiver-name='{$receiverName}' data-status='{$status}'>
                        <td>{$parcelId}</td>
                        <td>{$senderName}</td>
                        <td>{$senderBranch}</td>
                        <td>{$senderContact}</td>
                        <td>{$receiverName}</td>
                        <td>{$receiverBranch}</td>
                        <td>{$receiverContact}</td>
                        <td>{$weight}</td>
                        <td>{$declaredValue}</td>
                        <td>{$status}</td>
                        <td>
                            <button class='btn btn-info track-parcel-btn' data-id='{$row['id']}'>Track</button>
                        </td>
                      </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Parcel Modal -->
<div id="parcelModal" class="modal">
    <div class="modal-content card">
        <span class="close" onclick="closeParcelModal()">×</span>
        <div class="modal-header">
            <h3 id="modal-title">Add Parcel</h3>
        </div>
        <form id="parcel-form" method="post" action="handlers/save_parcels.php">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="parcel_id">Parcel ID:</label>
                <input type="text" name="parcel_id" id="parcel_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="sender_name">Sender Name:</label>
                <input type="text" name="sender_name" id="sender_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="sender_branch_id">Sender Branch:</label>
                <select name="sender_branch_id" id="sender_branch_id" class="form-control" required>
                    <option value="">Select Branch</option>
                    <?php
                    include './db/connection.php';
                    $branches = $conn->query("SELECT * FROM branches WHERE is_operational = 1");
                    while ($branch = $branches->fetch_assoc()) {
                        $branchName = htmlspecialchars($branch['name'], ENT_QUOTES);
                        $city = htmlspecialchars($branch['city'], ENT_QUOTES);
                        echo "<option value='{$branch['id']}'>{$branchName} ({$city})</option>";
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sender_contact">Sender Contact:</label>
                <input type="text" name="sender_contact" id="sender_contact" class="form-control" required placeholder="e.g., 0999123456">
            </div>
            <div class="form-group">
                <label for="receiver_name">Receiver Name:</label>
                <input type="text" name="receiver_name" id="receiver_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="receiver_branch_id">Receiver Branch:</label>
                <select name="receiver_branch_id" id="receiver_branch_id" class="form-control" required>
                    <option value="">Select Branch</option>
                    <?php
                    include './db/connection.php';
                    $branches = $conn->query("SELECT * FROM branches WHERE is_operational = 1");
                    while ($branch = $branches->fetch_assoc()) {
                        $branchName = htmlspecialchars($branch['name'], ENT_QUOTES);
                        $city = htmlspecialchars($branch['city'], ENT_QUOTES);
                        echo "<option value='{$branch['id']}'>{$branchName} ({$city})</option>";
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="receiver_contact">Receiver Contact:</label>
                <input type="text" name="receiver_contact" id="receiver_contact" class="form-control" required placeholder="e.g., 0888765432">
            </div>
            <div class="form-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" step="0.01" name="weight" id="weight" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="declared_value">Declared Value (MWK):</label>
                <input type="number" step="0.01" name="declared_value" id="declared_value" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Select Status (optional)</option>
                    <option value="In Transit">In Transit</option>
                    <option value="Out for Delivery">Out for Delivery</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Returned">Returned</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save Parcel</button>
        </form>
    </div>
</div>

<script>
    function openAddParcelModal() {
        document.getElementById('parcel-form').reset();
        document.getElementById('modal-title').innerText = 'Add Parcel';
        document.getElementById('id').value = '';
        document.getElementById('parcelModal').style.display = 'flex';
    }

    function closeParcelModal() {
        document.getElementById('parcelModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
        const parcelModal = document.getElementById('parcelModal');
        if (event.target === parcelModal) {
            parcelModal.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Edit parcel button listener
        const editParcelButtons = document.querySelectorAll('.edit-parcel-btn');
        editParcelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const parcelData = JSON.parse(this.dataset.parcel);
                document.getElementById('modal-title').innerText = 'Edit Parcel';
                document.getElementById('id').value = parcelData.id;
                document.getElementById('parcel_id').value = parcelData.parcel_id;
                document.getElementById('sender_name').value = parcelData.sender_name;
                document.getElementById('sender_branch_id').value = parcelData.sender_branch_id;
                document.getElementById('sender_contact').value = parcelData.sender_contact;
                document.getElementById('receiver_name').value = parcelData.receiver_name;
                document.getElementById('receiver_branch_id').value = parcelData.receiver_branch_id;
                document.getElementById('receiver_contact').value = parcelData.receiver_contact;
                document.getElementById('weight').value = parcelData.weight;
                document.getElementById('declared_value').value = parcelData.declared_value;
                document.getElementById('status').value = parcelData.status || '';
                document.getElementById('parcelModal').style.display = 'flex';
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
                    closeParcelModal();
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
                const status = row.dataset.status.toLowerCase();

                const matches = parcelId.includes(searchText) || 
                               senderName.includes(searchText) || 
                               receiverName.includes(searchText) || 
                               status.includes(searchText);

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