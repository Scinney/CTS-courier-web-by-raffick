<?php
// Database connection parameters
$host = 'localhost';  
$username = 'root';   // your database username
$password = '';       
$dbname = 'cts';      

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
