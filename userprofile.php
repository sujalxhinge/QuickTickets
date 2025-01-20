<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - QuickTickets</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .back-button {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .back-button:hover {
            text-decoration: underline;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile-header h1 {
            font-size: 24px;
            margin: 0;
        }
        .profile-details {
            margin-top: 20px;
        }
        .profile-details div {
            margin-bottom: 15px;
        }
        .profile-details label {
            font-weight: bold;
            display: block;
        }
        .tickets {
            margin-top: 30px;
        }
        .tickets h2 {
            margin-bottom: 10px;
        }
        .ticket-item {
            background: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.html" class="back-button">&larr; Back</a>

        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'quicktickets');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assuming user ID is passed via GET parameter
        $userId = $_GET['id'];

        // Fetch user data
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "<div class='profile-header'>";
            echo "<img src='" . $user['profile_picture'] . "' alt='Profile Picture'>";
            echo "<h1>" . htmlspecialchars($user['name']) . "</h1>";
            echo "</div>";

            echo "<div class='profile-details'>";
            echo "<div><label>Email:</label>" . htmlspecialchars($user['email']) . "</div>";
            echo "<div><label>Phone:</label>" . htmlspecialchars($user['phone']) . "</div>";
            echo "</div>";
        } else {
            echo "<p>User not found.</p>";
        }

        // Fetch purchased tickets
        $sqlTickets = "SELECT * FROM tickets WHERE user_id = ?";
        $stmtTickets = $conn->prepare($sqlTickets);
        $stmtTickets->bind_param("i", $userId);
        $stmtTickets->execute();
        $resultTickets = $stmtTickets->get_result();

        echo "<div class='tickets'>";
        echo "<h2>Purchased Tickets</h2>";

        if ($resultTickets->num_rows > 0) {
            while ($ticket = $resultTickets->fetch_assoc()) {
                echo "<div class='ticket-item'>";
                echo "<p><strong>Event:</strong> " . htmlspecialchars($ticket['event_name']) . "</p>";
                echo "<p><strong>Date:</strong> " . htmlspecialchars($ticket['event_date']) . "</p>";
                echo "<p><strong>Seat:</strong> " . htmlspecialchars($ticket['seat_number']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No tickets purchased yet.</p>";
        }

        echo "</div>";

        $stmt->close();
        $stmtTickets->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
