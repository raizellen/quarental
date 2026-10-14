<?php
include('../features/header.php');

$carDataPath = __DIR__ . '/../assets/data/cars.json';
$orderDataPath = __DIR__ . '/../assets/data/orders.json';

$vin = $_POST['vin'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$license = $_POST['license'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$days = isset($_POST['days']) ? intval($_POST['days']) : 1;

$carsData = json_decode(file_get_contents($carDataPath), true);
$carIndex = array_search($vin, array_column($carsData['cars'], 'vin'));

$success = false;
if ($carIndex !== false && $carsData['cars'][$carIndex]['available']) {
    $pricePerDay = $carsData['cars'][$carIndex]['pricePerDay'];
    $totalPrice = $pricePerDay * $days;

    $newOrder = [
        'customer' => [
            'name' => $name,
            'phoneNumber' => $phone,
            'email' => $email,
            'driversLicenseNumber' => $license,
        ],
        'car' => [
            'vin' => $vin,
        ],
        'rental' => [
            'startDate' => $startDate,
            'rentalPeriod' => $days,
            'totalPrice' => $totalPrice,
            'orderDate' => date('Y-m-d'),
        ],
    ];

    if (file_exists($orderDataPath)) {
        $ordersData = json_decode(file_get_contents($orderDataPath), true);
        if (!isset($ordersData['orders'])) {
            $ordersData['orders'] = [];
        }
    } else {
        $ordersData = ['orders' => []];
    }

    $ordersData['orders'][] = $newOrder;
    file_put_contents($orderDataPath, json_encode($ordersData, JSON_PRETTY_PRINT));

    $carsData['cars'][$carIndex]['available'] = false;
    file_put_contents($carDataPath, json_encode($carsData, JSON_PRETTY_PRINT));

    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="/poti assignment 2/assets/styles/styles.css">
    <script src="/poti assignment 2/assets/js/script.js" defer></script>
    <script src="/poti assignment 2/assets/js/reservation.js" defer></script>
</head>

<body>
    <br><br>
    <a href="/poti assignment 2/index.php" class="backToHome">Back to Home</a>
    <?php if ($success): ?>
        <script>
            localStorage.clear();
        </script>
        <h2>Reservation Successful!</h2>
        <p style="text-align: center; padding: 5px;">Thank you, your booking has been confirmed.</p>
    <?php else: ?>
        <h2>Reservation Failed</h1>
        <p style="text-align: center; padding: 5px;">Sorry, the car is no longer available or does not exist.</p>
    <?php endif; ?>
</body>
<?php include('../features/footer.php'); ?>