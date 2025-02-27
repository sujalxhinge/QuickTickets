<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load MPDF
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

// Database Connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the date range from the form
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

if (empty($from_date) || empty($to_date)) {
    die("Please select a valid date range.");
}

// Fetch event bookings within the selected date range
$sql = "SELECT 
            b.selected_seats, 
            b.total_price, 
            b.booking_date,
            e.title AS event_name,
            e.location,
            e.price,
            e.available_seats,
            e.rating
        FROM bookings b
        INNER JOIN events e ON b.showtime_id = e.event_id
        WHERE DATE(b.booking_date) BETWEEN ? AND ?
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $from_date, $to_date);
$stmt->execute();
$result = $stmt->get_result();

// Create MPDF instance
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Event Bookings Report");

// HTML for PDF
$html = "<h2 style='text-align: center;'>Event Bookings Report</h2>";
$html .= "<p style='text-align: center;'>From: " . date("d M Y", strtotime($from_date)) . " - To: " . date("d M Y", strtotime($to_date)) . "</p>";

$html .= "<table border='1' style='width:100%; border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Location</th>
                    <th>Price (₹)</th>
                    <th>Available Seats</th>
                    <th>Rating</th>
                    <th>Seats</th>
                    <th>Amount (₹)</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>" . $row["event_name"] . "</td>
                    <td>" . $row["location"] . "</td>
                    <td>₹" . $row["price"] . "</td>
                    <td>" . $row["available_seats"] . "</td>
                    <td>" . $row["rating"] . "</td>
                    <td>" . $row["selected_seats"] . "</td>
                    <td>₹" . $row["total_price"] . "</td>
                    <td>" . date("d M Y", strtotime($row["booking_date"])) . "</td>
                </tr>";
    }
} else {
    $html .= "<tr><td colspan='8' style='text-align: center;'>No bookings found in this date range</td></tr>";
}

$html .= "</tbody></table>";

// Add HTML content to PDF
$mpdf->WriteHTML($html);
$mpdf->Output("Event_Bookings_Report.pdf", "D"); // Force Download

// Close database connection
$conn->close();
?>
