<?php
session_start();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
</head>
<body>

<h1>Your Cart</h1>

<?php if (empty($cart)): ?>
    <p>No coffee added yet.</p>
<?php else: ?>

    <?php
    $totalItem = 0;
    $totalPrice = 0;
    ?>

    <table border="1" cellpadding="10">
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
                        <button type="submit">Update</button>
                    </form>
                </td>

                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>

                <td>
                    <form action="deleteCart.php" method="POST">
                        <input type="hidden" name="coffeeid" value="<?= $id ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <br><br>
    <h3>Total Item: <?= $totalItem ?></h3>
    <h3>Total Price: Rp <?= number_format($totalPrice, 0, ',', '.') ?></h3>

    <form action="pay.php" method="POST">
        <button type="submit">Pay</button>
    </form>

<?php endif; ?>

</body>
</html>
