<?php
$basePath = '/poti assignment 2';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$minimalHeader = in_array($currentPage, ['reservation.php', 'submitted.php']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quarental</title>
    <link rel="stylesheet" href="<?= $basePath ?>/assets/styles/header.css">
    <script src="<?= $basePath ?>/assets/js/search.js" defer></script>
</head>

<body>
    <nav class = "navbar">
        <div class="container">
            <div class="logo">
                <a href="<?= $basePath ?>/index.php">
                    <img src="<?= $basePath ?>/assets/images/logo.png" alt="Quarental Logo">
                </a>
            </div>

            <?php if (!$minimalHeader): ?>
            <div class="right-section">
                <form action="<?= $basePath ?>/pages/searchResults.php" method="GET" class="search-bar" id="searchForm">
                    <input
                        type="text"
                        name="query"
                        placeholder="Search for cars"
                        aria-label="Search for cars"
                        autocomplete="on"
                    />
                    <div class="suggestions-box" id="suggestionsBox" style="display:none;"></div>
                    <div class="filter-dropdown">
                        <button type="button" id="filterBtn">
                            Filter ▼
                        </button>
                        <div class="filter-options" style="display:none"></div>
                    </div>
                    <button type="submit">Search</button>
                </form>

                <div class="reservation" onclick="window.location.href='<?= $basePath ?>/pages/reservation.php'">
                    <img src="<?= $basePath ?>/assets/images/reservation.png" alt="Reservation" title="Your Reservations" />
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>