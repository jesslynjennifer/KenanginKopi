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

                <?php if (!isset($_SESSION['UserRole'])): ?>
                    <div class="login-btn">
                        <a href="login.php">Login</a>
                    </div>

                <?php elseif ($_SESSION['UserRole'] === "User"): ?>
                    <a href="profile.php">
                        <?php echo $_SESSION['UserName']; ?>
                    </a>

                    <a class="logout-btn" href="logout.php">Logout</a>

                <?php elseif ($_SESSION['UserRole'] === "Admin"): ?>
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
                <?php endif; ?>

            </div>
        </div>
    </nav>
</header>
