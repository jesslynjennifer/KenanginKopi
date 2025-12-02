<?php
session_start();

// --- payment handling (in-page) ---
$paymentSuccess = false;
$transactionId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    // generate simple transaction id: T + 4 random digits (you can change logic)
    $transactionId = 'T' . rand(1000, 9999);

    // clear cart
    unset($_SESSION['cart']);

    $paymentSuccess = true;
}

// get cart from session (may be empty array)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// initialize totals to avoid undefined variable warnings
$totalItem = 0;
$totalPrice = 0;

// compute totals (if there are items)
foreach ($cart as $id => $item) {
    $qty = isset($item['qty']) ? (int)$item['qty'] : 0;
    $price = isset($item['price']) ? (float)$item['price'] : 0;
    $subtotal = $price * $qty;

    $totalItem += $qty;
    $totalPrice += $subtotal;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shopping Cart - KenanginKopi</title>
    <link rel="stylesheet" href="./css/cart.css">
    <style>
    /* small extra styles for the success box and summary row */
    .cart-container { width: 70%; margin: 90px auto; padding: 30px; background: rgba(255,255,255,0.95); border-radius: 10px; box-shadow: 0 4px 18px rgba(0,0,0,0.12); }
    .shopping-title { font-size: 28px; font-weight:700; color:#5d3a1a; margin-bottom:6px; }
    .summary-line { color:#444; margin-bottom:18px; font-weight:600; }
    .success-message {
        background:#f6e6d6;
        border-radius:6px;
        padding:12px 14px;
        color:#7a4d2b;
        margin-bottom:16px;
        display:inline-block;
    }
    .back-home-btn { display:inline-block; margin-left:12px; padding:6px 12px; background:#c79d74; color:#fff; text-decoration:none; border-radius:6px; }
    .cart-table th { background:#ecdcc9; }
    .no-coffee { color:#666; padding:20px 0; }
    </style>
</head>
<body>

<?php include "./utils/navbarUser.php"; ?>

<div class="cart-container">

    <div class="shopping-header">
        <div class="shopping-title">Shopping Cart</div>

        <!-- summary line (item count + total) -->
        <div class="summary-line">
            <?= htmlspecialchars($totalItem) ?> item(s) - Total: Rp <?= number_format($totalPrice, 0, ',', '.') ?>
        </div>
    </div>

    <!-- payment success box (in-page) -->
    <?php if ($paymentSuccess): ?>
        <div class="success-message">
            Payment Success! ID: <?= htmlspecialchars($transactionId) ?>
        </div>
        <a class="back-home-btn" href="homeUser.php">Order Coffee Now</a>
    <?php endif; ?>

    <!-- cart content -->
    <?php if (empty($cart)): ?>
        <p class="no-coffee">Cart is Empty.<br>Order Coffee Now</p>

    <?php else: ?>

        <table class="cart-table">
            <tr>
                <th>Coffee</th>
                <th>Description</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php foreach ($cart as $id => $item):
                $qty = (int)($item['qty'] ?? 0);
                $price = (float)($item['price'] ?? 0);
                $subtotal = $qty * $price;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($item['desc'] ?? '-') ?></td>
                <td>Rp <?= number_format($price, 0, ',', '.') ?></td>

                <td>
                    <form action="updateCart.php" method="POST" style="display:inline;">
                        <input type="hidden" name="coffeeid" value="<?= htmlspecialchars($id) ?>">
                        <input type="number" name="qty" value="<?= $qty ?>" min="1" style="width:70px;padding:6px;">
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </td>

                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>

                <td>
                    <form action="deleteCart.php" method="POST" style="display:inline;">
                        <input type="hidden" name="coffeeid" value="<?= htmlspecialchars($id) ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>


        <form method="POST" style="margin-top:18px;">
            <button type="submit" name="pay" class="pay-btn">Pay</button>
        </form>

    <?php endif; ?>

</div>

<?php include "./utils/footer.php"; ?>

</body>
</html>
