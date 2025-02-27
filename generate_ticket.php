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

// HTML content with proper structure
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QuickTickets - Movie Ticket</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
             
        }

        body {
  
    display: flex;
    align-items: center;   /* Centers vertically */
    justify-content: center; /* Centers horizontally */
    height: 100vh; /* Full height of the viewport */
    margin: 170px; /* Remove any default margin */
  box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3), 
                        0px 4px 20px rgba(0, 0, 0, 0.2);
        }

        .ticket {
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3), 
                        0px 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
        }
        .ticket::before, .ticket::after {
            content: "";
            position: absolute;
            width: 30px;
            height: 30px;
            background:rgb(255, 255, 255);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        .ticket::before {
            left: -15px;
        }
        .ticket::after {
            right: -15px;
        }
        .ticket-header {
            background:rgb(35, 8, 8);
            color: #fff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-size: 20px;
            font-weight: bold;
        }
        .ticket-body {
            padding: 20px;
            text-align: left;
            border-bottom: 2px dashed #d32f2f;
        }
        .ticket-body p {
            margin: 8px 0;
            font-size: 16px;
        }
        .highlight {
            font-weight: bold;
            color: #d32f2f;
        }
        .ticket-footer {
            padding: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">QuickTickets</div>
        <div class="ticket-body">
            <p><strong>Booking ID:</strong> <span class="highlight">' . $bookingID . '</span></p>
            <p><strong>Movie:</strong> ' . $movieName . '</p>
            <p><strong>Venue:</strong> ' . $venue . '</p>
            <p><strong>Seats:</strong> <span class="highlight">' . $seatNumbers . '</span></p>
            <p><strong>Date:</strong> ' . $showDate . '</p>
            <p><strong>Time:</strong> ' . $showTime . '</p>
        </div>
        <div class="ticket-footer">Enjoy your movie!</div>
    </div>
</body>
</html>
';

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size & render PDF
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the PDF
$dompdf->stream("ticket.pdf", ["Attachment" => false]);
?>
