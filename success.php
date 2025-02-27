<?php
session_start();

if (!isset($_SESSION['seats']) || !isset($_SESSION['showtime'])) {
    die("Error: Missing booking details.");
}

$showtime = $_SESSION['showtime'];
$seats = $_SESSION['seats'];
$total_price = $_SESSION['total_price'];

//  $showtime
// $seats
// $total_price

include 'dbconnection.php';
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];
$seat_list = implode(",", $seats);
$booking_date = date("Y-m-d H:i:s");

$stmt = $conn->prepare("INSERT INTO bookings (user_id, showtime_id, selected_seats, total_price, booking_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisis", $user_id, $showtime, $seat_list, $total_price, $booking_date);
$stmt->execute();

$seat_values = implode(",", array_map(fn($seat) => "($showtime, $seat, 1)", $seats));

$query = "INSERT INTO seats (showtime_id, seat_number, is_booked) VALUES $seat_values 
          ON DUPLICATE KEY UPDATE is_booked = 1";

$conn->query($query);

$query = "UPDATE seats SET is_booked = 1 
          WHERE showtime_id = $showtime 
          AND seat_number IN (" . implode(",", $seats) . ")";
$conn->query($query);

if ($stmt->affected_rows > 0) {
    echo '<html><head>
    <style>
    html, body {
  height: 100%;
  margin: 0;
  display: flex;
  justify-content: center;  /* Center horizontally */
  align-items: center;      /* Center vertically */
  background-color: #f4f4f4; /* Optional background */
}

    
.card {
  overflow: hidden;
  position: relative;
  text-align: left;
  border-radius: 0.5rem;
  max-width: 290px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.05);
  background-color: #fff;
  display: flex;
  justify-content: center; 
  align-items: center;  
  padding: 20px;
}

.dismiss {
  position: absolute;
  right: 10px;
  top: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 1rem;
  background-color: #fff;
  color: black;
  border: 2px solid #D1D5DB;
  font-size: 1rem;
  font-weight: 300;
  width: 30px;
  height: 30px;
  border-radius: 7px;
  transition: .3s ease;
}

.dismiss:hover {
  background-color: #ee0d0d;
  border: 2px solid #ee0d0d;
  color: #fff;
}

.header {
  padding: 1.25rem 1rem 1rem 1rem;
}

.image {
  display: flex;
  margin-left: auto;
  margin-right: auto;
  background-color: #e2feee;
  flex-shrink: 0;
  justify-content: center;
  align-items: center;
  width: 3rem;
  height: 3rem;
  border-radius: 9999px;
  animation: animate .6s linear alternate-reverse infinite;
  transition: .6s ease;
}

.image svg {
  color: #0afa2a;
  width: 2rem;
  height: 2rem;
}

.content {
  margin-top: 0.75rem;
  text-align: center;
}

.title {
  color: #066e29;
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.5rem;
}

.message {
  margin-top: 0.5rem;
  color: #595b5f;
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.actions {
  margin: 0.75rem 1rem;
}

.history {
  display: inline-flex;
  padding: 0.5rem 1rem;
  background-color: #1aa06d;
  color: #ffffff;
  font-size: 1rem;
  line-height: 1.5rem;
  font-weight: 500;
  justify-content: center;
  width: 100%;
  border-radius: 0.375rem;
  border: none;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.track {
  display: inline-flex;
  margin-top: 0.75rem;
  padding: 0.5rem 1rem;
  color: #242525;
  font-size: 1rem;
  line-height: 1.5rem;
  font-weight: 500;
  justify-content: center;
  width: 100%;
  border-radius: 0.375rem;
  border: 1px solid #D1D5DB;
  background-color: #fff;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

@keyframes animate {
  from {
    transform: scale(1);
  }

  to {
    transform: scale(1.09);
  }
}
    </style>
    </head><body>
<div class="card"> 
  <div class="header"> 
    <div class="image">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><g stroke-width="0" id="SVGRepo_bgCarrier"></g><g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g><g id="SVGRepo_iconCarrier"> <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" stroke="#000000" d="M20 7L9.00004 18L3.99994 13"></path> </g></svg>
      </div> 
      <div class="content">
         <span class="title">Booking Successful</span> 
         <p class="message">Your booking is confirmed! Get ready for an amazing experience. See you there!</p> 
         </div> 
         <div class="actions">
            <a href="receipt.php">
    <button type="button" class="history">Print Ticket</button>
</a>
<a href="dashboard.php">
    <button type="button" class="track">Go To Home</button>
</a>

            </div> 
            </div> 
            </div>
            <body>
            <html>
';
} else {
    echo "<br>Error in booking.";
}
$stmt->close();
$conn->close();
?>