<?php
include('../features/header.php');
$vin = $_GET['vin'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="/poti assignment 2/assets/styles/styles.css">
    <script src="/poti assignment 2/assets/js/script.js" defer></script>
    <script src="/poti assignment 2/assets/js/reservation.js" defer></script>
</head>

<body>
    <div id="noCarMessage" style="display: none; text-align: center; padding: 50px;">
        <p>No car selected. Please return to the homepage and choose a car.</p>
    </div>
    <div class="container">
    <div id="carDetails"></div>
    <div id="reservationFormContainer" style="display: none;"></div>
    </div>
    <div id="confirmationModal" class="modal-overlay hidden">
        <div class="modal-box">
            <p>Are you sure you want to place this reservation?</p>
            <div class="modal-buttons">
                <button id="confirmYes">Yes</button>
                <button id="confirmNo">No</button>
            </div>
        </div>
    </div>
</body>
</html>