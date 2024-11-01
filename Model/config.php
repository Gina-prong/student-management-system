<?php
// Define database credentials
$db_host = 'localhost';
$db_user = 'root'; // Replace with your actual username
$db_pass = ''; // Replace with your actual password
$db_name = 'compssa_system';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>