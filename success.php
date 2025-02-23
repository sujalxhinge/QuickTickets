<?php
session_start();
require('vendor/autoload.php'); // Razorpay SDK

use Razorpay\Api\Api;

if (!isset($_GET['payment_id']) || !isset($_SESSION['razorpay_order_id'])) {
    die("Payment failed or unauthorized access!");
}

// Razorpay API Credentials
$keyId = "rzp_test_yqkNFHwH9hASXh";
$keySecret = "WH8XebwAV7keJTABNdU7ngd1";
$api = new Api($keyId, $keySecret);

// Verify payment
$payment = $api->payment->fetch($_GET['payment_id']);

if ($payment->status === "captured") {
    // Payment Successful - Update Database
    $conn = new mysqli("localhost", "root", "", "quicktickets");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $booking_id = $_SESSION['booking_id'];
    $user_id = 1; // Replace with logged-in user ID
    $amount = $_SESSION['total_price'];
    $payment_id = $_GET['payment_id'];
    $status = "Success";
    $transaction_id = $_SESSION['razorpay_order_id'];
    $payment_date = date("Y-m-d H:i:s");

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (booking_id, user_id, amount, payment_status, transaction_id, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $booking_id, $user_id, $amount, $status, $transaction_id, $payment_date);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect to Ticket Page
    header("Location: ticket.php?booking_id=$booking_id");
    exit();
} else {
    echo "Payment failed!";
}
?>
