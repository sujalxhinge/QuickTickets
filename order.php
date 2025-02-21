<?php
require("src/Razorpay.php");
use Razorpay\Api\Api;

$api_key="rzp_test_yqkNFHwH9hASXh";
$api_secret="WH8XebwAV7keJTABNdU7ngd1";
$plan=$_POST['plan'];
try{
$api = new Api($api_key, $api_secret);
$order=$api->order->create(array(
    'receipt' => 'reciept_'.time(),
     'amount' => 500*100,
      'currency' => 'INR',
       'notes'=> array(
        'plan'=> $plan
       
    ))); 
    $order_id=$order['id'];
    $amount=$order['amount'];
    echo json_encode(array("order_id"=>$order_id,"amount"=>$amount));
}
catch(Exception $e)
{
die("Error".$e->getMessage());
}
?>