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

// File size constraint
define("MAX_FILE_SIZE", 5 * 1024 * 1024); // 5 MB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_type = $_POST['event_type'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $language = $_POST['language'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $cast = $_POST['location'];

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_size = $_FILES['photo']['size'];
        $file_name = basename($_FILES['photo']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if ($file_size > MAX_FILE_SIZE) {
            die("File size exceeds the 5 MB limit.");
        }

        if (!in_array($file_ext, $allowed_types)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . uniqid() . "." . $file_ext;

        if (move_uploaded_file($file_tmp, $file_path)) {
            echo "File uploaded successfully.<br>";
        } else {
            die("Failed to upload file.");
        }
    } else {
        die("No file uploaded or an error occurred.");
    }

    // Insert data into the respective table
    $sql = "INSERT INTO $event_type (event_type, description, duration, language, price, rating, location, photo_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisdsss", $event_type, $description, $duration, $language, $price, $rating, $location, $file_path);

    if ($stmt->execute()) {
        echo "Event details saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
