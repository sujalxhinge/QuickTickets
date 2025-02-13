<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quicktickets";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    $event_type = isset($_POST['event_type']) ? trim($_POST['event_type']) : "";
    $description = isset($_POST['description']) ? trim($_POST['description']) : "";
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;
    $language = isset($_POST['language']) ? trim($_POST['language']) : "";
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
    $rating = isset($_POST['rating']) ? trim($_POST['rating']) : "";
    $location = isset($_POST['location']) ? trim($_POST['location']) : "";
    $theater_name = isset($_POST['theater_name']) ? trim($_POST['theater_name']) : "";
    $movie_time = isset($_POST['movie_time']) ? trim($_POST['movie_time']) : "";

    // Check if required fields are empty
    if (empty($event_type) || empty($description) || empty($language) || empty($price) || empty($rating) || empty($location) || empty($theater_name) || empty($movie_time)) {
        die("All fields are required.");
    }

    // Insert data into the respective table
    $sql = "INSERT INTO $event_type (event_type, description, duration, language, price, rating, location, theater_name, movie_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssisdssss", $event_type, $description, $duration, $language, $price, $rating, $location, $theater_name, $movie_time);

    if ($stmt->execute()) {
        // After successful data insert, show the popup
        echo "<script type='text/javascript'>alert('Your Data saved successfully. We will assist you in a shorter time.');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
