<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "quicktickets";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $address = trim($_POST["address"]);
    $phone_number = trim($_POST["phone_number"]);
    $email = trim($_POST["email"]);

    // Validate phone number (10 digits)
    if (!preg_match("/^\d{10}$/", $phone_number)) {
        echo json_encode(["status" => "error", "message" => "Invalid phone number"]);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email address"]);
        exit();
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM usersprofile WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_exists = $result->num_rows > 0;
    $stmt->close();

    if ($user_exists) {
        // Update existing user details
        $stmt = $conn->prepare("UPDATE usersprofile SET first_name = ?, last_name = ?, address = ?, phone_number = ? WHERE email = ?");
        $stmt->bind_param("sssss", $first_name, $last_name, $address, $phone_number, $email);
        $message = ($stmt->execute()) ? "Profile updated successfully" : "Error updating profile: " . $conn->error;
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO usersprofile (first_name, last_name, address, phone_number, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $address, $phone_number, $email);
        $message = ($stmt->execute()) ? "Profile created successfully" : "Error saving profile: " . $conn->error;
    }

    echo json_encode(["status" => "success", "message" => $message]);
    $stmt->close();
}

$conn->close();
?>
