<?php
session_start();

unset($_SESSION['UserID']);
unset($_SESSION['Username']);
unset($_SESSION['Role']);
unset($_SESSION['FullName']);
unset($_SESSION['Email']);

header("Location: homeGuest.php");
exit();
?>
