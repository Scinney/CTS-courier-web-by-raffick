<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./authentication/login.php");
    exit();
}
$id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage_employee</title>
</head>
<body>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            display: flex;
            align-items: center;
            background-color: #07c43d;
            color: #fff;
            padding: 10px 20px;
        }

        .toggle-button {
            background-color: #07c43d;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .header-content {
            margin-left: 10px;
        }

        .header-content h1 {
            margin: 0;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            padding: 10px;
            background-color: #07c43d;

        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            margin: 0 15px;
            font-size: 18px;
        }

        .nav-links a:hover {
            color: #007BFF;
        }

        .logo {
            margin-left: auto;
        }

        .logo img {
            max-height: 40px;
        }

        .toggle-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .toggle-menu ul {
            list-style: none;
            padding: 0;
        }

        .toggle-menu li {
            margin: 20px 0;
        }

        .toggle-menu a {
            text-decoration: none;
            color: #fff;
        }

        .toggle-menu a:hover {
            color: #007BFF;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <header>
        <button class="toggle-button" onclick="toggleMenu()">☰</button>
        <div class="header-content">
            <h1>CTS Courier PMS</h1>
        </div>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
    </header>
    <div class="nav-links">
        <a href="dashboard.html">Home</a>
        <a href="services.html">Services</a>
        <a href="#">Contact</a>
       
    </div>
    <div>
    <nav>
        <span>Welcome, <?php echo $first_name; ?> (<?php echo ucfirst($role); ?>)</span>
        <span class="contact-icon">📞 Contact: <?php echo $first_name; ?></span>
        
    </nav>
    </div>
    <section>
        <h3>Admin Controls</h3>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_messages.php">Manage Messages</a></li>
            <li><a href="manage_notifications.php">Manage Notifications</a></li>
            <li><a href="confirm_delivery.php">Confirm Deliveries</a></li>
        </ul>
    </section>
    <?php


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../404.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: manage_users.php");
}
?>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    <div class="toggle-menu" id="toggleMenu">
        <ul>
            <H2>Manage Users</H2>
            <li><a href="./delete_employee.php">Delete User</a></li>
            <li><a href="./add_employee.php">Add User</a></li>
            <li><a href="./edit_employee.php">Edit User</a></li>
            <li><a href="./change_employee_status.php">Update User</a></li>
            <li><a href="#">View Users</a></li>
            <li><a href="#">Settings</a></li>
            <li> <a href="../authentication/logout.php">Logout</a></li>
        </ul>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('toggleMenu');
            const overlay = document.getElementById('overlay');
            const isMenuOpen = menu.style.display === 'block';

            menu.style.display = isMenuOpen ? 'none' : 'block';
            overlay.style.display = isMenuOpen ? 'none' : 'block';
        }
    </script>
</body>
</html>

</body>
</html>