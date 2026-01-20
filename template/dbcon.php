<?php
// Database configuration
$server = "localhost";
$user = "root";
$password = "2552";
$db = "fashion";

// Turn off error reporting for production, log errors instead
error_reporting(0);

// Create connection
$con = mysqli_connect($server, $user, $password, $db);

// Check connection
if (!$con) {
    // Log error instead of showing to user
    error_log("Database connection failed: " . mysqli_connect_error());
    
    // Show user-friendly error message
    die("Database connection error. Please try again later.");
}

// Set charset to prevent issues
mysqli_set_charset($con, "utf8mb4");

// Set timezone if needed
// date_default_timezone_set('Your/Timezone');

// Optional: Enable mysqli error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>