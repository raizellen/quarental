<?php
include('../features/header.php');
include('../features/getCars.php');

$carVin = $_GET['vin'] ?? null;

$car = null;

if ($carVin !== null && !empty($cars)) {
    foreach ($cars as $c) {
        if ($c['vin'] == $carVin) {
            $car = $c;
            break;
        }
    }
}

if (!$car) {
    echo "<h2>Car not found</h2>";
    exit;
}
?>

<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quarental</title>
    <link rel="stylesheet" href="/poti assignment 2/assets/styles/styles.css">
    <script src="/poti assignment 2/assets/js/search.js" defer></script>
</head>

<body>
    <div class="single-car">
        <br>
        <a class="backBtn" href="javascript:history.back()">← Back</a><br>
        <img src="<?= $car['image'] ?>" alt="<?= $car['carModel'] ?>"/>
        <div class="car-details">
            <h2><?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['carModel']) ?></h2>
            <p><strong>Type:</strong> <?= htmlspecialchars($car['carType']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($car['description']) ?></p>
            <p><strong>Year of Manufacture:</strong> <?= htmlspecialchars($car['yearOfManufacture']) ?></p>
            <p><strong>Mileage:</strong> <?= htmlspecialchars($car['mileage']) ?></p>
            <p><strong>Fuel Type:</strong> <?= htmlspecialchars($car['fuelType']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($car['pricePerDay']) ?>/day</p>
            <div class="button-wrapper">
                <?php if (!empty($car['available']) && $car['available'] === true): ?>
                <a href="reservation.php?vin=<?= urlencode($car['vin']) ?>">
                    <button class="rentBtn">Rent</button>
                </a>
                <?php else: ?>
                    <button class="rentBtn" disabled>Unavailable</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php include('../features/footer.php'); ?>