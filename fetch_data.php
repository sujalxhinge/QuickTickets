<?php
header("Content-Type: application/json"); // Set response type as JSON

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "quicktickets"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]);
    exit();
}

$categories = ['Movie', 'Event', 'Show', 'Sports', 'Stand-Up Comedy', 'Concert'];
$data = [];

foreach ($categories as $category) {
    $sql = "SELECT image, title, theater FROM categories WHERE category = '$category' LIMIT 7";
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(["error" => "SQL Error: " . $conn->error]);
        exit();
    }

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    $data[$category] = $items;
}

$conn->close();
echo json_encode($data);
?>
