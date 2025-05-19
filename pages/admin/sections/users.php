<?php
// Include database connection
include './db/connection.php';

// Ensure connection is valid
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!-- User Management Header -->
<div class="card">
    <div class="user-management-header"> 
        <h2>User Management</h2>
        <button onclick="openAddUserModal()" class="btn btn-primary">➕ Add User</button>
    </div>
</div>

<!-- Active Users Table -->
<div class="card">
    <h3>Active Users</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="active-users-body">
        <?php
        // Query for active users
        $query = "SELECT id, name, surname, email, phone_number, role, status FROM users WHERE status = 'active'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            echo "<tr><td colspan='7'>Error: " . mysqli_error($connection) . "</td></tr>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $fullName = htmlspecialchars($row['name'] . ' ' . $row['surname'], ENT_QUOTES);
                $email = htmlspecialchars($row['email'], ENT_QUOTES);
                $phone_number = htmlspecialchars($row['phone_number'], ENT_QUOTES);
                $role = htmlspecialchars($row['role'], ENT_QUOTES);
                $status = htmlspecialchars($row['status'], ENT_QUOTES);

                $userJson = htmlspecialchars(json_encode([
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'email' => $row['email'],
                    'phone_number' => $row['phone_number'],
                    'role' => $row['role']
                ]), ENT_QUOTES);

                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$fullName}</td>
                    <td>{$email}</td>
                    <td>{$phone_number}</td>
                    <td>{$role}</td>
                    <td>{$status}</td>
                    <td>
                        <button 
                            class='btn btn-warning edit-user-btn'
                            data-id='{$row['id']}'
                            data-name=\"" . htmlspecialchars($row['name'], ENT_QUOTES) . "\"
                            data-surname=\"" . htmlspecialchars($row['surname'], ENT_QUOTES) . "\"
                            data-email=\"" . htmlspecialchars($row['email'], ENT_QUOTES) . "\"
                            data-phone_number=\"" . htmlspecialchars($row['phone_number'], ENT_QUOTES) . "\"
                            data-role=\"" . htmlspecialchars($row['role'], ENT_QUOTES) . "\"
                        >Edit</button>
                        <button 
                            class='btn btn-secondary suspend-user-btn'
                            data-id='{$row['id']}'
                        >Suspend</button>
                        <button 
                            class='btn btn-danger delete-user-btn'
                            data-id='{$row['id']}'
                        >Delete</button>
                    </td>
                </tr>";
            }
            mysqli_free_result($result);
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Suspended/Deleted Users Table -->
<div class="card">
    <h3>Suspended / Deleted Users</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody id="suspended-users-body">
        <?php
        // Query for suspended or deleted users
        $query_suspended = "SELECT id, name, surname, email, phone_number, role, status FROM users WHERE status IN ('suspended', 'deleted')";
        $result_suspended = mysqli_query($connection, $query_suspended);

        if (!$result_suspended) {
            echo "<tr><td colspan='7'>Error: " . mysqli_error($connection) . "</td></tr>";
        } else {
            while ($row_suspended = mysqli_fetch_assoc($result_suspended)) {
                $fullName_suspended = htmlspecialchars($row_suspended['name'] . ' ' . $row_suspended['surname'], ENT_QUOTES);
                $email_suspended = htmlspecialchars($row_suspended['email'], ENT_QUOTES);
                $phone_number_suspended = htmlspecialchars($row_suspended['phone_number'], ENT_QUOTES);
                $role_suspended = htmlspecialchars($row_suspended['role'], ENT_QUOTES);
                $status_suspended = htmlspecialchars($row_suspended['status'], ENT_QUOTES);

                echo "<tr>
                    <td>{$row_suspended['id']}</td>
                    <td>{$fullName_suspended}</td>
                    <td>{$email_suspended}</td>
                    <td>{$phone_number_suspended}</td>
                    <td>{$role_suspended}</td>
                    <td>{$status_suspended}</td>
                    <td>";
                if ($row_suspended['status'] === 'suspended') {
                    echo "<button 
                            class='btn btn-success unsuspend-user-btn'
                            data-id='{$row_suspended['id']}'
                        >Unsuspend</button>";
                }
                echo "</td>
                </tr>";
            }
            mysqli_free_result($result_suspended);
        }
        ?>
        </tbody>
    </table>
</div>

