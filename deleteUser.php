<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['userid'])) {
    header("Location: manageUser.php");
    exit;
}

$userid = mysqli_real_escape_string($conn, $_POST['userid']);

$delete = "DELETE FROM Users WHERE UserID = '$userid'";
mysqli_query($conn, $delete);

header("Location: manageUser.php");
exit;
