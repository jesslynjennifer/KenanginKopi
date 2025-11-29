<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] != "Customer") {
    header("Location: ../login.php");
    exit;
}
?>

<link rel="stylesheet" href="../css/navbar.css">

<header>
    <nav>
        <div class="navbar">

        <div class="logo">
            <a href="homeUser.php">KenanginKopi</a>
        </div>

        
        <div class="navbarDate">
            <?php echo date('l, d F Y'); ?>
        </div>

        <div class="navbarMenu">
            <a class="usernameNavbar" href="profile.php">
                <?php echo $_SESSION['username']; ?>
            </a>

            <a class="logoutBtn" href="../logout.php">Logout</a>
    </nav>
</header>

