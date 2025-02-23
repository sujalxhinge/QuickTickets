<?php
session_start();


// Database Connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get details from URL
$showtime_id = $_GET['showtime'];
$venue_id = $_GET['venue'];
$theater_id = $_GET['theater'];
$type = $_GET['type']; // movie or event
$id = $_GET['id']; // movie_id or event_id

// Fetch movie/event details
if ($type == "movie") {
    $query = "SELECT title, image, price FROM movies WHERE movie_id = ?";
} else {
    $query = "SELECT title, image, price FROM events WHERE event_id = ?";
}
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();

// Fetch booked seats for the showtime
$bookedSeats = [];
$seatQuery = "SELECT seat_number FROM seats WHERE showtime_id = ? AND is_booked = 1";
$seatStmt = $conn->prepare($seatQuery);
$seatStmt->bind_param("i", $showtime_id);
$seatStmt->execute();
$seatResult = $seatStmt->get_result();
while ($seat = $seatResult->fetch_assoc()) {
    $bookedSeats[] = $seat['seat_number'];
}

$stmt->close();
$seatStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - <?php echo htmlspecialchars($details["title"]); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .screen {
            background: black;
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
        }
        .seat-container {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            gap: 10px;
            justify-content: center;
        }
        .seat {
            width: 40px;
            height: 40px;
            background: #3498db;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .seat.booked {
            background: #2ecc71;
            cursor: not-allowed;
        }
        .seat.selected {
            background: #e74c3c;
        }
        .info {
            margin-top: 10px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        button {
            margin-top: 10px;
            padding: 10px;
            background: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background: #219150;
        }
    </style>
    <script>
        let selectedSeats = [];
        const pricePerSeat = <?php echo $details["price"]; ?>;

        function selectSeat(seat) {
            if (seat.classList.contains('booked')) return;

            const seatNumber = seat.dataset.seat;
            if (selectedSeats.includes(seatNumber)) {
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                seat.classList.remove('selected');
            } else {
                selectedSeats.push(seatNumber);
                seat.classList.add('selected');
            }
            updateTotal();
        }

        function updateTotal() {
            const total = selectedSeats.length * pricePerSeat;
            document.getElementById('totalPrice').innerText = "Total: ₹" + total;
        }

        function submitBooking() {
            if (selectedSeats.length === 0) {
                alert("Please select at least one seat.");
                return false;
            }
            document.getElementById('selectedSeats').value = selectedSeats.join(",");
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($details["title"]); ?></h1>
        <img src="uploads/<?php echo htmlspecialchars($details["image"] ?? "default.jpg"); ?>" width="200">
        <p>Venue: <?php echo htmlspecialchars($venue_id); ?> | Theater: <?php echo htmlspecialchars($theater_id); ?></p>
        <p>Showtime: <?php echo htmlspecialchars($showtime_id); ?></p>
        <div class="screen">Screen</div>

        <form action="process_booking.php" method="POST" onsubmit="return submitBooking();">
            <input type="hidden" name="showtime" value="<?php echo $showtime_id; ?>">
            <input type="hidden" name="venue" value="<?php echo $venue_id; ?>">
            <input type="hidden" name="theater" value="<?php echo $theater_id; ?>">
            <input type="hidden" name="selectedSeats" id="selectedSeats">

            <div class="seat-container">
                <?php
                for ($row = 1; $row <= 10; $row++) {
                    for ($col = 1; $col <= 10; $col++) {
                        $seatNumber = "$row-$col";
                        $isBooked = in_array($seatNumber, $bookedSeats) ? "booked" : "available";
                        echo "<div class='seat $isBooked' data-seat='$seatNumber' onclick='selectSeat(this)'>$seatNumber</div>";
                    }
                }
                ?>
            </div>

            <p id="totalPrice" class="total">Total: ₹0</p>
            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>
