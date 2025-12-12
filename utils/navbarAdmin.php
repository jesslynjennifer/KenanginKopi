<?php
if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="css/navbar.css">

<header>
    <nav>
        <div class="navbar">

            <div class="logo">
                <a href="index.php">KenanginKopi</a>
            </div>

            <div class="navbarDate">
                <?php echo date('l, d F Y'); ?>
            </div>

            <div class="navbarMenu">

            <div class="dropdown">
                <button class="dropbtn">Manage ▾</button>

                <div class="dropdown-content">
                    <a href="manageUser.php">Manage User</a>
                    <a href="manageStore.php">Manage Store</a>
                    <a href="manageCoffee.php">Manage Coffee</a>
                </div>
            </div>
            
            <div class="admin-username">
                <?php echo $_SESSION['UserName']; ?>
            </div>

                <a class="logout-btn" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
</header>
