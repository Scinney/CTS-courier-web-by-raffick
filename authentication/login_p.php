<?php
session_start();
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, first_name, role, password FROM users2 WHERE email = ?");
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

    // Redirect to respective role page
    header("Location: ../index.php");
}
?>
