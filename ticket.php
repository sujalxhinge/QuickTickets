<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quicktickets");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['booking_id'])) {
    die("Invalid request!");
}

$booking_id = $_GET['booking_id'];
$query = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - QuickTickets</title>
</head>
<body>
    <h2>🎟 Your Ticket</h2>
    <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
    <p><strong>Showtime ID:</strong> <?php echo $booking['showtime_id']; ?></p>
    <p><strong>Seats:</strong> <?php echo $booking['selected_seats']; ?></p>
    <p><strong>Total Price:</strong> ₹<?php echo $booking['total_price']; ?></p>
    <p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
    <p><strong>Payment Status:</strong> ✅ Paid</p>
    <button onclick="window.print()">Print Ticket</button>
</body>
</html>
