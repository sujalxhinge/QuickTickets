<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        td, th {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        img {
            border-radius: 10px;
        }
        button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query to fetch movie data
$sql = "SELECT title, image FROM movies";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Poster</th><th>Title</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $imagePath = !empty($row["image"]) ? "uploads/" . htmlspecialchars($row["image"]) : "assets/default.jpg";
        
        echo "<tr>";
        echo "<td><img src='" . $imagePath . "' height='100' width='100' alt='Movie Poster'></td>";
        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "<td>
                <a href='checkout.php?title=" . urlencode($row["title"]) . "'>
                    <button>Book Ticket</button>
                </a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>No movies found.</p>";
}

// Close connection
$conn->close();
?>

</body>
</html>
