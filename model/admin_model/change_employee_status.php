<?php
if (isset($_GET['toggle_status'])) {
    $user_id = intval($_GET['toggle_status']);
    $status_query = $conn->query("SELECT status FROM users WHERE id = $user_id");
    $row = $status_query->fetch_assoc();
    
    $new_status = ($row['status'] == 'active') ? 'inactive' : 'active';
    $conn->query("UPDATE users SET status='$new_status' WHERE id=$user_id");

    header("Location: manage_users.php");
}
?>

