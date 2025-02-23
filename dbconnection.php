<?php
$host = "localhost";  // Your database host (default: localhost)
$username = "root";   // Your database username
$password = "";       // Your database password (default: empty for WAMP/XAMPP)
$database = "quicktickets";  // Your database name

// Create a connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
