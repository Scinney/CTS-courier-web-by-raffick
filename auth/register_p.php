<?php
session_start();
require_once '../includes/config.php';

// Function to validate password strength
function isStrongPassword($password) {
    return preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/\d/', $password) &&
           preg_match('/[^a-zA-Z\d]/', $password) &&
           strlen($password) >= 4;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user'; // Default role

    // Validate inputs
    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All required fields must be filled.";
        header("Location: register.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    // Validate email uniqueness
    $checkEmail = $connection->prepare("SELECT email FROM users WHERE email = ?");
    if (!$checkEmail) {
        $_SESSION['error'] = "Database error: " . $connection->error;
        header("Location: register.php");
        exit();
    }
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        header("Location: email_in_use.php");
        exit();
    }
    $checkEmail->close();

    // Validate password
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }
    if (!isStrongPassword($password)) {
        $_SESSION['error'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, one symbol, and be at least 4 characters long.";
        header("Location: register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into users table
    $stmt = $connection->prepare("INSERT INTO users (name, surname, email, password, phone_number, role, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $connection->error;
        header("Location: register.php");
        exit();
    }
    $stmt->bind_param("ssssss", $name, $surname, $email, $hashed_password, $phone_number, $role);

    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['first_name'] = $name;
        $_SESSION['role'] = $role;
        header("Location: ../pages/roles/user/index.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . $stmt->error;
        header("Location: register.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>