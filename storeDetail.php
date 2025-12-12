<?php
session_start();
include "./utils/db.php";

$isLoggedIn = isset($_SESSION['UserID']);
$role = $isLoggedIn ? $_SESSION['UserRole'] : "Guest";

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
    JOIN Coffee ON Coffee.CoffeeID = StoreCoffee.CoffeeID
    WHERE StoreCoffee.StoreID = '$storeid'
";
$coffeeRes = mysqli_query($conn, $coffeeQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($store['StoreName']); ?> - Store Details</title>
    <link rel="stylesheet" href="./css/storeDetail.css">
</head>
<body>
    <header>
        <nav>
            <?php include "./utils/navbar.php"; ?>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="header-row">
                <h2 class="store-title"><?= htmlspecialchars($store['StoreName']); ?></h2>

                <?php if ($role === "User"): ?>
                    <a href="cart.php" class="cart-btn">Cart</a>
                <?php endif; ?>
            </div>

            <p class="location">Location: <?= htmlspecialchars($store['StoreLocation']); ?></p>

            <?php if ($role === "Admin"): ?>
                <a href="addCoffee.php?StoreID=<?= urlencode($storeid); ?>" class="add-btn">Add Coffee</a>
            <?php endif; ?>

            <h3 class="menu-title">Menu:</h3>

            <div class="coffee-list">
                <?php while ($row = mysqli_fetch_assoc($coffeeRes)) : ?>
                    <div class="coffee-card">

                        <h3 class="coffee-name">
                            <?= htmlspecialchars($row['CoffeeName']); ?>
                            - Rp <?= number_format($row['Price'], 0, ',', '.'); ?>
                        </h3>

                        <p class="desc"><?= htmlspecialchars($row['CoffeeDesc']); ?></p>

                        <?php if ($role === "User"): ?>
                            <form action="addToCart.php" method="POST" class="cart-form">
                                <label class="amount-label">Amount:</label>
                                <input type="number" name="amount" min="1" value="1" class="amount-input">

                                <input type="hidden" name="coffeeid" value="<?= $row['CoffeeID']; ?>">
                                <input type="hidden" name="storeid" value="<?= $storeid; ?>">

                                <button class="btn-add">Add to Cart</button>
                            </form>
                        <?php endif; ?>

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
