<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load mPDF

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<h2 style="text-align:center;">Recent Users</h2>');

// Database Connection
$conn = new mysqli("localhost", "root", "", "quicktickets");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Latest 20 Users
$sql = "SELECT first_name, last_name, address, phone_number, email FROM usersprofile ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);

// HTML Table for PDF
$html = '<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['address']}</td>
                <td>{$row['phone_number']}</td>
                <td>{$row['email']}</td>
              </tr>";
}
$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output("Recent_Users.pdf", "D"); // "D" forces download

$conn->close();
?>
