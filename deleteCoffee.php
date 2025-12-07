<?php

session_start();
include "./utils/db.php";


if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['coffeeid']) || !isset($_POST['storeid'])) {
    header("Location: manageStore.php"); 
    exit;
}

$coffeeid = $_POST['coffeeid'];
$storeid = $_POST['storeid'];
$stmt = mysqli_prepare($conn, "DELETE FROM StoreCoffee WHERE CoffeeID = ? AND StoreID = ?");
if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ss", $coffeeid, $storeid);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: manageCoffee.php?StoreID=" . urlencode($storeid));
    exit;
} else {
    die("Delete failed: " . mysqli_error($conn));
}

?>