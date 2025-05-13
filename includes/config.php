<?php
$host = 'localhost';
$user = 'root'; // your DB username
$password = ''; // your DB password
$dbname = 'cts_courier'; // replace with your database name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
