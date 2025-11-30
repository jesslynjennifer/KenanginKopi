<?php
session_start();

$paymentSuccess = false;


if (isset($_POST['pay'])) {
    unset($_SESSION['cart']);   // Hapus semua isi cart
    $paymentSuccess = true;     // Tampilkan pesan sukses
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
</head>
<body>

    <div class="cart-container">
        <h1>Your Cart</h1>

        <!-- Jika payment sukses -->
        <?php if ($paymentSuccess): ?>
            <p class="success-message">Payment Successful!</p>
            <a href="homeUser.php" class="back-home-btn">Back to Home</a>
            exit;
        <?php endif; ?>


        <!-- Jika cart kosong -->
        <?php if (empty($cart)): ?>
            <p class="no-coffee">No coffee added yet.</p>

        <?php else: ?>

            <?php
            $totalItem = 0;
            $totalPrice = 0;
            ?>

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
                    $subtotal = $item['price'] * $item['qty'];
                    $totalItem += $item['qty'];
                    $totalPrice += $subtotal;
                    ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['desc'] ?></td>
                        <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>

                        <td>
                            <form action="updateCart.php" method="POST">
                                <input type="hidden" name="coffeeid" value="<?= $id ?>">
                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1">
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </td>

                        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>

                        <td>
                            <form action="deleteCart.php" method="POST">
                                <input type="hidden" name="coffeeid" value="<?= $id ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

            <div class="total-box">
                Total Items: <?= $totalItem ?> <br>
                Total Price: Rp <?= number_format($totalPrice, 0, ',', '.') ?>
            </div>

            <form method="POST">
                <button type="submit" name="pay" class="pay-btn">Pay</button>
            </form>

        <?php endif; ?>
    </div>

</body>
</html>
