<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "User") {
    header("Location: login.php");
    exit;
}

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
    SELECT Coffee.CoffeeID, Coffee.CoffeeName, Coffee.CoffeeDesc, StoreCoffee.Price
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
    <title><?= $store['StoreName'] ?> - Coffee Menu</title>
    <link rel="stylesheet" href="./css/storeDetail.css">
</head>

<body>
    <header>
        <nav>
            <?php include "./utils/navbarUser.php"; ?>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="header-row">
                <h2 class="store-title"><?= htmlspecialchars($store['StoreName']); ?></h2>
                <a href="cart.php" class="cart-btn">Cart</a>
            </div>
        
            <p class="location">Location: <?= htmlspecialchars($store['StoreLocation']); ?></p>
            <h3 class="menu-title">Menu:</h3>
        
            <div class="coffee-list">
                <?php while ($row = mysqli_fetch_assoc($coffeeRes)) : ?>
                    <div class="coffee-card">
        
                        <h3 class="coffee-name">
                            <?= htmlspecialchars($row['CoffeeName']); ?> 
                            - Rp <?= number_format($row['Price'], 0, ',', '.'); ?>
                        </h3>
        
                        <p class="desc"><?= htmlspecialchars($row['CoffeeDesc']); ?></p>
        
                        <form action="addToCart.php" method="POST" class="cart-form">
                            <label class="amount-label">Amount:</label>
                            <input type="number" name="amount" min="1" value="1" class="amount-input">
        
                            <input type="hidden" name="coffeeid" value="<?= $row['CoffeeID']; ?>">
                            <input type="hidden" name="storeid" value="<?= $storeid; ?>">
        
                            <button class="btn-add">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
        
                <?php if (mysqli_num_rows($coffeeRes) == 0): ?>
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
