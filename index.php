<?php
include('./features/header.php');
include('./features/getCars.php');
?>

<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quarental</title>
    <link rel="stylesheet" href="assets/styles/styles.css">
    <script src="/poti assignment 2/assets/js/search.js" defer></script>
</head>

<body>
    <div class="welcome">
        <img src="<?= $basePath ?>/assets/images/logo.png" alt="Quarental Logo">
        <h1>Quick and Easy Car Rental<br>with Quarental!</h1>
    </div>

    <div class="featured">
        <h2>Featured Cars</h2>
        <div class="cars-grid">
            <?php foreach (array_slice($cars, 0, 5) as $car): ?>
                <div class="car">
                    <a href="./pages/car.php?vin=<?= $car['vin'] ?>">
                        <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['carModel']) ?>">
                        <h3><?= htmlspecialchars($car['brand'] . ' ' . $car['carModel']) ?></h3>
                    </a>
                    <p><?= htmlspecialchars($car['description']) ?></p>
                    <br>
                    <p><strong>Price Per Day:</strong> $<?= htmlspecialchars($car['pricePerDay']) ?></p>
                    <?php if (!empty($car['available']) && $car['available'] === true): ?>
                    <a href="./pages/reservation.php?vin=<?= urlencode($car['vin']) ?>">
                        <button class="rentBtn">Rent</button>
                    </a>
                    <?php else: ?>
                        <button class="rentBtn" disabled>Unavailable</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.cars-grid').addEventListener('click', e => {
                const carDiv = e.target.closest('.car');
                if (carDiv) {
                    const vin = carDiv.dataset.vin;
                    if (vin) {
                        localStorage.setItem('lastCar', vin);
                        console.log('Saved lastCar VIN:', vin);
                    }
                }
            });
        });
    </script>
</body>
</html>

<?php include('features/footer.php'); ?>