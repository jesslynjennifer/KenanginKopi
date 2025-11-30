<?php
session_start();
include './utils/db.php';

// ADMIN ONLY PAGE
if (!isset($_SESSION['UserID']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>KenanginKopi - Admin Home</title>
<link rel="stylesheet" href="./css/homePage.css">
</head>

<body>

<?php include './utils/navbarAdmin.php'; ?>

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
                        echo "  <a class='viewDetailsButton' href='storeDetailAdmin.php?storeid={$sid}'>View Details</a>";
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

<?php include './utils/footer.php'; ?>

</body>
</html>
