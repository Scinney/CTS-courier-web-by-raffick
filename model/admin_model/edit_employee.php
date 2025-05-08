<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $name = $_POST['name'];
    $email = $_POST['email'];

    $conn->query("UPDATE users SET first_name='$name', email='$email' WHERE id=$edit_id");
    header("Location: manage_users.php");
}
?>
