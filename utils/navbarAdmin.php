<?php
session_start();

// harus admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login.php");
    exit;
}
?>

<link rel="stylesheet" href="../css/navbar.css">

<header>
    <nav>
        <div class="navbar">

        <div class="logo>
            <a class="company" href="homeAdmin.php">KenanginKopi</a>
        </div>

        
        <div class="navbarDate">
            <?php echo date('l, d F Y'); ?>
        </div>

        <div class="navbarMenu">
            <div class="dropdown">
                <button class="dropbtn">Manage ▼</button>
                <div class="dropdown-content">
                    <a href="manage_user.php">Manage User</a>
                    <a href="manage_store.php">Manage Store</a>
                    <a href="manage_coffee.php">Manage Coffee</a>
                </div>
            </div>

            <a class="username" href="profile.php">
                <?php echo $_SESSION['username']; ?>
            </a>

            <a class="logoutBtn" href="../logout.php">Logout</a>

            </div>
        </div>
    </nav>
</header>
