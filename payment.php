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

<!-- <form action="success.php" method="POST">
    <script src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="rzp_test_yqkNFHwH9hASXh"
        data-amount="<?php echo $totalAmount * 100; ?>"
        data-currency="INR"
        data-order_id="<?php echo $order['id']; ?>"
        data-buttontext="Pay Now"></script>
</form> -->
<html>
    <body>
<div class="card">
  <div class="card-wrapper">
    <div class="card-icon">
      <div class="icon-cart-box">
        <svg
          viewBox="0 0 512 512"
          width="20"
          height="20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8V444.8C394 378 431.1 230.1 432 141.4L256 66.8l0 0z"
            fill="#ffffff"
          ></path>
        </svg>
      </div>
    </div>

    <div class="card-content">
      <div class="card-title-wrapper">
        <span class="card-title">Confirmation</span>
        <span class="card-action">
         
        </span>
      </div>
      <div class="card-text">
      ✔  Once the payment is successful, your booking will be confirmed instantly.<br>
      ✔  Tickets are non-refundable and cannot be modified after confirmation.<br>
      ✔  Ensure your payment details are correct to avoid any issues.<br>
      ✔  Your selected seats and showtime will be reserved only after payment.<br>
      ✔  Your transaction is secure, and all payment details are encrypted.<br>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<form id="razorpay-form" action="success.php" method="POST">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <input type="hidden" id="rzp_payment_id" name="rzp_payment_id">
    <input type="hidden" id="rzp_order_id" name="rzp_order_id">
    <input type="hidden" id="rzp_signature" name="rzp_signature">

    <button type="button" class="Btn" id="pay-btn">
        Pay
        <svg class="svgIcon" viewBox="0 0 576 512">
            <path d="M512 80c8.8 0 16 7.2 16 16v32H48V96c0-8.8 7.2-16 16-16H512zm16 144V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224H528zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H248z"></path>
        </svg>
    </button>
</form>

<script>
    document.getElementById("pay-btn").addEventListener("click", function () {
        var options = {
            "key": "rzp_test_yqkNFHwH9hASXh",
            "amount": "<?php echo $totalAmount * 100; ?>",
            "currency": "INR",
            "order_id": "<?php echo $order['id']; ?>",
            "handler": function (response) {
                document.getElementById("rzp_payment_id").value = response.razorpay_payment_id;
                document.getElementById("rzp_order_id").value = response.razorpay_order_id;
                document.getElementById("rzp_signature").value = response.razorpay_signature;
                document.getElementById("razorpay-form").submit();
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    });
</script>

<style>
    body {
        display: flex;
        flex-direction: column;
        justify-content: center; /* Centers vertically */
        align-items: center; /* Centers horizontally */
        height: 100vh; /* Full viewport height */
        margin: 0;
        background-color: #f4f4f4; /* Optional background color */
    }

    .card {
        width: 600px;
        height: auto;
        background: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4), 0 2px 3px rgba(0, 0, 0, 0.3);
        padding: 10px;
        margin-bottom: 35px; /* Added space between card and button */
    }

    .card-wrapper {
        display: inline-flex;
        flex-wrap: nowrap;
        align-items: center;
        width: 100%;
    }

    .card-wrapper {
    display: flex;
    align-items: center; /* Aligns items vertically */
    justify-content: flex-start; /* Aligns content to the left */
    width: 100%;
}

.card-icon {
    width: 170px; /* Fixed width */
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-icon .icon-cart-box {
    background-color: #2196f3;
    width: 3em;
    height: 3em;
    border-radius: 50%;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

    .card-content {
        width: 80%;
    }

    .card-title-wrapper {
        display: inline-flex;
        flex-wrap: nowrap;
        align-items: baseline;
        width: 100%;
    }

    .card-title {
        width: 95%;
        font-size: 1em;
        font-weight: 600;
        color: #333;
        padding: 20px 0 0 10px;
    }

    .card-action {
        width: 5%;
        text-align: right;
        padding: 0 20px;
    }

    .card-action svg {
        cursor: pointer;
        fill: rgba(0, 0, 0, 0.2);
        transition: 0.3s ease-in-out;
    }

    .card-action svg:hover {
        fill: rgba(0, 0, 0, 0.6);
    }

    .card-text {
        font-size: 0.8em;
        color: #757575;
        padding: 10px 0 10px 10px;
    }

    .product-price {
        font-size: 0.9em;
        font-weight: 600;
        color: #333;
        padding: 0 0 10px 10px;
    }

    /* Centering the form and button */
    #razorpay-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .Btn {
        width: 130px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgb(15, 15, 15);
        border: none;
        color: white;
        font-weight: 600;
        gap: 8px;
        cursor: pointer;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.106);
        position: relative;
        overflow: hidden;
        transition-duration: .3s;
    }

    .svgIcon {
        width: 16px;
    }

    .svgIcon path {
        fill: white;
    }

    .Btn::before {
        width: 130px;
        height: 130px;
        position: absolute;
        content: "";
        background-color: white;
        border-radius: 50%;
        left: -100%;
        top: 0;
        transition-duration: .3s;
        mix-blend-mode: difference;
    }

    .Btn:hover::before {
        transition-duration: .3s;
        transform: translate(100%, -50%);
        border-radius: 0;
    }

    .Btn:active {
        transform: translate(5px, 5px);
        transition-duration: .3s;
    }
</style>

