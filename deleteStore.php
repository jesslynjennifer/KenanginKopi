<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['storeid'])) {
    header("Location: manageStore.php");
    exit;
}

$storeid = mysqli_real_escape_string($conn, $_GET['storeid']);

mysqli_query($conn, "DELETE FROM StoreCoffee WHERE StoreID = '$storeid'");

mysqli_query($conn, "DELETE FROM Store WHERE StoreID = '$storeid'");

header("Location: manageStore.php");
exit;
?>
