<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../404.php");
    exit();
}
$first_name = $_SESSION['first_name'];
$role = $_SESSION['role'];
//  section routing
$section = $_GET['section'] ?? 'dashboard';
$allowed_sections = ['dashboard', 'users', 'parcels', 'branches', 'delivery', 'reports', 'settings'];
$current_section = in_array($section, $allowed_sections) ? $section : 'dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CTS Admin Panel</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background-color: #2b38f0;
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
        }
        .sidebar .profile {
            text-align: center;
            padding: 10px 20px;
            border-bottom: 1px solid #262ea3;
        }
        .sidebar .profile h3 {
            margin: 0;
        }
        .sidebar a {
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            display: block;
            transition: background 0.2s;
            font-weight: bold;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #262ea3;
        }
        .sidebar .watermark {
            margin-top: auto;
            padding: 20px;
            font-size: 12px;
            text-align: center;
            opacity: 0.6;
        }
        .main-content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }
        .card {
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php include "sections/{$current_section}.php"; ?>
</div>

</body>
</html>
