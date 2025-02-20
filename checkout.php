<?php
session_start(); // Start session to access logged-in user data

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    die("You must be logged in to book tickets.");
}

$user_id = $_SESSION["user_id"];

// Fetch username from users table
$sql_user = "SELECT username FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

if (!$user_data) {
    die("User not found.");
}

$username = $user_data["username"];

// Get movie title from URL
if (isset($_GET['title'])) {
    $title = urldecode($_GET['title']);

    // Fetch movie details
    $sql = "SELECT title, image, total_seats, theater, language, time AS show_time, duration, price, location, date 
            FROM movies WHERE title = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();

    if (!$movie) {
        die("Movie not found.");
    }
} else {
    die("No movie selected.");
}

// Handle seat booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seats_requested = intval($_POST["seats"]);
    $total_price = $seats_requested * $movie["price"];

    if ($seats_requested > 0 && $seats_requested <= $movie["total_seats"]) {
        $remaining_seats = $movie["total_seats"] - $seats_requested;

        // Update remaining seats in movies table
        $update_sql = "UPDATE movies SET total_seats = ? WHERE title = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $remaining_seats, $title);
        $update_stmt->execute();

        // Insert booking details into booked_data table
        $insert_sql = "INSERT INTO booked_data (user_id, username, event_name, theater, language, show_time, selected_seats, total_price, duration, location, booking_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issssiisss", $user_id, $username, $movie["title"], $movie["theater"], $movie["language"], $movie["show_time"], $seats_requested, $total_price, $movie["duration"], $movie["location"]);

        if ($insert_stmt->execute()) {
            echo "<p style='color:green;'>Booking successful! Remaining seats: " . $remaining_seats . "</p>";
            header("Refresh:2; url=checkout.php?title=" . urlencode($title));
            exit();
        } else {
            echo "<p style='color:red;'>Booking failed: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid number of seats.</p>";
    }
}

// Close connection
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
            let pricePerSeat = <?php echo $movie["price"]; ?>;
            let totalPrice = seatCount * pricePerSeat;
            document.getElementById("totalPrice").innerText = totalPrice ? "₹" + totalPrice : "₹0";
        }
    </script>
</head>
<body>

<h2>Checkout for <?php echo htmlspecialchars($movie["title"]); ?></h2>
<img src="<?php echo htmlspecialchars($movie["image"]); ?>" width="200">

<p><strong>Theater:</strong> <?php echo htmlspecialchars($movie["theater"]); ?></p>
<p><strong>Language:</strong> <?php echo htmlspecialchars($movie["language"]); ?></p>
<p><strong>Show Time:</strong> <?php echo htmlspecialchars($movie["show_time"]); ?></p>
<p><strong>Date:</strong> <?php echo htmlspecialchars($movie["date"]); ?></p>
<p><strong>Duration:</strong> <?php echo htmlspecialchars($movie["duration"]); ?> mins</p>
<p><strong>Price per Seat:</strong> ₹<?php echo htmlspecialchars($movie["price"]); ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($movie["location"]); ?></p>
<p><strong>Available Seats:</strong> <?php echo htmlspecialchars($movie["total_seats"]); ?></p>

<form method="POST">
    <label for="seats">Number of Seats:</label>
    <input type="number" id="seats" name="seats" min="1" max="<?php echo $movie["total_seats"]; ?>" required oninput="updateTotalPrice()">
    
    <p><strong>Total Price:</strong> <span id="totalPrice">₹0</span></p>
    
    <button type="submit">Proceed to Payment</button>
</form>

</body>
</html>
