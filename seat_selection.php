<?php
session_start();

include 'dbconnection.php';
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate request parameters
if (!isset($_GET['id']) || !isset($_GET['type']) || !isset($_GET['showtime'])) {
    die("Invalid request. Please go back and select a valid show.");
}

$id = (int)$_GET['id'];
$type = $_GET['type'];
$showtime_id = (int)$_GET['showtime'];

// Fetch movie/event details
if ($type === "movie") {
    $sql = "SELECT * FROM movies WHERE movie_id = ?";
} else {
    $sql = "SELECT * FROM events WHERE event_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Invalid request. No movie or event found.");
}
$row = $result->fetch_assoc();

// Fetch booked seats for this showtime
$bookedSeatsQuery = "SELECT seat_number FROM seats WHERE showtime_id = ? AND is_booked = 1";
$seatStmt = $conn->prepare($bookedSeatsQuery);
$seatStmt->bind_param("i", $showtime_id);
$seatStmt->execute();
$seatResult = $seatStmt->get_result();
$bookedSeats = [];
while ($seatRow = $seatResult->fetch_assoc()) {
    $bookedSeats[] = $seatRow['seat_number'];
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
    <title>Select Seats - <?php echo htmlspecialchars($row["title"]); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .seat-container {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            max-width: 500px;
            margin: 20px auto;
        }
        .seat {
            width: 40px;
            height: 40px;
            background-color: #ddd;
            border-radius: 5px;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }
        .seat.booked {
            background-color: red;
            cursor: not-allowed;
        }
        .seat.selected {
            background-color: green;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($row["title"]); ?></h1>
    <img src="uploads/<?php echo htmlspecialchars($row["image"] ?? "default.jpg"); ?>" width="200">

    <h2>Select Your Seats</h2>

    <div class="seat-container">
        <?php
        for ($i = 1; $i <= 50; $i++) {
            $seatClass = in_array($i, $bookedSeats) ? "seat booked" : "seat";
            echo "<div class='$seatClass' data-seat='$i'>$i</div>";
        }
        ?>
    </div>

    <br>
    <button id="proceedBtn">Proceed to Payment</button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedSeats = [];

            document.querySelectorAll(".seat").forEach(seat => {
                if (!seat.classList.contains("booked")) {
                    seat.addEventListener("click", function () {
                        let seatNumber = this.getAttribute("data-seat");

                        if (selectedSeats.includes(seatNumber)) {
                            selectedSeats = selectedSeats.filter(num => num !== seatNumber);
                            this.classList.remove("selected");
                        } else {
                            selectedSeats.push(seatNumber);
                            this.classList.add("selected");
                        }
                    });
                }
            });

            document.getElementById("proceedBtn").addEventListener("click", function () {
                if (selectedSeats.length === 0) {
                    alert("Please select at least one seat!");
                    return;
                }

                // Redirect to process_booking.php with GET parameters
                let url = `process_booking.php?id=<?php echo $id; ?>&type=<?php echo $type; ?>&showtime_id=<?php echo $showtime_id; ?>&selected_seats=${selectedSeats.join(",")}`;
                window.location.href = url;
            });
        });
    </script>
</body>
</html>
