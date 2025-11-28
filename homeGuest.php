<?php
// homeGuest.php
session_start();
include 'db.php';           // koneksi DB (pastikan path benar)
include 'navbarGuest.php';  // navbar untuk guest
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>KenanginKopi - Home</title>
<link rel="stylesheet" href="./css/homeGuest.css">
</head>
<body>
    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>KenanginKopi</h2>
                <p>Favourable taste for your mood</p>

                <div class="gridContainer">
                    <?php
                    // Ambil semua store dari DB
                    $sql = "SELECT StoreID, StoreName FROM Store ORDER BY StoreName";
                    $res = mysqli_query($conn, $sql);

                    if ($res && mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $sid = htmlspecialchars($row['StoreID']);
                            $sname = htmlspecialchars($row['StoreName']);
                            echo '<div class="gridTemplate">';
                            echo "  <h4>{$sname}</h4>";
                            echo "  <a class='viewDetailsButton' href='storeDetail.php?storeid={$sid}'>View Details</a>";
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

<?php include 'footer.php'; ?>
</body>
</html>
