<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "QuickTickets";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$full_name = $email = $contact = $query = "";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $query = $_POST['query'];

    // Server-side validation for phone number (exactly 10 digits)
    if (!preg_match("/^\d{10}$/", $contact)) {
        $message = "Error: Phone number must be exactly 10 digits.";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO contactusTB (full_name, email, contact, query) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $full_name, $email, $contact, $query);

        if ($stmt->execute()) {
            $message = "Your query has been submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <style>
    body {
      background: url('images icons/gredient-2.jpg') no-repeat center center fixed;
      background-size: cover;
      -webkit-backdrop-filter: blur(19px);
      backdrop-filter: blur(19px);
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .contact-form {
      border-radius: 10px;
      padding: 20px;
      width: 380px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.8);
      position: relative;
      backdrop-filter: blur(13px);
      -webkit-backdrop-filter: blur(13px);
      background-color: rgba(255, 255, 255, 0.1);
    }
    .contact-form h2 {
      text-align: center;
      color: rgb(16, 12, 10);
    }
    .form-group {
      margin-bottom: 8px;
    }
    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #333;
    }
    .form-group input, 
    .form-group textarea {
      width: 94%;
      padding: 10px;
      border: 1px solid rgba(18, 4, 4, 0.5);
      border-radius: 5px;
      font-size: 14px;
      background-color: rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      color: black;
      outline: none;
    }
    .form-group input::placeholder, 
    .form-group textarea::placeholder {
      color: rgba(15, 10, 10, 0.6);
    }
    .form-group textarea {
      height: 100px;
      resize: none;
    }
    .form-group button {
      width: 100%;
      padding: 8px;
      background: rgb(0, 0, 0);
      color: white;
      font-size: 20px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      margin: 10px 10px; 
      font-weight: bold;
    }
    .form-group button:hover {
      background: rgb(255, 174, 0);
    }
    .message {
      text-align: center;
      margin-top: 10px;
      font-size: 16px;
      color: green;
    }
  </style>
  <script>
    function validatePhoneNumber() {
      let contactInput = document.getElementById("contact");
      let contactValue = contactInput.value;
      let phoneRegex = /^\d{10}$/;

      if (!phoneRegex.test(contactValue)) {
        contactInput.setCustomValidity("Phone number must be exactly 10 digits.");
      } else {
        contactInput.setCustomValidity("");
      }
    }
  </script>
  <link rel="icon" href="images icons/link.png" type="image/icon type">
</head>
<body>
  <div class="contact-form">
    <h2>Contact Us</h2>

    <?php if (!empty($message)) { ?>
      <div class="message"><?php echo $message; ?></div>
    <?php } ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" required oninput="validatePhoneNumber()" maxlength="10">
      </div>
      <div class="form-group">
        <label for="query">Query:</label>
        <textarea id="query" name="query" required></textarea>
      </div>
      <div class="form-group">
        <button type="submit">Submit</button>
      </div>
    </form>
  </div>
</body>
</html>
