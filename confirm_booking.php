<?php
session_start(); // Start the session to track user data

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    die("You must be logged in to confirm a booking.");
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Validate booking data
if (isset($_POST['title'], $_POST['category'], $_POST['seats'], $_POST['total_price'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $seats_requested = intval($_POST['seats']);
    $total_price = floatval($_POST['total_price']);

    // Validate category
    $valid_categories = ["movies", "shows", "concerts", "events", "sports", "standup_comedy"];
    if (!in_array($category, $valid_categories)) {
        die("Invalid category selected.");
    }

    // Fetch event details to verify seat availability
    $sql = "SELECT total_seats, theater, language, time AS show_time, duration, location, date 
            FROM $category WHERE title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        die("Event not found.");
    }

    if ($seats_requested > 0 && $seats_requested <= $event["total_seats"]) {
        $remaining_seats = $event["total_seats"] - $seats_requested;

        // Update remaining seats in the correct table
        $update_sql = "UPDATE $category SET total_seats = ? WHERE title = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $remaining_seats, $title);
        $update_stmt->execute();

        // Insert booking details into `booked_data` table
        $insert_sql = "INSERT INTO booked_data (user_id, username, event_name, theater, language, show_time, selected_seats, total_price, duration, location, category, booking_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issssiissss", $user_id, $username, $title, $event["theater"], $event["language"], $event["show_time"], $seats_requested, $total_price, $event["duration"], $event["location"], $category);

        if ($insert_stmt->execute()) {
            echo "<p style='color:green;'>Booking confirmed successfully!</p>";
            echo "<p><a href='dashboard.php'>Go back to Dashboard</a></p>";
        } else {
            echo "<p style='color:red;'>Booking failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid number of seats.</p>";
    }
} else {
    echo "<p style='color:red;'>Incomplete booking details.</p>";
}

$conn->close();
?>