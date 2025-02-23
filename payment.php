<?php
session_start();
require('vendor/autoload.php'); // Razorpay SDK

use Razorpay\Api\Api;

// Redirect if booking details are missing
if (!isset($_SESSION['booking_id'], $_SESSION['total_price'], $_SESSION['user_id'])) {
    header("Location: seat_selection.php"); // Redirect back to seat selection
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$userQuery = "SELECT full_name, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// If user not found, redirect
if (!$user) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Assign user details
$user_name = htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8');
$user_email = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
$user_contact = ""; // Keep contact empty

// Razorpay API Credentials
$keyId = "rzp_test_yqkNFHwH9hASXh";
$keySecret = "WH8XebwAV7keJTABNdU7ngd1";
$api = new Api($keyId, $keySecret);

// Create Razorpay Order
$orderData = [
    'receipt'         => 'booking_' . $_SESSION['booking_id'],
    'amount'          => (int) $_SESSION['total_price'] * 100, // Convert to paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto capture
];

$order = $api->order->create($orderData);

// Store order details in session
$_SESSION['razorpay_order_id'] = $order['id'];

// Prepare payment options for Razorpay (JSON encoded for security)
$razorpayOptions = json_encode([
    "key"        => $keyId,
    "amount"     => $orderData['amount'],
    "currency"   => "INR",
    "name"       => "QuickTickets",
    "description"=> "Ticket Booking Payment",
    "order_id"   => $order['id'],
    "handler"    => "function(response) {
        window.location.href = 'success.php?payment_id=' + response.razorpay_payment_id;
    }",
    "prefill"    => [
        "name"    => $user_name,
        "email"   => $user_email,
        "contact" => $user_contact // Empty contact field
    ],
    "theme"      => ["color" => "#F37254"]
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - QuickTickets</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Processing Payment...</h2>
    
    <script>
        var options = <?php echo $razorpayOptions; ?>;
        options.handler = eval("(" + options.handler + ")"); // Convert handler to function
        var rzp = new Razorpay(options);
        rzp.open();
    </script>
</body>
</html>
