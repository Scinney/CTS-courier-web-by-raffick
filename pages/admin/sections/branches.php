<!-- branch.php -->
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
                <th>Contact Number</th>
                <th>Address</th>
                <th>Operational</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="branches-body">
            <?php
            include './db/connection.php';

            $branches = $conn->query("SELECT * FROM branches");

            while ($row = $branches->fetch_assoc()) {
                $name = htmlspecialchars($row['name'], ENT_QUOTES);
                $city = htmlspecialchars($row['city'], ENT_QUOTES);
                $contactNumber = htmlspecialchars($row['contact_number'], ENT_QUOTES);
                $address = htmlspecialchars($row['address'], ENT_QUOTES);
                $isOperational = $row['is_operational'] ? 'Yes' : 'No';

                $branchJson = htmlspecialchars(json_encode([
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'city' => $row['city'],
                    'contact_number' => $row['contact_number'],
                    'address' => $row['address'],
                    'is_operational' => $row['is_operational']
                ]), ENT_QUOTES);

                echo "<tr>
                        <td>{$name}</td>
                        <td>{$city}</td>
                        <td>{$contactNumber}</td>
                        <td>{$address}</td>
                        <td>{$isOperational}</td>
                        <td>
                            <button class='btn btn-warning edit-branch-btn' data-branch='{$branchJson}'>Edit</button>
                            <button class='btn btn-danger delete-branch-btn' data-id='{$row['id']}'>Delete</button>
                        </td>
                      </tr>";
            }
            $conn->close();
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
            <input type="hidden" name="id" id="branch-id">
            <div class="form-group">
                <label for="branch_name">Branch Name:</label>
                <input type="text" name="name" id="branch_name" class="form-control" required placeholder="e.g., Lilongwe North Branch">
            </div>
            <div class="form-group">
                <label for="branch_city">City:</label>
                <select name="city" id="branch_city" class="form-control" required>
                    <option value="">Select City</option>
                    <option value="Lilongwe">Lilongwe</option>
                    <option value="Blantyre">Blantyre</option>
                    <option value="Mzuzu">Mzuzu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="branch_contact">Contact Number:</label>
                <input type="text" name="contact_number" id="branch_contact" class="form-control" required placeholder="e.g., 0999555666">
            </div>
            <div class="form-group">
                <label for="branch_address">Address:</label>
                <input type="text" name="address" id="branch_address" class="form-control" required placeholder="e.g., P.O. Box 100, City Centre">
            </div>
            <div class="form-group">
                <label for="is_operational">Operational:</label>
                <input type="checkbox" name="is_operational" id="is_operational" value="1" checked>
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
        document.getElementById('is_operational').checked = true;
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
                document.getElementById('branch-id').value = branchData.id;
                document.getElementById('branch_name').value = branchData.name;
                document.getElementById('branch_city').value = branchData.city;
                document.getElementById('branch_contact').value = branchData.contact_number;
                document.getElementById('branch_address').value = branchData.address;
                document.getElementById('is_operational').checked = branchData.is_operational == 1;
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
                        body: `id=${branchId}&action=delete`
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