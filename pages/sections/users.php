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
?>


        </tbody>
    </table>
</div>

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
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody id="suspended-users-body">
        <?php
        // Your existing PHP code for this table
        // Ensure the Edit button's data attributes are correctly populated for this table too if needed for JS
        $users_suspended = $conn->query("SELECT * FROM users2 WHERE status IN ('suspended', 'deleted')");

        while ($row_suspended = $users_suspended->fetch_assoc()) {
            $fullName_suspended = htmlspecialchars($row_suspended['first_name'] . ' ' . $row_suspended['last_name'], ENT_QUOTES);
            $email_suspended = htmlspecialchars($row_suspended['email'], ENT_QUOTES);
            $role_suspended = htmlspecialchars($row_suspended['role'], ENT_QUOTES);
            $status_suspended = htmlspecialchars($row_suspended['status'], ENT_QUOTES);

            // For the edit button in suspended table, ensure you pass all necessary data attributes
            // if the same modal and JS function is used for editing these users.
            $userJson_suspended = htmlspecialchars(json_encode([
                'id' => $row_suspended['id'],
                'first_name' => $row_suspended['first_name'],
                'second_name' => $row_suspended['last_name'],
                'email' => $row_suspended['email'],
                'role' => $row_suspended['role']
            ]), ENT_QUOTES);

            echo "<tr>
            <td>{$row_suspended['id']}</td>
            <td>{$fullName_suspended}</td>
            <td>{$email_suspended}</td>
            <td>{$role_suspended}</td>
            <td>{$status_suspended}</td>
            <td>
                <button 
                    class='btn btn-warning edit-user-btn'
                    data-id='{$row_suspended['id']}'
                    data-first_name=\"" . htmlspecialchars($row_suspended['first_name'], ENT_QUOTES) . "\"
                    data-second_name=\"" . htmlspecialchars($row_suspended['last_name'], ENT_QUOTES) . "\"
                    data-email=\"" . htmlspecialchars($row_suspended['email'], ENT_QUOTES) . "\"
                    data-role=\"" . htmlspecialchars($row_suspended['role'], ENT_QUOTES) . "\"
                >Edit</button>
            </td>
        </tr>";
        }
        ?>
        </tbody>
    </table>
</div>


<<div id="userModal" class="modal">
    <div class="modal-content card"> 
        <span class="close" onclick="closeUserModal()">&times;</span>
        <div class="modal-header">
            <h3 id="modal-title">Add User</h3>
        </div>
        <form id="user-form" method="post" action="handlers/save_user.php">
            <input type="hidden" name="user_id" id="user_id">

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="second_name">Last Name:</label>
                <input type="text" name="second_name" id="second_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group"> 
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <div class="form-group"> 
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control"> 
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

    /* Card Styling */
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 25px; /* Ensures space between cards and elements */
        overflow: auto; /* Clears floats and contains content */
    }

    .card h2, .card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
    }

    /* User Management Header Specific Styles */
    .user-management-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* User Table Styling */
    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        overflow: hidden; /* Keeps rounded corners for header within card context */
        border-radius: 8px; /* Apply radius to table if it's visually distinct from card */
    }

    .user-table th {
        background-color: #07c43d;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }
    /* To make top-left and top-right corners of TH rounded */
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
    
    .user-table td .btn { /* Add some spacing between buttons in the actions column */
        margin-right: 5px;
    }
    .user-table td .btn:last-child {
        margin-right: 0;
    }


    /* Button Styles */
    .btn {
        padding: 9px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, transform 0.1s ease;
        font-weight: 500;
        line-height: 1.5;
        text-decoration: none; /* For any <a> tags styled as buttons */
        display: inline-block; /* Proper alignment and sizing */
        vertical-align: middle; /* Align with text/icons */
    }
    .btn:active {
        transform: translateY(1px); /* Slight press effect */
    }

    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.35);
    }

    .btn-primary { /* For "Add User" */
        background-color: #2b38f0;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #262ea3;
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
        color: #fff; 
    }
    .btn-warning:hover {
        background-color: #262ea3;
    }

    .btn-secondary { /* For "Suspend" */
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

    /* Form Control Styling */
    .form-group {
        margin-bottom: 18px; /* Consistent spacing for form groups */
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
        /* margin-top: 5px; /* Replaced by label margin-bottom in .form-group */
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #80bdff; /* Blue focus, consistent with Bootstrap */
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 1050;
        padding: 20px; /* Add padding for smaller screens so modal doesn't touch edges */
        box-sizing: border-box;
    }

    .modal-content {
        background: #fff;
        padding: 25px 30px;
        width: 100%; /* Full width up to max-width */
        max-width: 500px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        animation: fadeInModal 0.3s ease-out;
    }

    .modal-header {
        padding-bottom: 15px; /* Space between title and form */
        margin-bottom: 20px; /* Space before the first form element */
        border-bottom: 1px solid #e9ecef; /* Subtle line under the header */
    }
    .modal-header h3 {
        margin: 0; /* Remove default h3 margin as .modal-header handles spacing */
        font-size: 1.5rem; /* Slightly larger modal title */
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

    /* Badge Styling (as per your original CSS, can be used for status if needed) */
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

    /* Keyframes for Modal Animation */
    @keyframes fadeInModal {
        from { opacity: 0; transform: translateY(-20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
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

document.addEventListener('DOMContentLoaded', function () {
    // Suspend user
    document.querySelectorAll('.suspend-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.id;
            if (confirm("Are you sure you want to suspend this user?")) {
                fetch(`handlers/user_Actions.php`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${userId}&action=suspend`
                }).then(() => location.reload());
            }
        });
    });

    // Delete user
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.id;
            if (confirm("Are you sure you want to delete this user?")) {
                fetch(`handlers/user_actions.php`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `id=${userId}&action=delete`
                }).then(() => location.reload());
            }
        });
    });
});


    // Optionally: Add logic here to open modal in "edit mode" with pre-filled fields
</script>
