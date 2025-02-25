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
    <link rel="stylesheet" href="booking.css">

</head>
<body>
<!-- <div class="details-container">
    <h1><?php echo htmlspecialchars($row["title"]); ?></h1>
    <img src="uploads/<?php echo htmlspecialchars($row["image"] ?? "default.jpg"); ?>" width="200">
   
    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row["price"]); ?></p>

    <?php if ($type == "movie") { ?>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($row["duration"]); ?> mins</p>
        <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/10</p>
        <p><strong>Language:</strong> <?php echo htmlspecialchars($row["language"]); ?></p>
        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($row["release_date"]); ?></p>
    <?php } else { ?>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($row["description"]); ?></p>
        <p><strong>Event Date:</strong> <?php echo htmlspecialchars($row["date"]); ?></p>
        <p><strong>Event Time:</strong> <?php echo htmlspecialchars($row["time"]); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($row["location"]); ?></p>
        <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($row["available_seats"]); ?> / <?php echo htmlspecialchars($row["total_seats"]); ?></p>
        <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/10</p>
    <?php } ?>
</div> -->
    
<div class="details-container">
    <h1><?php echo htmlspecialchars($row["title"]); ?></h1>

    <!-- Image Centered -->
    <div class="image-container">
        <img src="uploads/<?php echo htmlspecialchars($row["image"] ?? "default.jpg"); ?>" alt="Image">
    </div>

    <!-- Grid-Based Details Section -->
    <div class="details-content">
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row["price"]); ?></p>

        <?php if ($type == "movie") { ?>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($row["duration"]); ?> mins</p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/10</p>
            <p><strong>Language:</strong> <?php echo htmlspecialchars($row["language"]); ?></p>
            <p><strong>Release Date:</strong> <?php echo htmlspecialchars($row["release_date"]); ?></p>
        <?php } else { ?>
            <p><strong>Event Date:</strong> <?php echo htmlspecialchars($row["date"]); ?></p>
            <p><strong>Event Time:</strong> <?php echo htmlspecialchars($row["time"]); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row["location"]); ?></p>
            <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($row["available_seats"]); ?> / <?php echo htmlspecialchars($row["total_seats"]); ?></p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($row["rating"]); ?>/5</p>
        <?php } ?>
    </div>
</div>

    <form id="select-con" action="payment.php" method="GET">
    <div class="select-item">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">

        
        <label>Select Showtime:</label>
        <select name="showtime" required>
            <?php while ($show = $showtimeResult->fetch_assoc()) {
                echo "<option value='{$show['showtime_id']}'>Date: {$show['show_date']} - Time: {$show['show_time']}</option>";
            } ?>
        </select>
        </div>
        <div class="select-item">
        <label>Select Venue:</label>
        <select name="venue" required>
            <?php while ($venue = $venueResult->fetch_assoc()) {
                echo "<option value='{$venue['venue_id']}'>{$venue['venue_name']}</option>";
            } ?>
        </select>
        </div>
        <div class="select-item">
        <label>Select Theater:</label>
        <select name="theater" required>
            <?php while ($theater = $theaterResult->fetch_assoc()) {
                echo "<option value='{$theater['theater_id']}'>{$theater['theater_name']}</option>";
            } ?>
        </select>
        </div>
        <!-- <label>Select Seats:</label>
        <div id="seatContainer">
            <?php for ($i = 1; $i <= 100; $i++) {
                echo "<input type='checkbox' name='seats[]' value='$i' class='seat'> S $i ";
            } ?>
        </div> -->
        
        <div id="seatContainer">
    <?php for ($i = 1; $i <= 100; $i++) { ?>
        <input type="checkbox" id="seat<?php echo $i; ?>" name="seats[]" value="<?php echo $i; ?>" class="seat">
        <label for="seat<?php echo $i; ?>">S <?php echo $i; ?></label>
    <?php } ?>
</div>

        
        <!-- <p>Total Price: <span id="totalPrice">0</span></p>
        <button type="submit">Proceed to Payment</button> -->
        <div class="price-container">
        <p>Total Price: <span id="totalPrice">0</span></p>
        <!-- <button type="submit">Proceed to Payment</button> -->
        
<button type="submit" class="pay-btn">
  <span class="btn-text">Proceed to Payment</span>
  <div class="icon-container">
    <svg viewBox="0 0 24 24" class="icon card-icon">
      <path
        d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18C2,19.11 2.89,20 4,20H20C21.11,20 22,19.11 22,18V6C22,4.89 21.11,4 20,4Z"
        fill="currentColor"
      ></path>
    </svg>
    <svg viewBox="0 0 24 24" class="icon payment-icon">
      <path
        d="M2,17H22V21H2V17M6.25,7H9V6H6V3H18V6H15V7H17.75L19,17H5L6.25,7M9,10H15V8H9V10M9,13H15V11H9V13Z"
        fill="currentColor"
      ></path>
    </svg>
    <svg viewBox="0 0 24 24" class="icon dollar-icon">
      <path
        d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"
        fill="currentColor"
      ></path>
    </svg>

    <svg viewBox="0 0 24 24" class="icon wallet-icon default-icon">
      <path
        d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
        fill="currentColor"
      ></path>
    </svg>

    <svg viewBox="0 0 24 24" class="icon check-icon">
      <path
        d="M9,16.17L4.83,12L3.41,13.41L9,19L21,7L19.59,5.59L9,16.17Z"
        fill="currentColor"
      ></path>
    </svg>
  </div>
</button>

</div>

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