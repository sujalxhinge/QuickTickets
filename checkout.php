<?php
if (isset($_GET["title"])) {
    $title = $_GET["title"];
    $duration = $_GET["duration"];
    $language = $_GET["language"];
    $price = $_GET["price"];
    $rating = $_GET["rating"];
    $location = $_GET["location"];
    $theater = $_GET["theater"];
    $time = $_GET["time"];
    $image = $_GET["image"];
    $date = $_GET["date"];
    $total_seats = $_GET["total_seats"];
} else {
    die("No movie selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - <?php echo htmlspecialchars($title); ?></title>
</head>
<body>
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <img src="<?php echo htmlspecialchars($image); ?>" alt="Movie Image">
    <p><strong>Duration:</strong> <?php echo htmlspecialchars($duration); ?></p>
    <p><strong>Language:</strong> <?php echo htmlspecialchars($language); ?></p>
    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($price); ?></p>
    <p><strong>Rating:</strong> <?php echo htmlspecialchars($rating); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
    <p><strong>Theater:</strong> <?php echo htmlspecialchars($theater); ?></p>
    <p><strong>Show Time:</strong> <?php echo htmlspecialchars($time); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($date); ?></p>
    <p><strong>Total Seats:</strong> <?php echo htmlspecialchars($total_seats); ?></p>

    <button>Proceed to Payment</button>
</body>
</html>
