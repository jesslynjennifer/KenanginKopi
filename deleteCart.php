<?php
session_start();

$id = $_POST['coffeeid'];
unset($_SESSION['cart'][$id]);

header("Location: cart.php");
exit;
?>