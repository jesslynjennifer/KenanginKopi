<?php

session_start();
include "./utils/db.php";


if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['storeid'])) {
    header("Location: manageStore.php");
    exit;
}

$StoreID_raw = $_GET['storeid'];
$storeName = 'Unknown Store';
$stmt_store = mysqli_prepare($conn, "SELECT StoreName FROM Store WHERE StoreID = ?");

if ($stmt_store) {
    mysqli_stmt_bind_param($stmt_store, "s", $StoreID_raw);
    mysqli_stmt_execute($stmt_store);
    $storeResult = mysqli_stmt_get_result($stmt_store);
    $store = mysqli_fetch_assoc($storeResult);
    if ($store) {
        $storeName = htmlspecialchars($store['StoreName']);
    }
    mysqli_stmt_close($stmt_store);
}

$coffeeQuery = "
    SELECT 
        c.CoffeeID, 
        c.CoffeeName, 
        sc.Price, 
        c.CoffeeDesc 
    FROM 
        Coffee c
    JOIN 
        StoreCoffee sc ON c.CoffeeID = sc.CoffeeID
    WHERE 
        sc.StoreID = ?
    ORDER BY 
        c.CoffeeID
";
$coffeeResult = false;
$stmt_coffee = mysqli_prepare($conn, $coffeeQuery);

if ($stmt_coffee) {
    mysqli_stmt_bind_param($stmt_coffee, "s", $StoreID_raw);
    mysqli_stmt_execute($stmt_coffee);
    $coffeeResult = mysqli_stmt_get_result($stmt_coffee);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coffee Page - <?= $storeName ?></title>
    <link rel="stylesheet" href="css/manageCoffee.css">
    </head>
<body>

    <header>
        <?php include "./utils/navbarAdmin.php"; ?>
    </header>

    <main class="container">
        <h1>Manage Coffee for **<?= $storeName ?>**</h1>
        
        <a href="addCoffee.php?StoreID=<?= urlencode($StoreID_raw) ?>" class="add-btn">Add Coffee</a>
        
        <table class="coffee-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

            <?php if ($coffeeResult && mysqli_num_rows($coffeeResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($coffeeResult)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CoffeeID']); ?></td>
                        <td><?= htmlspecialchars($row['CoffeeName']); ?></td>
                        <td>Rp <?= number_format($row['Price'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($row['CoffeeDesc']); ?></td>
                        <td>
                            <form method="POST" action="deleteCoffee.php" style="display:inline;">
                                <input type="hidden" name="coffeeid" value="<?= htmlspecialchars($row['CoffeeID']); ?>">
                                <input type="hidden" name="storeid" value="<?= htmlspecialchars($StoreID_raw); ?>">
                                
                                <button type="submit" 
                                        onclick="return confirm('Are you sure want to remove **<?= $row['CoffeeName']; ?>** from **<?= $storeName ?>**?');"
                                        class="delete-btn">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No coffee found in this store.</td>
                </tr>
            <?php endif; ?>
        </table>
        
        <?php 
        if ($stmt_coffee) {
            mysqli_stmt_close($stmt_coffee);
        }
        ?>
        
        <a href="manageStore.php" class="back-btn">← Back to Manage Stores</a>
    </main>
</body>
</html>