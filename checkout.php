<?php

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // Ensure you have a database connection file

if (isset($_GET["title"])) {
    $title = $_GET["title"];
    $duration = $_GET["duration"];
    $language = $_GET["language"];
    $price = $_GET["price"];
    $rating = $_GET["rating"];
    $location = $_GET["location"];
    $theater = $_GET["theater"];
    $time = $_GET["time"];
    $image = $_GET["image"];
    $date = $_GET["date"];
    $total_seats = $_GET["total_seats"];
} else {
    die("No movie selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - <?php echo htmlspecialchars($title); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <img src="<?php echo htmlspecialchars($image); ?>" alt="Movie Image">
    <p><strong>Duration:</strong> <?php echo htmlspecialchars($duration); ?></p>
    <p><strong>Language:</strong> <?php echo htmlspecialchars($language); ?></p>
    <p><strong>Price:</strong> ₹<span id="ticket_price"><?php echo htmlspecialchars($price); ?></span></p>
    <p><strong>Rating:</strong> <?php echo htmlspecialchars($rating); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
    <p><strong>Theater:</strong> <?php echo htmlspecialchars($theater); ?></p>
    <p><strong>Show Time:</strong> <?php echo htmlspecialchars($time); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
    
    <p><strong>Add Seats:</strong> 
        <input type="number" id="seats" min="1" max="<?php echo htmlspecialchars($total_seats); ?>"> 
        <input type="button" onclick="updateTotal()" value="ADD" id="seatsubmit">
    </p>

    <p><strong>Total:</strong> ₹<span id="totalvalue">0</span></p>
    <p><strong>Total Seats Available:</strong> <span id="remaining_seats"><?php echo htmlspecialchars($total_seats); ?></span></p>

    <button>Proceed to Payment</button>

    <script>
    function updateTotal() {
        let seats = parseInt(document.getElementById('seats').value);
        let price = parseInt(document.getElementById('ticket_price').innerText);
        let totalSeats = parseInt(document.getElementById('remaining_seats').innerText);

        // Validate input
        if (isNaN(seats) || seats <= 0 || seats > totalSeats) {
            alert("Please enter a valid number of seats.");
            return;
        }

        let totalCost = seats * price;
        document.getElementById('totalvalue').innerText = totalCost;  // ✅ Update total value correctly


    }
</script>

</body>
</html>
