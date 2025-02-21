<?php
session_start(); // Start session to access logged-in user data

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    die("You must be logged in to book tickets.");
}

$user_id = $_SESSION["user_id"];

// Get movie/event title and category from URL
if (isset($_GET['title']) && isset($_GET['category'])) {
    $title = urldecode($_GET['title']);
    $category = urldecode($_GET['category']); // Fetch category from URL

    // Validate category
    $valid_categories = ["movies", "shows", "concerts", "events", "sports", "standup_comedy"];
    if (!in_array($category, $valid_categories)) {
        die("Invalid category.");
    }

    // Fetch movie/event details dynamically from the correct table
    $sql = "SELECT title, image, total_seats, theater, language, time AS show_time, duration, price, location, date 
            FROM $category WHERE title = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        die("Event not found.");
    }
} else {
    die("No event selected.");
}

// Handle seat booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seats_requested = intval($_POST["seats"]);
    $total_price = $seats_requested * $event["price"];

    if ($seats_requested > 0 && $seats_requested <= $event["total_seats"]) {
        $remaining_seats = $event["total_seats"] - $seats_requested;

        // Update remaining seats in correct table
        $update_sql = "UPDATE $category SET total_seats = ? WHERE title = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $remaining_seats, $title);
        $update_stmt->execute();

        // Insert booking details into `booked_data` table
        $insert_sql = "INSERT INTO booked_data (user_id, username, event_name, theater, language, show_time, selected_seats, total_price, duration, location, category, booking_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issssiissss", $user_id, $_SESSION["username"], $event["title"], $event["theater"], $event["language"], $event["show_time"], $seats_requested, $total_price, $event["duration"], $event["location"], $category);

        if ($insert_stmt->execute()) {
            echo "<p style='color:green;'>Booking successful! Remaining seats: " . $remaining_seats . "</p>";
            header("Refresh:2; url=checkout.php?title=" . urlencode($title) . "&category=" . urlencode($category));
            exit();
        } else {
            echo "<p style='color:red;'>Booking failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid number of seats.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script>
        function updateTotalPrice() {
            let seatCount = document.getElementById("seats").value;
            let pricePerSeat = <?php echo $event["price"]; ?>;
            let totalPrice = seatCount * pricePerSeat;
            document.getElementById("totalPrice").innerText = totalPrice ? "₹" + totalPrice : "₹0";
        }
    </script>
</head>
<body>

<h2>Checkout for <?php echo htmlspecialchars($event["title"]); ?></h2>
<img src="<?php echo htmlspecialchars($event["image"]); ?>" width="200">

<p><strong>Theater:</strong> <?php echo htmlspecialchars($event["theater"]); ?></p>
<p><strong>Language:</strong> <?php echo htmlspecialchars($event["language"]); ?></p>
<p><strong>Show Time:</strong> <?php echo htmlspecialchars($event["show_time"]); ?></p>
<p><strong>Date:</strong> <?php echo htmlspecialchars($event["date"]); ?></p>
<p><strong>Duration:</strong> <?php echo htmlspecialchars($event["duration"]); ?> mins</p>
<p><strong>Price per Seat:</strong> ₹<?php echo htmlspecialchars($event["price"]); ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($event["location"]); ?></p>
<p><strong>Available Seats:</strong> <?php echo htmlspecialchars($event["total_seats"]); ?></p>

<form method="POST">
    <label for="seats">Number of Seats:</label>
    <input type="number" id="seats" name="seats" min="1" max="<?php echo $event["total_seats"]; ?>" required oninput="updateTotalPrice()">
    
    <p><strong>Total Price:</strong> <span id="totalPrice">₹0</span></p>
    
    <button type="submit">Proceed to Payment</button>
</form>

</body>
</html>
