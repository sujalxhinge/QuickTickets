<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="userprofile.css" />
    <link rel="icon" href="images icons/link.png" type="image/icon type">
</head>
<body> 
<button class="cta" onclick="window.location.href='dashboard.html';">
  <span class="hover-underline-animation">Home</span>
  <svg
    id="arrow-horizontal"
    xmlns="http://www.w3.org/2000/svg"
    width="30"
    height="10"
    viewBox="0 0 46 16"
  >
    <path
      id="Path_10"
      data-name="Path 10"
      d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
      transform="translate(30)"
    ></path>
  </svg>
</button>


<button class="Btn" onclick="window.location.href='login.php';">
  <div class="sign">
    <svg viewBox="0 0 512 512">
      <path
        d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"
      ></path>
    </svg>
  </div>
</button>


  <div id="form-ui">
  <form action="update_profile.php" method="post" id="form">
    <div id="form-body">
        <div id="welcome-lines">
            <div id="welcome-line-2"><span>Welcome Back <?php echo htmlspecialchars($username); ?>!</span></div>
        </div>
        <div id="input-area">
            <div class="form-inp">
                <input name="first_name" placeholder="First Name" type="text" required>
            </div>
            <div class="form-inp">
                <input name="last_name" placeholder="Last Name" type="text" required>
            </div>
            <div class="form-inp">
                <input name="address" placeholder="Address" type="text" required>
            </div>
            <div class="form-inp">
                <input name="phone_number" placeholder="Phone Number" type="number" required>
            </div>
            <div class="form-inp">
                <input name="email" placeholder="Email Address" type="email" required>
            </div>
        </div>
        <div id="submit-button-cvr">
            <button id="submit-button" type="submit">Save Changes</button>
        </div>
    </div>
</form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#form").on("submit", function (event) {
        event.preventDefault();

        $.ajax({
            url: "update_profile.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                alert(response.message);
            },
            error: function () {
                alert("Something went wrong. Please try again.");
            }
        });
    });
});
</script>

</body>
</html>