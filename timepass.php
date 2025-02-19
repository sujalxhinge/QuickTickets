<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
</head>
<body>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quicktickets");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch movie data
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><img src='" . htmlspecialchars($row["image"]) . "' height='100' width='100'></td>";
        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No movies found.";
}

// Close connection
$conn->close();
?>

</body>
</html>
