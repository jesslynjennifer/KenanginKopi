<?php
session_start();

$id = $_POST['coffeeid'];
$qty = intval($_POST['qty']);

if ($qty < 1) {
    die("Quantity must be more than 0.");
}

$_SESSION['cart'][$id]['qty'] = $qty;

header("Location: cart.php");
exit;
?>