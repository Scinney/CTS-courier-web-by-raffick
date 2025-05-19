<?php
// Include database connection
include '../db/connection.php';

// Ensure connection is valid
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate and sanitize form inputs
$errors = [];
$user_id = !empty($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_VALIDATE_INT) : null;
$name = trim($_POST['name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone_number = trim($_POST['phone_number'] ?? '');
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';

// Valid roles from the database enum
$valid_roles = ['user', 'admin', 'branch-admin', 'receptionist', 'driver'];

// Input validation
if (empty($name)) {
    $errors[] = "First name is required";
}
if (empty($surname)) {
    $errors[] = "Last name is required";
}
if (!$email) {
    $errors[] = "Valid email is required";
}
if (!in_array($role, $valid_roles)) {
    $errors[] = "Invalid role selected";
}
if ($user_id === null && empty($password)) {
    $errors[] = "Password is required for new users";
}
if (!empty($phone_number) && !preg_match('/^\+?[1-9]\d{1,14}$/', $phone_number)) {
    $errors[] = "Invalid phone number format";
}

// Check if email is already in use (excluding the current user if editing)
if ($email) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param('si', $email, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email is already in use";
    }
    $stmt->close();
}

// If there are errors, redirect back with error messages
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    header("Location: ../index.php?section=users");
    exit;
}

// Prepare SQL based on whether it's an insert or update
if ($user_id) {
    // Update existing user
    if (empty($password)) {
        // Update without changing password
        $stmt = $connection->prepare("UPDATE users SET name = ?, surname = ?, email = ?, phone_number = ?, role = ? WHERE id = ?");
        $stmt->bind_param('sssssi', $name, $surname, $email, $phone_number, $role, $user_id);
    } else {
        // Update with new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connection->prepare("UPDATE users SET name = ?, surname = ?, email = ?, phone_number = ?, role = ?, password = ? WHERE id = ?");
        $stmt->bind_param('ssssssi', $name, $surname, $email, $phone_number, $role, $hashed_password, $user_id);
    }
} else {
    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $connection->prepare("INSERT INTO users (name, surname, email, phone_number, role, password, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param('ssssss', $name, $surname, $email, $phone_number, $role, $hashed_password);
}

// Execute the query
if ($stmt->execute()) {
    session_start();
    $_SESSION['success'] = $user_id ? "User updated successfully" : "User added successfully";
} else {
    session_start();
    $_SESSION['errors'] = ["Database error: " . $stmt->error];
}

$stmt->close();
$connection->close();

// Redirect back to the users page
header("Location: ../index.php?section=users");
exit;
?>