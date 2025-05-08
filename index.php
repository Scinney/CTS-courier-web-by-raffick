<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./authentication/login.php");
    exit();
}
$id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$role = $_SESSION['role'];

switch ($role) {
    case 'admin':
        header("Location: ./view/admin_home.php");
        break;
    case 'user':
        header("Location: ./view/customer_home.php");
        break;
    case 'employee':
        header("Location: ./view/employee_home.php");
        break;
    case 'manager':
        header("Location: ./view/manager_home.php");
        break;
    default:
        header("Location: ./404.php");
}
exit();
?>


