<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $conn->query("INSERT INTO users (first_name, email, role, password) VALUES ('$name', '$email', 'employee', '$password')");
    header("Location: manage_users.php");
}
?>
