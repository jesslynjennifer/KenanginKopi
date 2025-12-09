<?php
session_start();
include "./utils/db.php";

if (!isset($_GET['storeid'])) {
    echo "Store not found.";
    exit;
}

$storeid = mysqli_real_escape_string($conn, $_GET['storeid']);

$storeQuery = "
SELECT * FROM Store 
WHERE StoreID = '$storeid'
";

$storeRes = mysqli_query($conn, $storeQuery);
$store = mysqli_fetch_assoc($storeRes);

if (!$store) {
    echo "Store not found.";
    exit;
}

$coffeeQuery = "
    SELECT Coffee.CoffeeName, Coffee.CoffeeDesc, StoreCoffee.Price
    FROM StoreCoffee
    JOIN Coffee ON Coffee.CoffeeID = StoreCoffee.CoffeeID
    WHERE StoreCoffee.StoreID = '$storeid'
";

$coffeeRes = mysqli_query($conn, $coffeeQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $store['StoreName']; ?> - Store Details</title>
    <link rel="stylesheet" href="./css/storeDetail.css">
</head>
<body>
    <header>
        <nav>
            <?php include "./utils/navbarGuest.php"; ?>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2 class="store-title"><?= $store['StoreName']; ?></h2>
            <p class="location">Location: <?= htmlspecialchars($store['StoreLocation']); ?></p>
            <h2 class="menu">Menu: </h2>
    
            <div class="coffee-list">
                <?php while ($row = mysqli_fetch_assoc($coffeeRes)) : ?>
                    <div class="coffee-card">
                        <h3><?= htmlspecialchars($row['CoffeeName']); ?> - Rp <?= number_format($row['Price'], 0, ',', '.'); ?></h3>
                        <p class="desc"><?= htmlspecialchars($row['CoffeeDesc']); ?></p>
                    </div>
                <?php endwhile; ?>
    
                <?php if (mysqli_num_rows($coffeeRes) == 0) : ?>
                    <p class="no-data">No coffee available in this store.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <?php include "./utils/footer.php"; ?>
    </footer>
</body>
</html>
