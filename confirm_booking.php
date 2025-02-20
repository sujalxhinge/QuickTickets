<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_title = $_POST['movie_title'];
    $theater_name = $_POST['theater_name'];
    $language = $_POST['language'];
    $show_time = $_POST['show_time'];
    $duration = $_POST['duration'];
    $price = floatval($_POST['price']);
    $location = $_POST['location'];
    $booked_seats = intval($_POST['seats']);
    $total_price = $booked_seats * $price;

    // Fetch available seats
    $sql = "SELECT total_seats FROM movies WHERE title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $movie_title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: Movie not found.");
    }

    $row = $result->fetch_assoc();
    $current_seats = $row['total_seats'];

    if ($booked_seats > $current_seats) {
        die("Error: Not enough seats available.");
    }

    // Deduct booked seats
    $new_seat_count = $current_seats - $booked_seats;
    $update_sql = "UPDATE movies SET total_seats = ? WHERE title = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("is", $new_seat_count, $movie_title);
    $update_stmt->execute();

    // Insert into `booked_data` table
    $insert_sql = "INSERT INTO booked_data (event_name, theater_name, language, show_time, selected_seats, total_price, booking_date, duration, location) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssssidis", $movie_title, $theater_name, $language, $show_time, $booked_seats, $total_price, $duration, $location);
    $insert_stmt->execute();

    echo "Booking successful! Remaining seats: " . $new_seat_count . "<br>";
    echo "Total Price: ₹" . $total_price;
}

$conn->close();
?>
  