<?php
session_start();
include "./utils/db.php";

//ngecek apakah ada transaksi apa ngga
$userid = $_SESSION['UserID'];

$sqlCheckOrder = "SELECT COUNT(*) AS total FROM Transactions WHERE UserID = '$userid'";
$resultCheck = mysqli_query($conn, $sqlCheckOrder);
$rowCheck = mysqli_fetch_assoc($resultCheck);

$noOrderYet = ($rowCheck['total'] == 0);


$paymentSuccess = false;
$transactionId = null;

$cart = $_SESSION['cart'] ?? [];

$totalItem = 0;
$totalPrice = 0;

foreach ($cart as $item) {
    $qty = (int)$item['qty'];
    $price = (float)$item['price'];
    $totalItem += $qty;
    $totalPrice += ($qty * $price);
}

if (isset($_POST['pay']) && !empty($cart)) {

    $transactionId = "T" . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);

    $firstItem = reset($cart);
    $storeid = $firstItem['storeid'] ?? null;

    if ($storeid === null) {
        die("Error: storeid missing in cart. PLEASE FIX addToCart.php");
    }

    $userid = $_SESSION['UserID'];

    $sqlTrans = "
        INSERT INTO Transactions (TransactionID, UserID, StoreID, TransactionDate, TotalPrice)
        VALUES ('$transactionId', '$userid', '$storeid', NOW(), '$totalPrice')
    ";
    mysqli_query($conn, $sqlTrans);

    foreach ($cart as $coffeeid => $item) {
        $qty = (int)$item['qty'];
        $subtotal = $qty * $item['price'];

        $sqlDetail = "
            INSERT INTO TransactionDetails (TransactionID, CoffeeID, Qty, SubTotal)
            VALUES ('$transactionId', '$coffeeid', '$qty', '$subtotal')
        ";
        mysqli_query($conn, $sqlDetail);
    }

    unset($_SESSION['cart']);

    $paymentSuccess = true;

    $cart = [];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shopping Cart - KenanginKopi</title>
    <link rel="stylesheet" href="./css/cart.css">
</head>
<body>
    <header>
        <nav>
            <?php include "./utils/navbarUser.php"; ?>
        </nav>
    </header>

    <main>
        <div class="cart-container">

            <?php if ($noOrderYet): ?>
                <div class="no-coffee-message">
                    You have no coffee order yet.<br>
                    <a class="back-home-btn" href="index.php">Order Coffee Now</a>
                </div>
            <?php endif; ?>

            <div class="shopping-header">
                <div class="shopping-title">Shopping Cart</div>
                <div class="summary-line">
                    <?= $totalItem ?> item(s) — Total: Rp <?= number_format($totalPrice, 0, ',', '.') ?>
                </div>
            </div>
    
        <?php if ($paymentSuccess): ?>
            <div class="success-message">
                Payment Successful! ID: <b><?= htmlspecialchars($transactionId) ?></b>
            </div>
            <p class="no-coffee">Cart is Empty.<br><a class="back-home-btn" href="homeUser.php">Order Coffee Now</a></p>
            <?php exit; ?>
        <?php endif; ?>
    
            <table class="cart-table">
                <tr>
                    <th>Coffee</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
    
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                    $qty = (int)$item['qty'];
                    $price = (float)$item['price'];
                    $subtotal = $qty * $price;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['desc']) ?></td>
                        <td>Rp <?= number_format($price, 0, ',', '.') ?></td>
    
                        <td>
                            <form action="updateCart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="coffeeid" value="<?= $id ?>">
                                <input type="number" name="qty" value="<?= $qty ?>" min="1" style="width:70px;">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </td>
    
                        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
    
                        <td>
                            <form action="deleteCart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="coffeeid" value="<?= $id ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
    
            </table>
    
            <form method="POST" style="margin-top:18px;">
                <button type="submit" name="pay" class="pay-btn">Pay</button>
            </form>
            <a href="storeDetailUser.php?storeid=S0001" class="back-button">← Back</a>
        </div>
        

    </main>

    <footer>
        <?php include "./utils/footer.php"; ?>
    </footer>

</body>
</html>
