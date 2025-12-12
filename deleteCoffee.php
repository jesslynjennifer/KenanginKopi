<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: manageStore.php");
    exit;
}

$coffeeID = $_POST["coffeeid"];
$storeID = $_POST["storeid"];

$stmt1 = mysqli_prepare($conn, "DELETE FROM StoreCoffee WHERE CoffeeID = ? AND StoreID = ?");
mysqli_stmt_bind_param($stmt1, "ss", $coffeeID, $storeID);
mysqli_stmt_execute($stmt1);
mysqli_stmt_close($stmt1);

$stmtCheck = mysqli_prepare($conn, "SELECT CoffeeID FROM StoreCoffee WHERE CoffeeID = ?");
mysqli_stmt_bind_param($stmtCheck, "s", $coffeeID);
mysqli_stmt_execute($stmtCheck);
$checkRes = mysqli_stmt_get_result($stmtCheck);
$stillLinked = mysqli_num_rows($checkRes) > 0;
mysqli_stmt_close($stmtCheck);

if (!$stillLinked) {
    $stmt2 = mysqli_prepare($conn, "DELETE FROM Coffee WHERE CoffeeID = ?");
    mysqli_stmt_bind_param($stmt2, "s", $coffeeID);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
}

header("Location: manageCoffee.php?storeid=" . $storeID);
exit;
