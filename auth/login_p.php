<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement
    $stmt = $connection->prepare("SELECT id, name AS first_name, role, password, status FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $connection->error;
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    $stmt->bind_result($id, $first_name, $role, $hashed_password, $status);
    $stmt->fetch();

    // Check if user is suspended or deleted
    if ($status === 'suspended') {
        header("Location: suspended.php");
        exit();
    }
    if ($status === 'deleted') {
        header("Location: deleted.php");
        exit();
    }

    if (!password_verify($password, $hashed_password)) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    // Set session variables
    $_SESSION['user_id'] = $id;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['role'] = $role;

    // Redirect by role
    switch ($role) {
        case 'admin':
            header("Location: ../pages/admin/index.php");
            break;
        case 'branch-admin':
            header("Location: ../dashboard/branch_admin_dashboard.php");
            break;
        case 'receptionist':
            header("Location: ../dashboard/receptionist_dashboard.php");
            break;
        case 'driver':
            header("Location: ../dashboard/driver_dashboard.php");
            break;
        case 'user':
        default:
            header("Location: ../pages/roles/user/index.php");
            break;
    }
    exit();
}
?>