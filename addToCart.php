<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$coffeeid = $_POST['coffeeid'];
$storeid = $_POST['storeid'];

$q = "
    SELECT CoffeeName, CoffeeDesc, Price
    FROM Coffee 
    JOIN StoreCoffee ON Coffee.CoffeeID = StoreCoffee.CoffeeID
    WHERE Coffee.CoffeeID = '$coffeeid' AND StoreCoffee.StoreID = '$storeid'
";
$res = mysqli_query($conn, $q);
$coffee = mysqli_fetch_assoc($res);

if (!$coffee) {
    die("Coffee not found");
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$coffeeid])) {
    $_SESSION['cart'][$coffeeid]['qty'] += 1;
} else {
    // Kalau belum ada → masukkan item
    $_SESSION['cart'][$coffeeid] = [
        "name" => $coffee['CoffeeName'],
        "desc" => $coffee['CoffeeDesc'],
        "price" => $coffee['Price'],
        "qty" => 1
    ];
}

header("Location: cart.php");
exit;
