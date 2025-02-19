<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'quicktickets');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Fetch event details dynamically from categories
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $query = "SELECT * FROM categories WHERE id = $category_id";
    $result = $conn->query($query);
    $event = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Event Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .checkout-container {
            background: white;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838;
        }
        input {
            padding: 8px;
            width: 80%;
            margin: 10px 0;
        }
    </style>
    <script>
        function updateTotalPrice() {
            let seatPrice = parseFloat(document.getElementById('seatPrice').value);
            let selectedSeats = document.getElementById('seats').value.split(',').length;
            let totalPrice = seatPrice * selectedSeats;
            document.getElementById('totalPrice').innerText = 'Total Price: $' + totalPrice;
        }
    </script>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <img src="<?php echo $event['image_url']; ?>" alt="Event Image">
        <h3><?php echo $event['event_name']; ?></h3>
        <p>Theater: <?php echo $event['theater_name']; ?></p>
        <p>Language: <?php echo $event['language']; ?></p>
        <p>Showtime: <?php echo $event['show_time']; ?></p>
        <label>Select Seats: </label>
        <input type="text" id="seats" placeholder="e.g., A1,A2" oninput="updateTotalPrice()">
        <p>Available Seats: <?php echo $event['available_seats']; ?></p>
        <p>Price per Seat: $<span id="seatPrice">10</span></p>
        <p id="totalPrice">Total Price: $0</p>
        <form action="process_booking.php" method="POST">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="0">
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>
