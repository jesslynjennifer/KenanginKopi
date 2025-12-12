<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['UserID'];

$query = "
    SELECT T.TransactionID, T.TransactionDate, T.TotalPrice, S.StoreName
    FROM Transactions T
    JOIN Store S ON S.StoreID = T.StoreID
    WHERE T.UserID = '$userid'
    ORDER BY T.TransactionDate DESC
";

$transactions = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order History - KenanginKopi</title>
    <link rel="stylesheet" href="./css/history.css">
</head>
<body>
    <header>
        <nav>
            <?php include "./utils/navbar.php"; ?>
        </nav>
    </header>

    <main>
        <div class="history-container">
            <h2 class="title">Order History</h2>

            <?php if (mysqli_num_rows($transactions) == 0): ?>

                <div class="empty-box">
                    <p class="empty-text">There is no transaction</p>
                    <p class="small">You haven’t order coffee yet.</p>
                    <a href="homeUser.php" class="order-btn">Order Now</a>
                </div>

                <a href="profile.php" class="back-btn">← Back</a>

            <?php else: ?>
                <?php while ($t = mysqli_fetch_assoc($transactions)): ?>

                    <div class="transaction-block">
                        <h3 class="transaction-id">Transaction ID: <?= $t['TransactionID'] ?></h3>
                        <p class="date">Date: <?= $t['TransactionDate'] ?></p>
                        <p class="store">Store: <?= htmlspecialchars($t['StoreName']) ?></p>
                        <h4 class="ordered-items">Ordered Items:</h4>

                        <table class="history-table">
                            <tr>
                                <th>Coffee</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>

                            <?php
                            $transId = $t['TransactionID'];
                            $detailQuery = "
                                SELECT C.CoffeeName, TD.Qty, TD.SubTotal
                                FROM TransactionDetails TD
                                JOIN Coffee C ON C.CoffeeID = TD.CoffeeID
                                WHERE TD.TransactionID = '$transId'
                            ";
                            $detailRes = mysqli_query($conn, $detailQuery);

                            while ($d = mysqli_fetch_assoc($detailRes)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['CoffeeName']) ?></td>
                                    <td><?= $d['Qty'] ?></td>
                                    <td>Rp <?= number_format($d['SubTotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>

                            <tr class="total-row">
                                <td colspan="2" class="total-label">Total</td>
                                <td class="total-price">
                                    Rp <?= number_format($t['TotalPrice'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                <?php endwhile; ?>

                <a href="profile.php" class="back-btn">← Back</a>

            <?php endif; ?>
        </div>
    </main>

    <footer>
        <?php include "./utils/footer.php"; ?>
    </footer>
</body>
</html>
