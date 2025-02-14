<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "quicktickets"; // Change to your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$role = $_POST['role'];
$review = $_POST['review'];

// Insert data into database
$sql = "INSERT INTO reviews (name, role, review) VALUES ('$name', '$role', '$review')";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Review submitted successfully!'); window.location.href='splash.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
