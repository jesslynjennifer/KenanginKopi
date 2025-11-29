<?php
session_start();
include "db.php";

// CEK LOGIN & ROLE
if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] != "Customer") {
    header("Location: login.php");
    exit;
}

// CEK store ID
if (!isset($_GET['storeid'])) {
    echo "Store not found.";
    exit;
}

$storeid = mysqli_real_escape_string($conn, $_GET['storeid']);

// AMBIL INFO STORE
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

// AMBIL COFFEE MILIK STORE INI
$coffeeQuery = "
    SELECT Coffee.CoffeeID, Coffee.CoffeeName, Coffee.Description, 
           StoreCoffee.Price
    FROM StoreCoffee
    INNER JOIN Coffee ON Coffee.CoffeeID = StoreCoffee.CoffeeID
    WHERE StoreCoffee.StoreID = '$storeid'
";

$coffeeRes = mysqli_query($conn, $coffeeQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $store['StoreName'] ?> - Coffee List</title>
    <link rel="stylesheet" href="css/storeDetail.css">
</head>
<body>

<?php include "userNavbar.php"; ?>

<div class="container">

    <h2 class="store-title"><?= htmlspecialchars($store['StoreName']); ?></h2>
    <p class="subtitle">Available Coffees</p>

    <div class="coffee-list">

        <?php while ($row = mysqli_fetch_assoc($coffeeRes)) : ?>
            <div class="coffee-card">
                <h3><?= htmlspecialchars($row['CoffeeName']); ?></h3>
                <p class="desc"><?= htmlspecialchars($row['Description']); ?></p>

                <p class="price">IDR <?= number_format($row['Price'], 0, ',', '.'); ?></p>

                <form action="addToCart.php" method="POST">
                    <input type="hidden" name="coffeeid" value="<?= $row['CoffeeID']; ?>">
                    <input type="hidden" name="storeid" value="<?= $storeid; ?>">
                    <button class="btn-add">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($coffeeRes) == 0): ?>
            <p class="no-data">No coffees available in this store.</p>
        <?php endif; ?>

    </div>

</div>

<?php include "footer.php"; ?>

</body>
</html>
