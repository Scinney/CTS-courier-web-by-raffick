<?php
session_start();
require 'conn.php';

// Function to validate password strength
function isStrongPassword($password) {
    return preg_match('/[A-Z]/', $password) &&   // At least one uppercase letter
           preg_match('/[a-z]/', $password) &&   // At least one lowercase letter
           preg_match('/\d/', $password) &&      // At least one number
           preg_match('/[^a-zA-Z\d]/', $password) && // At least one special character
           strlen($password) >= 4;               // At least 4 characters
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $second_name = $_POST['second_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Validate email uniqueness
    $checkEmail = $conn->prepare("SELECT email FROM users2 WHERE email = ?");
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
    $stmt = $conn->prepare("INSERT INTO users2 (first_name, second_name, email, gender, role,  password, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $first_name, $second_name, $email, $gender, $role, $hashed_password, $phone_number, $address);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Insert user data into relevant role-based table
        if ($role == 'manager') {
            $conn->query("INSERT INTO manager (user_id, email) VALUES ('$user_id', '$email')");
        } elseif ($role == 'employee') {
            $conn->query("INSERT INTO employee (user_id, email) VALUES ('$user_id', '$email')");
        } else {
            $conn->query("INSERT INTO customer (user_id, email) VALUES ('$user_id', '$email')");
        }
        
        
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
