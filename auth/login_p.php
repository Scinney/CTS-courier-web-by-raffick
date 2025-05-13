<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to fetch user info
    $stmt = $conn->prepare("SELECT id, first_name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("Invalid email or password.");
    }

    $stmt->bind_result($id, $first_name, $role, $hashed_password);
    $stmt->fetch();

    // Verify password
    if (!password_verify($password, $hashed_password)) {
        die("Invalid email or password.");
    }

    // Set session variables
    $_SESSION['user_id'] = $id;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['role'] = $role;

    // Redirect to respective dashboard based on role
    switch ($role) {
        case 'admin':
            header("Location: ../dashboard/admin_dashboard.php");
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
            header("Location: ../dashboard/user_dashboard.php");
            break;
    }
    exit();
}
?>
