<?php
include('../features/header.php');
include('../features/getCars.php');

$search = isset($_GET['query']) ? trim($_GET['query']) : '';
$query = isset($_GET['query']) ? strtolower(trim($_GET['query'])) : '';
$filters = isset($_GET['filter']) ? $_GET['filter'] : [];

$filteredCars = array_filter($cars, function ($car) use ($query, $filters) {
    $queryWords = array_filter(explode(' ', $query));
    if (empty($queryWords)) {
        $matchQuery = true;
    } else {
        $matchQuery = true;
        foreach ($queryWords as $word) {
            $word = strtolower($word);
            if (
                strpos(strtolower($car['brand']), $word) === false &&
                strpos(strtolower($car['carModel']), $word) === false
            ) {
                $matchQuery = false;
                break;
            }
        }
    }
    if (empty($filters)) return $matchQuery;
    $matchFilter = true;
    foreach ($filters as $filter) {
        if ($filter !== $car['carType'] && $filter !== $car['brand']) {
            $matchFilter = false;
            break;
        }
    }
    return $matchQuery && $matchFilter;
});
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
    <h2>Search Results<?= $search ? " for '$search'" : '' ?></h2>
    <?php if (!empty($filters)): ?>
        <p class="filtersApplied">Filters applied:
            <?php
            echo htmlspecialchars(implode(', ', $filters));
            ?>
        </p>
    <?php endif; ?>
    <?php if (empty($filteredCars)): ?>
        <p>No cars matched your search.</p>
    <?php else: ?>
        <div class="cars-grid">
            <?php foreach ($filteredCars as $car): ?>
                <div class="car">
                    <a href="../pages/car.php?vin=<?= $car['vin'] ?>">
                        <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] . ' ' . $car['carModel'] ?>">
                        <h3><?= $car['brand'] . ' ' . $car['carModel'] ?></h3>
                    </a>
                    <p><?= $car['description'] ?></p>
                    <p><strong>Type:</strong> <?= $car['carType'] ?> | <strong>Year:</strong> <?= $car['yearOfManufacture'] ?></p>
                    <p><strong>Price/Day:</strong> $<?= $car['pricePerDay'] ?></p>
                    <?php if (!empty($car['available']) && $car['available'] === true): ?>
                    <a href="../pages/reservation.php?vin=<?= urlencode($car['vin']) ?>">
                        <button class="rentBtn">Rent</button>
                    </a>
                    <?php else: ?>
                        <button class="rentBtn" disabled>Unavailable</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>

<?php include('../features/footer.php'); ?>