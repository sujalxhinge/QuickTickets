<?php
session_start();

include 'dbconnection.php';
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['movie_id'])) {
    $id = (int)$_GET['movie_id'];
    $type = "movie";
    $sql = "SELECT * FROM movies WHERE movie_id = ?";
} elseif (isset($_GET['event_id'])) {
    $id = (int)$_GET['event_id'];
    $type = "event";
    $sql = "SELECT * FROM events WHERE event_id = ?";
} else {
    die("Invalid request.");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("No movie or event found.");
}
$row = $result->fetch_assoc();

$showtimeQuery = "SELECT showtime_id, show_date, show_time FROM showtimes WHERE " . ($type == "movie" ? "movie_id" : "event_id") . " = ?";
$showtimeStmt = $conn->prepare($showtimeQuery);
$showtimeStmt->bind_param("i", $id);
$showtimeStmt->execute();
$showtimeResult = $showtimeStmt->get_result();

$venueQuery = "SELECT * FROM venues";
$venueResult = $conn->query($venueQuery);
$theaterQuery = "SELECT * FROM theaters";
$theaterResult = $conn->query($theaterQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - <?php echo htmlspecialchars($row["title"]); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($row["title"]); ?></h1>
    <img src="uploads/<?php echo htmlspecialchars($row["image"] ?? "default.jpg"); ?>" width="200">
    <h2>Details</h2>
    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row["price"]); ?></p>
    
    <form action="payment.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">

        
        <label>Select Showtime:</label>
        <select name="showtime" required>
            <?php while ($show = $showtimeResult->fetch_assoc()) {
                echo "<option value='{$show['showtime_id']}'>Date: {$show['show_date']} - Time: {$show['show_time']}</option>";
            } ?>
        </select>
        
        <label>Select Venue:</label>
        <select name="venue" required>
            <?php while ($venue = $venueResult->fetch_assoc()) {
                echo "<option value='{$venue['venue_id']}'>{$venue['venue_name']}</option>";
            } ?>
        </select>
        
        <label>Select Theater:</label>
        <select name="theater" required>
            <?php while ($theater = $theaterResult->fetch_assoc()) {
                echo "<option value='{$theater['theater_id']}'>{$theater['theater_name']}</option>";
            } ?>
        </select>
        
        <label>Select Seats:</label>
        <div id="seatContainer">
            <?php for ($i = 1; $i <= 100; $i++) {
                echo "<input type='checkbox' name='seats[]' value='$i' class='seat'> Seat $i ";
            } ?>
        </div>
        
        <p>Total Price: <span id="totalPrice">0</span></p>
        <button type="submit">Proceed to Payment</button>
    </form>
</body>
</html>

<script>
    document.querySelectorAll('.seat').forEach(seat => {
        seat.addEventListener('change', function() {
            let total = 0;
            document.querySelectorAll('.seat:checked').forEach(selected => {
                total += <?php echo $row['price']; ?>;
            });
            document.getElementById('totalPrice').textContent = total;
        });
    });
</script>