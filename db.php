<?php
// Database configuration
$host = "localhost";     // WAMP MySQL server
$dbname = "fashion";     // Your database name
$username = "root";      // Default WAMP MySQL username
$password = "2552";          //WAMP MySQL password is 2552

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully"; // Optional for testing
?>
