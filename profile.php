<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['UserID'];

$query = "SELECT * FROM Users WHERE UserID = '$userid'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - KenanginKopi</title>
    <link rel="stylesheet" href="./css/profile.css">
</head>

<body>

<?php include "./utils/navbarUser.php"; ?>

<div class="profile-container">

    <h2 class="title">My Profile</h2>

    <div class="profile-box">

        <div class="row">
            <span class="label">User ID</span>
            <span class="value"><?= htmlspecialchars($user['UserID']); ?></span>
        </div>

        <div class="row">
            <span class="label">Full Name</span>
            <span class="value"><?= htmlspecialchars($user['FullName']); ?></span>
        </div>

        <div class="row">
            <span class="label">Username</span>
            <span class="value"><?= htmlspecialchars($user['UserName']); ?></span>
        </div>

        <div class="row">
            <span class="label">Email</span>
            <span class="value"><?= htmlspecialchars($user['UserEmail']); ?></span>
        </div>

        <div class="btn-group">

            <a href="editProfile.php" class="btn full-btn">Edit Profile</a>

            <a href="history.php" class="btn full-btn">Order History</a>

            <a href="homeUser.php" class="back-btn">← Back</a>
        </div>

    </div>

</div>

<?php include "./utils/footer.php"; ?>

</body>
</html>
