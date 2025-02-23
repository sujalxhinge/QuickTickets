<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to securely handle image uploads
function uploadImage($imageFile) {
    $target_dir = "uploads/";
    $imageName = basename($imageFile["name"]);
    $target_file = $target_dir . $imageName;

    // Check if the file is an actual image
    $check = getimagesize($imageFile["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Move uploaded file
    if (!move_uploaded_file($imageFile["tmp_name"], $target_file)) {
        die("Failed to upload image.");
    }

    return $imageName;
}

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // Get action from form

    switch ($action) {
        case 'add_category':
            $category_name = $_POST['category_name'];
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->bind_param("s", $category_name);
            if ($stmt->execute()) echo "Category added!";
            $stmt->close();
            exit;
            break;

        case 'add_venue':
            $venue_name = $_POST['venue_name'];
            $location = $_POST['location']; // Capture location

            $stmt = $conn->prepare("INSERT INTO venues (venue_name, location) VALUES (?, ?)");
            $stmt->bind_param("ss", $venue_name, $location);
            if ($stmt->execute()) echo "Venue added!";
            $stmt->close();
            exit;
            break;

        case 'add_theater':
            $theater_name = $_POST['theater_name'];
            $venue_id = $_POST['venue_id'];
            $location = $_POST['location']; // Capture location

            $stmt = $conn->prepare("INSERT INTO theaters (theater_name, venue_id, location) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $theater_name, $venue_id, $location);
            if ($stmt->execute()) echo "Theater added!";
            $stmt->close();
            exit;
            break;

        case 'add_movie':
            $title = $_POST['title'];
            $duration = $_POST['duration'];
            $language = $_POST['language'];
            $price = $_POST['price'];
            $rating = $_POST['rating'];
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
            $image = uploadImage($_FILES['image']);

            $stmt = $conn->prepare("INSERT INTO movies (title, duration, language, price, rating, category_id, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdiss", $title, $duration, $language, $price, $rating, $category_id, $image);
            if ($stmt->execute()) echo "Movie added!";
            $stmt->close();
            exit;
            break;

        case 'add_event':
            $title = $_POST['title'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $location = $_POST['location'];
            $price = $_POST['price'];
            $rating = $_POST['rating'];
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
            $image = uploadImage($_FILES['image']);

            $stmt = $conn->prepare("INSERT INTO events (title, date, time, location, price, rating, category_id, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssdiis", $title, $date, $time, $location, $price, $rating, $category_id, $image);
            if ($stmt->execute()) echo "Event added!";
            $stmt->close();
            exit;
            break;

            case 'add_showtime':
                $date = $_POST['date'];
                $time = $_POST['time'];
                $total_seats = $_POST['total_seats'];
                $available_seats = $total_seats;
            
                // Check if movie_id or event_id is provided
                $movie_id = isset($_POST['movie_id']) && !empty($_POST['movie_id']) ? $_POST['movie_id'] : NULL;
                $event_id = isset($_POST['event_id']) && !empty($_POST['event_id']) ? $_POST['event_id'] : NULL;
            
                if ($movie_id && $event_id) {
                    die("Error: You cannot add both Movie ID and Event ID at the same time.");
                }
                if (!$movie_id && !$event_id) {
                    die("Error: You must provide either a Movie ID or an Event ID.");
                }
            
                // Handle theater_id for movies and venue_id for events
                $theater_id = isset($_POST['theater_id']) && !empty($_POST['theater_id']) ? $_POST['theater_id'] : NULL;
                $venue_id = isset($_POST['venue_id']) && !empty($_POST['venue_id']) ? $_POST['venue_id'] : NULL;
            
                if ($movie_id && !$theater_id) {
                    die("Error: You must provide a Theater ID for movies.");
                }
                if ($event_id && !$venue_id) {
                    die("Error: You must provide a Venue ID for events.");
                }
            
                // Insert into showtimes table
                $stmt = $conn->prepare("INSERT INTO showtimes (movie_id, event_id, theater_id, venue_id, date, time, total_seats, available_seats) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiissi", $movie_id, $event_id, $theater_id, $venue_id, $date, $time, $total_seats, $available_seats);
            
                if ($stmt->execute()) {
                    echo "Showtime added successfully!";
            
                    // ✅ Update total seats in events if event_id is provided
                    if ($event_id) {
                        $updateStmt = $conn->prepare("UPDATE events SET total_seats = total_seats + ? WHERE event_id = ?");
                        $updateStmt->bind_param("ii", $total_seats, $event_id);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                } else {
                    echo "Error: " . $stmt->error;
                }
            
                $stmt->close();
                exit;
                break;
            
            
    

        case 'update_seats':
            $showtime_id = $_POST['showtime_id'];
            $available_seats = $_POST['available_seats'];

         
            $stmt = $conn->prepare("UPDATE showtimes SET available_seats = ? WHERE showtime_id = ?");

            $stmt->bind_param("ii", $available_seats, $showtime_id);
            if ($stmt->execute()) echo "Seats updated!";
            $stmt->close();
            exit;
            break;

        default:
            die("Invalid action.");
    }
}

// Close the connection
$conn->close();
?>
