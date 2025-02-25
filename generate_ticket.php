<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// Ticket details (Replace with database values)
$bookingID = "MENO000ZEQK0N1";
$movieName = "Drishyam (U/A)";
$venue = "Menoka Cinema: Kolkata (Menoka New), Kolkata";
$seatNumbers = "H16, H17";
$showDate = "Mon, 10 Aug, 2015";
$showTime = "4:15 PM";

// HTML content with Bootstrap & custom styling
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickTickets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; }
        .ticket-container { border: 3px solid #dc3545; border-radius: 15px; padding: 20px; width: 600px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 24px; font-weight: bold; color: #dc3545; }
        .not-ticket { font-size: 12px; color: #dc3545; text-align: right; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
        .ticket-box { border: 2px solid #ccc; padding: 10px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <div class="brand">QuickTickets 🎟️</div>
            <div class="not-ticket">THIS IS NOT YOUR TICKET.<br>Exchange this at the box office.</div>
        </div>
        <hr>
        <p><strong>📌 BOOKING ID:</strong> ' . $bookingID . '</p>
        <div class="grid-container">
            <div class="ticket-box">
                <strong>🎬 ' . $movieName . '</strong><br>
                <small>📍 ' . $venue . '</small>
            </div>
            <div class="ticket-box">
                <strong>🎟️ Dress Circle:</strong> ' . $seatNumbers . '
            </div>
            <div class="ticket-box">
                <strong>📅 ' . $showDate . '</strong>
            </div>
            <div class="ticket-box">
                <strong>⏰ ' . $showTime . '</strong>
            </div>
        </div>
    </div>
</body>
</html>';

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size & render PDF
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the PDF
$dompdf->stream("ticket.pdf", ["Attachment" => false]);
?>
