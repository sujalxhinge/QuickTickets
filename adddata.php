<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "QuickTickets"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function showAlert($message) {
    echo "<script>alert('$message'); window.history.back();</script>";
    exit();
}

if (isset($_POST['add'])) {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $language = $_POST['language'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $location = $_POST['location'];
    $theater = $_POST['theater'];
    $time = $_POST['time'];
    $date = $_POST['date']; // New date input
    $total_seats = $_POST['total_seats']; // New total seats input

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    if ($_FILES['image']['size'] < 5 * 1024 * 1024) {
        move_uploaded_file($image_tmp, "uploads/" . $image);
        $sql = "INSERT INTO categories (category, title, duration, language, price, rating, location, theater, time, date, total_seats, image) 
                VALUES ('$category', '$title', '$duration', '$language', '$price', '$rating', '$location', '$theater', '$time', '$date', '$total_seats', '$image')";
        
        if ($conn->query($sql) === TRUE) {
            showAlert("New category added successfully.");
        } else {
            showAlert("Error: " . $conn->error);
        }
    } else {
        showAlert("File size must be less than 5MB.");
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $language = $_POST['language'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $location = $_POST['location'];
    $theater = $_POST['theater'];
    $time = $_POST['time'];
    $date = $_POST['date']; // New date input
    $total_seats = $_POST['total_seats']; // New total seats input

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    if (!empty($image)) {
        if ($_FILES['image']['size'] < 5 * 1024 * 1024) {
            move_uploaded_file($image_tmp, "uploads/" . $image);
            $sql = "UPDATE categories SET category='$category', title='$title', duration='$duration', language='$language', 
                    price='$price', rating='$rating', location='$location', theater='$theater', time='$time', date='$date', total_seats='$total_seats', image='$image' WHERE id='$id'";
        } else {
            showAlert("File size must be less than 5MB.");
        }
    } else {
        $sql = "UPDATE categories SET category='$category', title='$title', duration='$duration', language='$language', 
                price='$price', rating='$rating', location='$location', theater='$theater', time='$time', date='$date', total_seats='$total_seats' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        showAlert("Category updated successfully.");
    } else {
        showAlert("Error: " . $conn->error);
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM categories WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        showAlert("Category deleted successfully.");
    } else {
        showAlert("Error: " . $conn->error);
    }
}

$conn->close();
?>
