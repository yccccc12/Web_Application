<?php
// Database configuration
$servername = "localhost";
$username = "root";  // Change this if needed
$password = "";       // Set your database password
$database = "tub_db";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 encoding for proper text handling
$conn->set_charset("utf8");
?>
