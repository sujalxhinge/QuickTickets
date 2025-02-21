<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET['title'];
    $duration = $_GET['duration'];
    $language = $_GET['language'];
    $price = $_GET['price'];
    $location = $_GET['location'];
    $theater = $_GET['theater'];
    $time = $_GET['time'];
    $date = $_GET['date'];
    $total_seats = $_GET['total_seats'];
    $image = $_GET['image'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="checkout.css">
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script>
        function updateTotal() {
            var price = parseFloat(document.getElementById("price").value);
            var seats = parseInt(document.getElementById("selected_seats").value);
            document.getElementById("total_price").innerText = "₹" + (price * seats);
        }
    </script>
</head>
<body>
    <div class="container" id="checkout-container">
        <div class="image-box" id="image-box">
            <img id="event-image" src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
        </div>
        <form action="" method="POST" id="checkout-form">
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <input type="hidden" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
            <input type="hidden" name="theater" value="<?php echo htmlspecialchars($theater); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <input type="hidden" name="duration" value="<?php echo htmlspecialchars($duration); ?>">
            <input type="hidden" name="language" value="<?php echo htmlspecialchars($language); ?>">

            <div class="info-box" id="info-box">
                <p id="title-info">Title: <?php echo htmlspecialchars($title); ?></p>
                <p id="duration-info">Duration: <?php echo htmlspecialchars($duration); ?></p>
                <p id="language-info">Language: <?php echo htmlspecialchars($language); ?></p>
                <p id="location-info">Location: <?php echo htmlspecialchars($location); ?></p>
                <p id="theater-info">Theater: <?php echo htmlspecialchars($theater); ?></p>
                <p id="time-info">Show Time: <?php echo htmlspecialchars($time); ?></p>
                <p id="date-info">Date: <?php echo htmlspecialchars($date); ?></p>
                <p id="price-info">Price per Seat: ₹<?php echo htmlspecialchars($price); ?></p>
                <p id="available-seats">Available Seats: <?php echo htmlspecialchars($total_seats); ?></p>
            </div>

            <div class="seats-box" id="seats-box">
                <label for="selected_seats">Select Seats:</label>
                <input type="number" id="selected_seats" name="selected_seats" min="1" max="<?php echo htmlspecialchars($total_seats); ?>" required oninput="updateTotal()">
                
                <p style="font-weight:bold";>Total Price: <span id="total_price" style="font-weight:bold;">₹0</span></p>
            </div>
            
          
<button class="pay-btn">
  <span id="pay" class="btn-text">Pay Now</span>
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

        </form>
    </div>
    <script>
     $(document).ready(function()
    {
       $("#pay").click(function(){
        $.ajax( {
          type:"POST",
          url:"order.php",
          data:{
            plan:"silver"
          },
          success:function(res)
          {
           console.log(res);
          }

          })

      })
    });
    </script>
</body>
</html>
