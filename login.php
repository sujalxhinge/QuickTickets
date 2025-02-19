<?php
session_start(); // Start session to store user data

$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "quicktickets"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];

    // Query to check user in database
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found! Please sign up first.'); window.history.back();</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images icons/link.png" type="image/icon type">
</head>
<style>
   
   body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full height of viewport */
    margin: 0; /* Remove default margin */
    background-color: #f4f4f4; /* Optional background */
}

.form-container {
    width: 350px;
    height: 500px;
    background-color: #fff;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    border-radius: 10px;
    box-sizing: border-box;
    padding: 20px 30px;
  
}


.title {
  text-align: center;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  margin: 10px 0 30px 0;
  font-size: 28px;
  font-weight: 800;
}

.form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 18px;
  margin-bottom: 15px;
}

.input {
  border-radius: 20px;
  border: 1px solid #c0c0c0;
  outline: 0 !important;
  box-sizing: border-box;
  padding: 12px 15px;
}

.page-link {
  text-decoration: underline;
  margin: 0;
  text-align: end;
  color: #747474;
  text-decoration-color: #747474;
}

.page-link-label {
  cursor: pointer;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  font-size: 9px;
  font-weight: 700;
}

.page-link-label:hover {
  color: #000;
}

.form-btn {
  padding: 10px 15px;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  border-radius: 20px;
  font-size: 15px;
  font-weight: bold;
  border: 0 !important;
  outline: 0 !important;
  background: rgb(0, 0, 0);
  color: white;
  cursor: pointer;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}

.form-btn:active {
  box-shadow: none;
}
.form-btn:hover
{
    background-color: #147f1b;
    color: #f4f4f4;
}

.sign-up-label {
  margin: 0;
  font-size: 14px;
  color: #747474;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        margin-left: 46px;
}

.sign-up-link {
  margin-left: 1px;
  font-size: 11px;
  text-decoration: underline;
  text-decoration-color: teal;
  color: teal;
  cursor: pointer;
  font-weight: 800;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
}

.buttons-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  margin-top: 20px;
  gap: 15px;
}


</style>
<body>
 <div class="form-container">
    <p class="title">Login</p>
    <form class="form" action="login.php" method="POST">
    <input type="text" class="input" name="email_or_username" placeholder="Username/Email" required>
    <input type="password" class="input" name="password" placeholder="Password" required>
    <p class="page-link">
    <a href="forgot_password.php" class="page-link-label">Forgot Password?</a>
    </p>
    <button type="submit" class="form-btn">Login</button>
    <p class="sign-up-label">
        Don't have an account? 
        <a href="signup.php" class="sign-up-link">Sign up</a>
    </p>
</form>

  </div>
</body>
</html>