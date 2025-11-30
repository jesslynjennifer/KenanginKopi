<?php
session_start();
include "./utils/db.php";
include "./utils/navbarAdmin.php";

// HARUS ADMIN
if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['storeid'])) {
    echo "Store not found.";
    exit;
}

$storeid = mysqli_real_escape_string($conn, $_GET['storeid']);

// Ambil Store
$storeQuery = "SELECT * FROM Store WHERE StoreID = '$storeid'";
$storeRes = mysqli_query($conn, $storeQuery);
$store = mysqli_fetch_assoc($storeRes);

if (!$store) {
    echo "Store not found.";
    exit;
}

// Ambil coffee terkait
$coffeeQuery = "
    SELECT StoreCoffee.CoffeeID, Coffee.CoffeeName, StoreCoffee.Price
    FROM StoreCoffee
    JOIN Coffee ON Coffee.CoffeeID = StoreCoffee.CoffeeID
    WHERE StoreCoffee.StoreID = '$storeid'
";
$coffeeRes = mysqli_query($conn, $coffeeQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $store['StoreName']; ?> - Admin Manage</title>
    <link rel="stylesheet" href="./css/storeDetail.css">
</head>
<body>

<?php include "navbarAdmin.php"; ?>

<div class="container">

    <h2 class="store-title">Manage: <?= $store['StoreName']; ?></h2>

    <a href="addStoreCoffee.php?storeid=<?= $storeid; ?>" class="add-btn">
        + Add Coffee
    </a>

    <div class="coffee-list">

        <?php while ($row = mysqli_fetch_assoc($coffeeRes)) : ?>
            <div class="coffee-card">
                <h3><?= $row['CoffeeName']; ?></h3>
                <p class="price">IDR <?= number_format($row['Price'], 0, ',', '.'); ?></p>

                <div class="actions">
                    <a href="editPrice.php?storeid=<?= $storeid; ?>&coffeeid=<?= $row['CoffeeID']; ?>" class="edit-btn">Edit Price</a>

                    <a href="deleteStoreCoffee.php?storeid=<?= $storeid; ?>&coffeeid=<?= $row['CoffeeID']; ?>" 
                        class="delete-btn"
                        onclick="return confirm('Delete this coffee from store?')">
                        Delete
                    </a>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($coffeeRes) == 0): ?>
            <p class="no-data">No coffee assigned to this store.</p>
        <?php endif; ?>

    </div>

</div>

<?php include "./utils/footer.php"; ?>

</body>
</html>
