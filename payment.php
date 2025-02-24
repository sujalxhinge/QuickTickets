<?php
session_start();

require 'vendor/autoload.php';
use Razorpay\Api\Api;

if (!isset($_GET['seats']) || !isset($_GET['showtime'])) {
    die("Invalid request");
}

// Store the selected seats and showtime in session
$_SESSION['seats'] = $_GET['seats'];
$_SESSION['showtime'] = $_GET['showtime'];

$totalAmount = count($_GET['seats']) * $_GET['price'];
$_SESSION['total_price'] = $totalAmount;

// Razorpay payment integration
$api = new Api('rzp_test_yqkNFHwH9hASXh', 'WH8XebwAV7keJTABNdU7ngd1');
$order = $api->order->create([
    'receipt' => uniqid(),
    'amount' => $totalAmount * 100,
    'currency' => 'INR'
]);
$_SESSION['order_id'] = $order['id'];
?>

<form action="success.php" method="POST">
    <script src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="rzp_test_yqkNFHwH9hASXh"
        data-amount="<?php echo $totalAmount * 100; ?>"
        data-currency="INR"
        data-order_id="<?php echo $order['id']; ?>"
        data-buttontext="Pay Now"></script>
</form>
