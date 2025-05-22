<!-- branches.php -->
<div class="card">
    <div class="user-management-header">
        <h2>Branch Management</h2>
        <button onclick="openAddBranchModal()" class="btn btn-primary">➕ Add Branch</button>
    </div>
    <table class="user-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>City</th>
                <th>City Side</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Operational</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="branches-body">
            <?php
            include './db/connection.php';

            $branches = $connection->query("SELECT * FROM Branches");

            while ($row = $branches->fetch_assoc()) {
                $branchId = htmlspecialchars($row['BranchID'], ENT_QUOTES);
                $name = htmlspecialchars($row['Name'], ENT_QUOTES);
                $city = htmlspecialchars($row['City'], ENT_QUOTES);
                $citySide = htmlspecialchars($row['CitySide'], ENT_QUOTES);
                $contactNumber = htmlspecialchars($row['ContactNumber'], ENT_QUOTES);
                $address = htmlspecialchars($row['Address'], ENT_QUOTES);
                $operational = $row['Operational'] ? 'Yes' : 'No';

                $branchJson = htmlspecialchars(json_encode([
                    'BranchID' => $row['BranchID'],
                    'Name' => $row['Name'],
                    'City' => $row['City'],
                    'CitySide' => $row['CitySide'],
                    'ContactNumber' => $row['ContactNumber'],
                    'Address' => $row['Address'],
                    'Operational' => $row['Operational']
                ]), ENT_QUOTES);

                echo "<tr>
                        <td>{$name}</td>
                        <td>{$city}</td>
                        <td>{$citySide}</td>
                        <td>{$contactNumber}</td>
                        <td>{$address}</td>
                        <td>{$operational}</td>
                        <td>
                            <button class='btn btn-warning edit-branch-btn' data-branch='{$branchJson}'>Edit</button>
                            <button class='btn btn-danger delete-branch-btn' data-id='{$branchId}'>Delete</button>
                        </td>
                      </tr>";
            }
            $connection->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Branch Modal -->
<div id="branchModal" class="modal">
    <div class="modal-content card">
        <span class="close" onclick="closeBranchModal()">×</span>
        <div class="modal-header">
            <h3 id="branch-modal-title">Add Branch</h3>
        </div>
        <form id="branch-form" method="post" action="handlers/save_branch.php">
            <input type="hidden" name="BranchID" id="branch-id">
            <div class="form-group">
                <label for="branch_name">Branch Name:</label>
                <input type="text" name="Name" id="branch_name" class="form-control" required placeholder="e.g., Lilongwe North Branch">
            </div>
            <div class="form-group">
                <label for="branch_city">City:</label>
                <select name="City" id="branch_city" class="form-control" required>
                    <option value="">Select City</option>
                    <option value="Lilongwe">Lilongwe</option>
                    <option value="Blantyre">Blantyre</option>
                    <option value="Mzuzu">Mzuzu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="branch_city_side">City Side:</label>
                <input type="text" name="CitySide" id="branch_city_side" class="form-control" placeholder="e.g., North">
            </div>
            <div class="form-group">
                <label for="branch_contact">Contact Number:</label>
                <input type="text" name="ContactNumber" id="branch_contact" class="form-control" required placeholder="e.g., 0999555666">
            </div>
            <div class="form-group">
                <label for="branch_address">Address:</label>
                <input type="text" name="Address" id="branch_address" class="form-control" required placeholder="e.g., P.O. Box 100, City Centre">
            </div>
            <div class="form-group">
                <label for="operational">Operational:</label>
                <input type="checkbox" name="Operational" id="operational" value="1" checked>
            </div>
            <button type="submit" class="btn btn-success">Save Branch</button>
        </form>
    </div>
</div>

<script>
    function openAddBranchModal() {
        document.getElementById('branch-form').reset();
        document.getElementById('branch-modal-title').innerText = 'Add Branch';
        document.getElementById('branch-id').value = '';
        document.getElementById('operational').checked = true;
        document.getElementById('branchModal').style.display = 'flex';
    }

    function closeBranchModal() {
        document.getElementById('branchModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
        const branchModal = document.getElementById('branchModal');
        if (event.target === branchModal) {
            branchModal.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Edit branch button listener
        const editBranchButtons = document.querySelectorAll('.edit-branch-btn');
        editBranchButtons.forEach(button => {
            button.addEventListener('click', function () {
                const branchData = JSON.parse(this.dataset.branch);
                document.getElementById('branch-modal-title').innerText = 'Edit Branch';
                document.getElementById('branch-id').value = branchData.BranchID;
                document.getElementById('branch_name').value = branchData.Name;
                document.getElementById('branch_city').value = branchData.City;
                document.getElementById('branch_city_side').value = branchData.CitySide;
                document.getElementById('branch_contact').value = branchData.ContactNumber;
                document.getElementById('branch_address').value = branchData.Address;
                document.getElementById('operational').checked = branchData.Operational == 1;
                document.getElementById('branchModal').style.display = 'flex';
            });
        });

        // Delete branch button listener
        const deleteBranchButtons = document.querySelectorAll('.delete-branch-btn');
        deleteBranchButtons.forEach(button => {
            button.addEventListener('click', function () {
                const branchId = this.dataset.id;
                if (confirm("Are you sure you want to delete this branch?")) {
                    fetch(`handlers/branch_action.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `BranchID=${branchId}&action=delete`
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
                        alert('An error occurred while deleting the branch: ' + error.message);
                    });
                }
            });
        });

        // Form submission with AJAX for branch
        document.getElementById('branch-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('handlers/save_branch.php', {
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
                    closeBranchModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the branch: ' + error.message);
            });
        });
    });
</script>

<?php
echo '<style>';
include 'users.php';
echo '</style>';
?>