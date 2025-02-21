<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<button id="pay">pay now</button>
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
          var order_id=JSON.parse(res).order_id;
          var amount=JSON.parse(res).amount;
          
          startPayment(order_id,amount)
          }
          })

      })
    });
    function startPayment(order_id,amount) {
        var options = {
            key: "rzp_test_yqkNFHwH9hASXh", // Enter the Key ID generated from the Dashboard
            amount: amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            currency: "INR",
            name: "STP DRIVE",
            description: "silver plan",
            image: "https://www.stpcomputereducation.com/images/logo.svg",
            order_id: order_id, // This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            prefill: {
                name: "Gaurav Kumar",
                email: "gaurav.kumar@example.com",
                contact: "9000090000"
            },
            notes: {
                address: "Razorpay Corporate Office"
            },
            theme: {
                "color": "#3399cc"
            },
            "handler": function (response){
                window.location.href="https://www.stpcomputereducation.com";
       }
        };
        var rzp = new Razorpay(options);
        rzp.open();
        rzp.on('payment.failed', function (response){
   
    alert(response.error.reason);
   
});
    }
    </script>
</body>
</html>
