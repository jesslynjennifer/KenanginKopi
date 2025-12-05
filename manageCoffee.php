<?php

session_start();
include "./utils/db.php";

// if (!isset($_SESSION['UserID'])) {
//     header("Location: login.php");
//     exit;
// }

if (!isset($_POST['StoreID'])) {
    echo "Store not found.";
    exit;
}

$StoreID = $_POST['StoreID'];

//query buat tampilin data store
$storeName = $_POST['StoreName'];
$storeQuery = "SELECT * FROM Store WHERE StoreID = '$StoreID'";
$storeResult = mysqli_query($conn, $storeQuery);
$store = mysqli_fetch_assoc($storeResult);


$coffeeQuery = "SELECT * FROM Coffee WHERE StoreID = '$StoreID'";
$coffeeResult = mysqli_query($conn, $coffeeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coffee Page</title>
</head>
<body>

    <header>
        <?php
        // include 'KenanginKopi\utils\navbarAdmin.php';
        ?>
    </header>

    <main>
        <h1>Manage Coffee for </h1>
        <a href="addCoffee.php">Add Coffee</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $coffeeResult->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['CoffeeID']; ?></td>
                    <td><?= $row['CoffeeName']; ?></td>
                    <td><?= $row['CoffeePrice']; ?></td>
                    <td><?= $row['CoffeeDescription']; ?></td>
                    <td>
                        <a href="deleteCoffee.php?coffeeid=<?= $row['CoffeeID']; ?>&storeid=<?= $storeid; ?>"
                        onclick="return confirm('Are you sure want to delete this coffee?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    
</body>
</html>