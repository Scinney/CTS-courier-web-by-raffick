<?php
include '../db/connection.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $id           = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $first_name   = trim($_POST['first_name']);
    $second_name  = trim($_POST['second_name']); // This maps to 'last_name' in the DB
    $email        = trim($_POST['email']);
    $role         = trim($_POST['role']);
    $password     = isset($_POST['password']) ? $_POST['password'] : null;

    // Validation (basic)
    if (empty($first_name) || empty($second_name) || empty($email) || empty($role)) {
        die("Missing required fields.");
    }

    // If it's a new user
    if (empty($id)) {
        if (empty($password)) {
            die("Password is required for new users.");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users2 (first_name, last_name, email, role, password, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("sssss", $first_name, $second_name, $email, $role, $hashed_password);
    } else {
        // Update user — password is optional
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users2 SET first_name=?, last_name=?, email=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("sssssi", $first_name, $second_name, $email, $role, $hashed_password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users2 SET first_name=?, last_name=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $first_name, $second_name, $email, $role, $id);
        }
    }

    if ($stmt->execute()) {
        header("Location: ../admin_home.php?section=users&status=success");
        exit();
    } else {
        echo "Database error: " . $stmt->error;
    }
} else {
    header("Location: ../404.php");
    exit();
}
?>
