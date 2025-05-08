<!-- User Management Header -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
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
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="active-users-body">
        <?php
include './db/connection.php';

$users = $conn->query("SELECT * FROM users2 WHERE status = 'active'");

while ($row = $users->fetch_assoc()) {
    $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES);
    $email = htmlspecialchars($row['email'], ENT_QUOTES);
    $role = htmlspecialchars($row['role'], ENT_QUOTES);
    $status = htmlspecialchars($row['status'], ENT_QUOTES);

    $userJson = htmlspecialchars(json_encode([
        'id' => $row['id'],
        'first_name' => $row['first_name'],
        'second_name' => $row['last_name'], // referred to as 'second_name' in JS
        'email' => $row['email'],
        'role' => $row['role']
    ]), ENT_QUOTES);
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$fullName}</td>
    <td>{$email}</td>
    <td>{$role}</td>
    <td>{$status}</td>
    <td>
        <button 
            class='btn btn-warning edit-user-btn'
            data-id='{$row['id']}'
            data-first_name=\"" . htmlspecialchars($row['first_name'], ENT_QUOTES) . "\"
            data-second_name=\"" . htmlspecialchars($row['last_name'], ENT_QUOTES) . "\"
            data-email=\"" . htmlspecialchars($row['email'], ENT_QUOTES) . "\"
            data-role=\"" . htmlspecialchars($row['role'], ENT_QUOTES) . "\"
        >Edit</button>
    </td>
</tr>";

}
?>


        </tbody>
    </table>
</div>

<!-- Suspended Users Table -->
<div class="card">
    <h3>Suspended / Deleted Users</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="suspended-users-body">
        <?php

$users = $conn->query("SELECT * FROM users2 WHERE status IN ('suspended', 'deleted')");

while ($row = $users->fetch_assoc()) {
    $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name'], ENT_QUOTES);
    $email = htmlspecialchars($row['email'], ENT_QUOTES);
    $role = htmlspecialchars($row['role'], ENT_QUOTES);
    $status = htmlspecialchars($row['status'], ENT_QUOTES);

    $userJson = htmlspecialchars(json_encode([
        'id' => $row['id'],
        'first_name' => $row['first_name'],
        'second_name' => $row['last_name'], // Use as second_name in JS
        'email' => $row['email'],
        'role' => $row['role']
    ]), ENT_QUOTES);
            echo "<tr>
            <td>{$row['id']}</td>
            <td>{$fullName}</td>
            <td>{$email}</td>
            <td>{$role}</td>
            <td>{$status}</td>
            <td>
                <button 
                    class='btn btn-warning edit-user-btn'
                    data-id='{$row['id']}'
                    data-first_name=\"" . htmlspecialchars($row['first_name'], ENT_QUOTES) . "\"
                    data-second_name=\"" . htmlspecialchars($row['last_name'], ENT_QUOTES) . "\"
                    data-email=\"" . htmlspecialchars($row['email'], ENT_QUOTES) . "\"
                    data-role=\"" . htmlspecialchars($row['role'], ENT_QUOTES) . "\"
                >Edit</button>
            </td>
        </tr>";

}
?>

        </tbody>
    </table>
</div>


<!-- Add/Edit User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content card">
        <span class="close" onclick="closeUserModal()">&times;</span>
        <h3 id="modal-title" style="margin-bottom: 20px;">Add User</h3>
        <form id="user-form" method="post" action="handlers/save_user.php">
            <input type="hidden" name="user_id" id="user_id">

            <div style="margin-bottom: 15px;">
                <label for="first_name">First Name:</label><br>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="second_name">Last Name:</label><br>
                <input type="text" name="second_name" id="second_name" class="form-control" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="email">Email:</label><br>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="role">Role:</label><br>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password">Password:</label><br>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save User</button>
        </form>
    </div>
</div>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f9f9f9;
        margin: 0;
        padding: 20px;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .user-table th {
        background-color: #07c43d;
        color: white;
        padding: 12px;
        text-align: left;
    }

    .user-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f1f1;
        color: #333;
    }

    .user-table tr:hover {
        background-color: #f5f5f5;
    }

    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-primary {
        background-color: #2b38f0 ;
        color: #fff;
    }

    .btn-primary:hover {
        background-color:#262ea3;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #2b38f0;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #262ea3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    .edit-user-btn{
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
    .form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 14px;
    margin-top: 5px;
    background: #fdfdfd;
    }

    .form-control:focus {
        border-color: #07c43d;
        outline: none;
        box-shadow: 0 0 0 2px rgba(7, 196, 61, 0.2);
    }


    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .modal-content {
        background: #fff;
        padding: 25px 30px;
        width: 500px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    .close {
        position: absolute;
        top: 10px; right: 15px;
        font-size: 22px;
        font-weight: bold;
        color: #555;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        text-transform: capitalize;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
</style>


<script>
    // Open modal for adding a new user
    function openAddUserModal() {
        document.getElementById('user-form').reset();
        document.getElementById('modal-title').innerText = 'Add User';
        document.getElementById('user_id').value = '';
        document.getElementById('userModal').style.display = 'flex';
    }

    // Close the modal
    function closeUserModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('userModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Attach edit button listeners after DOM content is fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-user-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modal-title').innerText = 'Edit User';
                document.getElementById('user_id').value = button.getAttribute('data-id');
                document.getElementById('first_name').value = button.getAttribute('data-first_name');
                document.getElementById('second_name').value = button.getAttribute('data-second_name');
                document.getElementById('email').value = button.getAttribute('data-email');
                document.getElementById('role').value = button.getAttribute('data-role');
                document.getElementById('password').value = ''; // Leave password blank for security
                document.getElementById('userModal').style.display = 'flex';
            });
        });
    });



    // Optionally: Add logic here to open modal in "edit mode" with pre-filled fields
</script>
