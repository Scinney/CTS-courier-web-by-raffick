<?php
// config/connection.php
$host = '127.0.0.1';
$dbname = 'cts_courier';
$username = 'root';
$password = '';

$connection = new mysqli($host, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>