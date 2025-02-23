<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate movie_id or event_id
if (isset($_GET['movie_id'])) {
    $id = (int)$_GET['movie_id'];
    $type = "movie";
    $sql = "SELECT * FROM movies WHERE movie_id = ?";
} elseif (isset($_GET['event_id'])) {
    $id = (int)$_GET['event_id'];
    $type = "event";
    $sql = "SELECT * FROM events WHERE event_id = ?";
} else {
    die("Invalid request. Please select a movie or event properly.");
}

// Fetch movie/event details
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid request. No movie or event found.");
}
$row = $result->fetch_assoc();

// Fetch available showtimes
$showtimeQuery = "SELECT showtime_id, show_date, show_time FROM showtimes WHERE " . ($type == "movie" ? "movie_id" : "event_id") . " = ?";
$showtimeStmt = $conn->prepare($showtimeQuery);
$showtimeStmt->bind_param("i", $id);
$showtimeStmt->execute();
$showtimeResult = $showtimeStmt->get_result();

// Fetch venues & theaters
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
    <?php if ($type == "movie") { ?>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($row["duration"]); ?> minutes</p>
        <p><strong>Language:</strong> <?php echo htmlspecialchars($row["language"]); ?></p>
        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($row["release_date"]); ?></p>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row["price"]); ?></p>
        <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/10</p>
    <?php } else { ?>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($row["date"]); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($row["time"]); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($row["location"]); ?></p>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row["price"]); ?></p>
        <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/10</p>
    <?php } ?>

    <form action="seat_selection.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">

        <label for="showtime">Select Showtime:</label>
<select name="showtime" required>
    <?php 
    if ($showtimeResult->num_rows > 0) {
        while ($show = $showtimeResult->fetch_assoc()) {
            // Use dummy time if show_date or show_time is missing
            $date = !empty($show['show_date']) ? $show['show_date'] : "TBA";
            $time = !empty($show['show_time']) ? $show['show_time'] : "To Be Announced";
            
            echo "<option value='{$show['showtime_id']}'>Date: $date - Time: $time</option>";
        }
    } else {
        echo "<option value='' disabled>No showtimes available here</option>";
    }
    ?>
</select>


        <label for="venue">Select Venue:</label>
        <select name="venue" required>
            <?php while ($venue = $venueResult->fetch_assoc()) {
                echo "<option value='{$venue['venue_id']}'>{$venue['venue_name']} - {$venue['location']}</option>";
            } ?>
        </select>

        <label for="theater">Select Theater:</label>
        <select name="theater" required>
            <?php while ($theater = $theaterResult->fetch_assoc()) {
                echo "<option value='{$theater['theater_id']}'>{$theater['theater_name']}</option>";
            } ?>
        </select>

        <button type="submit">Select Seats</button>
    </form>
</body>
</html>

<?php
$stmt->close();
$showtimeStmt->close();
$conn->close();
?>