<!-- User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content card"> 
        <span class="close" onclick="closeUserModal()">×</span>
        <div class="modal-header">
            <h3 id="modal-title">Add User</h3>
        </div>
        <form id="user-form" method="post" action="handlers/save_user.php">
            <input type="hidden" name="user_id" id="user_id">

            <div class="form-group">
                <label for="name">First Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="surname">Last Name:</label>
                <input type="text" name="surname" id="surname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" placeholder="+26598.." class="form-control">
            </div>

            <div class="form-group"> 
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="branch-admin">Branch Admin</option>
                    <option value="receptionist">Receptionist</option>
                    <option value="driver">Driver</option>
                </select>
            </div>

            <div class="form-group"> 
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep unchanged">
            </div>

            <button type="submit" class="btn btn-success">Save User</button>
        </form>
    </div>
</div>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 25px;
        overflow: auto;
    }

    .card h2, .card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
    }

    .user-management-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        overflow: hidden;
        border-radius: 8px;
    }

    .user-table th {
        background-color: #28a745; /* Green */
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }

    .user-table th:first-child {
        border-top-left-radius: 8px;
    }

    .user-table th:last-child {
        border-top-right-radius: 8px;
    }

    .user-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
        color: #495057;
    }

    .user-table tr:last-child td {
        border-bottom: none;
    }

    .user-table tr:hover {
        background-color: #f5f5f5;
    }

    .user-table td .btn {
        margin-right: 5px;
    }

    .user-table td .btn:last-child {
        margin-right: 0;
    }

    .btn {
        padding: 9px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, transform 0.1s ease;
        font-weight: 500;
        line-height: 1.5;
        text-decoration: none;
        display: inline-block;
        vertical-align: middle;
    }

    .btn:active {
        transform: translateY(1px);
    }

    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.35);
    }

    .btn-primary {
        background-color: #0077cc; /* CTS blue */
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #262ea3;
    }

    .btn-success {
        background-color:  #28a745; /* Green */
        color: #fff;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #0077cc; /* CTS blue */
        color: #fff;
    }

    .btn-warning:hover {
        background-color: #262ea3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 14px;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 1050;
        padding: 20px;
        box-sizing: border-box;
    }

    .modal-content {
        background: #fff;
        padding: 25px 30px;
        width: 100%;
        max-width: 500px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        animation: fadeInModal 0.3s ease-out;
    }

    .modal-header {
        padding-bottom: 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        color: #6c757d;
        cursor: pointer;
        line-height: 1;
    }

    .close:hover {
        color: #343a40;
    }

    .badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .375rem;
        text-transform: capitalize;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    @keyframes fadeInModal {
        from { opacity: 0; transform: translateY(-20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

<script>
    function openAddUserModal() {
        document.getElementById('user-form').reset();
        document.getElementById('modal-title').innerText = 'Add User';
        document.getElementById('user_id').value = '';
        document.getElementById('password').required = true;
        document.getElementById('userModal').style.display = 'flex';
    }

    function closeUserModal() {
        document.getElementById('userModal').style.display = 'none';
        document.getElementById('password').required = false;
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('userModal');
        if (event.target === modal) {
            modal.style.display = 'none';
            document.getElementById('password').required = false;
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-user-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modal-title').innerText = 'Edit User';
                document.getElementById('user_id').value = button.getAttribute('data-id');
                document.getElementById('name').value = button.getAttribute('data-name');
                document.getElementById('surname').value = button.getAttribute('data-surname');
                document.getElementById('email').value = button.getAttribute('data-email');
                document.getElementById('phone_number').value = button.getAttribute('data-phone_number');
                document.getElementById('role').value = button.getAttribute('data-role');
                document.getElementById('password').value = '';
                document.getElementById('password').required = false;
                document.getElementById('userModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.suspend-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm("Are you sure you want to suspend this user?")) {
                    fetch(`handlers/user_actions.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${userId}&action=suspend`
                    }).then(() => location.reload());
                }
            });
        });

        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm("Are you sure you want to delete this user? please not this action is irreversible")) {
                    fetch(`handlers/user_actions.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${userId}&action=delete`
                    }).then(() => location.reload());
                }
            });
        });

        document.querySelectorAll('.unsuspend-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm("Are you sure you want to unsuspend this user?")) {
                    fetch(`handlers/user_actions.php`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${userId}&action=unsuspend`
                    }).then(() => location.reload());
                }
            });
        });
    });
</script>

<?php
// Close the database connection
mysqli_close($connection);
?>