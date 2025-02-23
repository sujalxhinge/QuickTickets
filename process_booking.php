<?php
session_start(); // Start session for storing booking info

// Database Connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted properly
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selectedSeats"])) {
    
    $showtime_id = $_POST["showtime"];
    $venue_id = $_POST["venue"];
    $theater_id = $_POST["theater"];
    $selectedSeats = explode(",", $_POST["selectedSeats"]); // Convert seat string into array

    if (empty($selectedSeats)) {
        die("Error: No seats selected.");
    }

    // Fetch the movie_id or event_id from showtimes
    $query = "SELECT movie_id, event_id FROM showtimes WHERE showtime_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $showtime_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $showtimeData = $result->fetch_assoc();
    $stmt->close();

    if (!$showtimeData) {
        die("Error: Invalid showtime selected.");
    }

    // Determine the correct price based on whether it's a movie or an event
    if ($showtimeData['movie_id']) {
        $query = "SELECT price FROM movies WHERE movie_id = ?";
        $id = $showtimeData['movie_id'];
    } elseif ($showtimeData['event_id']) {
        $query = "SELECT price FROM events WHERE event_id = ?";
        $id = $showtimeData['event_id'];
    } else {
        die("Error: No valid movie or event found.");
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $priceData = $result->fetch_assoc();
    $stmt->close();

    if (!$priceData) {
        die("Error: Price not found.");
    }

    $pricePerSeat = $priceData['price'];
    $totalPrice = count($selectedSeats) * $pricePerSeat;

    // Check seat availability before booking
    $placeholders = implode(",", array_fill(0, count($selectedSeats), "?"));
    $seatCheckQuery = "SELECT seat_number FROM seats WHERE showtime_id = ? AND seat_number IN ($placeholders) AND is_booked = 1";
    $stmt = $conn->prepare($seatCheckQuery);
    $stmt->bind_param("i" . str_repeat("s", count($selectedSeats)), $showtime_id, ...$selectedSeats);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: Some selected seats are already booked!";
        exit();
    }
    $stmt->close();

    // Insert Booking into 'bookings' Table
    $user_id = 1; // Assuming user ID is available (Replace with session user ID)
    $bookingDate = date("Y-m-d H:i:s");
    $insertBooking = "INSERT INTO bookings (user_id, showtime_id, selected_seats, total_price, booking_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertBooking);
    $seatString = implode(",", $selectedSeats);
    $stmt->bind_param("iisds", $user_id, $showtime_id, $seatString, $totalPrice, $bookingDate);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    // Mark Selected Seats as Booked
    $updateSeatQuery = "UPDATE seats SET is_booked = 1 WHERE showtime_id = ? AND seat_number IN ($placeholders)";
    $stmt = $conn->prepare($updateSeatQuery);
    $stmt->bind_param("i" . str_repeat("s", count($selectedSeats)), $showtime_id, ...$selectedSeats);
    $stmt->execute();
    $stmt->close();

    // Store Booking Data in Session for Payment Processing
    $_SESSION['booking_id'] = $booking_id;
    $_SESSION['total_price'] = $totalPrice;
    $_SESSION['selected_seats'] = $seatString;

    // Redirect to Payment Page (Razorpay or any payment gateway)
    header("Location: payment.php");
    exit();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
