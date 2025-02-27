<?php
require_once __DIR__ . '/vendor/autoload.php'; // Include MPDF

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "quicktickets";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest 20 records
$sql = "SELECT 
            b.booking_id, 
            b.selected_seats, 
            b.total_price, 
            b.booking_date,
            e.title AS event_name,
            m.title AS movie_name
        FROM bookings b
        LEFT JOIN events e ON b.showtime_id = e.event_id
        LEFT JOIN movies m ON b.showtime_id = m.movie_id
        ORDER BY b.booking_date DESC
        LIMIT 20";

$result = $conn->query($sql);

// Generate PDF content
$html = '<h2 style="text-align:center;">Recent Bookings Report</h2>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <th>Movie/Event</th>
        <th>Booking ID</th>
        <th>Seats</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $title = !empty($row["movie_name"]) ? $row["movie_name"] : $row["event_name"];
        $html .= '<tr>
            <td>' . $title . '</td>
            <td>' . $row["booking_id"] . '</td>
            <td>' . $row["selected_seats"] . '</td>
            <td>₹' . $row["total_price"] . '</td>
            <td>' . date("d M Y", strtotime($row["booking_date"])) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center;">No recent bookings found</td></tr>';
}

$html .= '</table>';

// Close database connection
$conn->close();

// Initialize MPDF and generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Recent_Bookings_Report.pdf', 'D'); // 'D' forces download

?>
