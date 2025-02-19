<?php
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";  // Change if necessary
$dbname = "quicktickets";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = strtolower(str_replace("-", "_", $_POST['category'])); // Convert category to table name
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $language = $_POST['language'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $location = $_POST['location'];
    $theater = $_POST['theater'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $total_seats = $_POST['total_seats'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Handle file upload
    $image = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    if (isset($_POST['add'])) {
        // Insert Data
        $sql = "INSERT INTO $category (title, duration, language, price, rating, location, theater, time, image, date, total_seats) 
                VALUES ('$title', '$duration', '$language', '$price', '$rating', '$location', '$theater', '$time', '$image', '$date', '$total_seats')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New record added successfully!'); window.location.href=document.referrer;</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href=document.referrer;</script>";
        }
    } elseif (isset($_POST['update']) && !empty($id)) {
        // Update Data
        $sql = "UPDATE $category SET 
                title='$title', duration='$duration', language='$language', price='$price', rating='$rating', 
                location='$location', theater='$theater', time='$time', image='$image', date='$date', total_seats='$total_seats' 
                WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record updated successfully!'); window.location.href=document.referrer;</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href=document.referrer;</script>";
        }
    } elseif (isset($_POST['delete']) && !empty($id)) {
        // Delete Data
        $sql = "DELETE FROM $category WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record deleted successfully!'); window.location.href=document.referrer;</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href=document.referrer;</script>";
        }
    } else {
        echo "<script>alert('Invalid request!'); window.location.href=document.referrer;</script>";
    }
}

$conn->close();
?>
