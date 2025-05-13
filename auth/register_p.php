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

    // Validate email uniqueness
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$checkEmail) {
        die("Prepare failed: " . $conn->error);
    }
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        die("Email is already registered.");
    }
    $checkEmail->close();

    // Validate password
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }
    if (!isStrongPassword($password)) {
        die("Password must contain at least one uppercase letter, one lowercase letter, one number, one symbol, and be at least 4 characters long.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password, phone_number, role) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $name, $surname, $email, $hashed_password, $phone_number, $role);

    if ($stmt->execute()) {
        // Registration successful
        header("Location: ../dashboard/user_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
