<?php
$conn = mysqli_connect("localhost", "root", "", "kenanginkopi");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>