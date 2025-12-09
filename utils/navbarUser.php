<?php
if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "User") {
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="css/navbar.css">

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
                <a href= "profile.php">
                    <?php echo $_SESSION['UserName']; ?>
                </a>

                <a class="logout-btn" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
</header>
