<?php
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
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password

    // Check if user already exists
    $check_sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('User already exists! Please try a different email or username.'); window.history.back();</script>";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $full_name, $email, $username, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: Could not sign up. Please try again.');</script>";
        }
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignUp</title>
    <link rel="icon" href="images icons/link.png" type="image/icon type" />
  </head>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 35px;
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
      border: 0 !important;
      outline: 0 !important;
      background: teal;
      color: white;
      cursor: pointer;
      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .form-btn:active {
      box-shadow: none;
    }

    .sign-up-label {
      margin: 0;
      font-size: 10px;
      color: #747474;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
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

    .apple-login-button,
    .google-login-button {
      border-radius: 20px;
      box-sizing: border-box;
      padding: 10px 15px;
      box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px,
        rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      font-size: 11px;
      gap: 5px;
    }

    .apple-login-button {
      background-color: #000;
      color: #fff;
      border: 2px solid #000;
    }

    .google-login-button {
      border: 2px solid #747474;
    }

    .apple-icon,
    .google-icon {
      font-size: 18px;
      margin-bottom: 1px;
    }
  </style>
  <body>
    <!-- From Uiverse.io by akshat-patel28 -->
    <div class="form-container">
      <p class="title">Sign Up</p>
      <form class="form" action="signup.php" method="POST">
    <input type="text" class="input" name="full_name" placeholder="Enter Full Name" required />
    <input type="email" class="input" name="email" placeholder="Email" required />
    <input type="text" class="input" name="username" placeholder="Username" required />
    <input type="password" class="input" name="password" placeholder="Password" required />
    <button type="submit" class="form-btn">Sign Up</button>
</form>

      <div class="buttons-container">
        <div class="google-login-button">
          <svg
            stroke="currentColor"
            fill="currentColor"
            stroke-width="0"
            version="1.1"
            x="0px"
            y="0px"
            class="google-icon"
            viewBox="0 0 48 48"
            height="1em"
            width="1em"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill="#FFC107"
              d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12
  c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24
  c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"
            ></path>
            <path
              fill="#FF3D00"
              d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657
  C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"
            ></path>
            <path
              fill="#4CAF50"
              d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36
  c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"
            ></path>
            <path
              fill="#1976D2"
              d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571
  c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"
            ></path>
          </svg>
          <span>Log in with Google</span>
        </div>
      </div>
    </div>
  </body>
</html>
