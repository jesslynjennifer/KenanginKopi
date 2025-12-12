<?php
session_start();
include './utils/db.php';

// Detect login status & role
$isLoggedIn = isset($_SESSION['UserID']);
$role = $isLoggedIn ? $_SESSION['UserRole'] : 'Guest';

// Tentukan navbar & detail link berdasarkan role
if (!$isLoggedIn) {
    $navbar = "./utils/navbarGuest.php";
    $detailPage = "storeDetailGuest.php";
} else {
    if ($role === "User") {
        $navbar = "./utils/navbarUser.php";
        $detailPage = "storeDetailUser.php";
    } elseif ($role === "Admin") {
        $navbar = "./utils/navbarAdmin.php";
        $detailPage = "storeDetailAdmin.php";
    } else {
        $navbar = "./utils/navbarGuest.php";
        $detailPage = "storeDetailGuest.php";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>KenanginKopi - Home</title>
    <link rel="stylesheet" href="./css/homePage.css">
</head>

<body>
    <header>
        <nav>
            <?php include $navbar; ?>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">

                <h2>KenanginKopi</h2>
                <p>Favourable taste for your mood</p>

                <div class="gridContainer">
                    <?php
                        $sql = "SELECT StoreID, StoreName FROM Store ORDER BY StoreName";
                        $res = mysqli_query($conn, $sql);

                        if ($res && mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $sid = htmlspecialchars($row['StoreID']);
                                $sname = htmlspecialchars($row['StoreName']);

                                echo '<div class="gridTemplate">';
                                echo "  <h4>{$sname}</h4>";
                                echo "  <a class='viewDetailsButton' href='{$detailPage}?storeid={$sid}'>View Details</a>";
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="no-data">No stores available.</p>';
                        }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include './utils/footer.php'; ?>
    </footer>

</body>
</html>
