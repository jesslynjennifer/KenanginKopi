<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

function generateStoreID($conn) {
    $query = "SELECT StoreID FROM Store ORDER BY StoreID DESC LIMIT 1";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) == 0) {
        return "S0001";
    }

    $row = mysqli_fetch_assoc($res);
    $lastID = intval(substr($row["StoreID"], 1));
    $newID = $lastID + 1;

    return "S" . str_pad($newID, 4, "0", STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $location = trim($_POST["location"]);

    if (empty($name)) {
        $error = "Store name must be filled!";
    } elseif (str_word_count($name) < 2) {
        $error = "Store name must be minimum 2 words!";
    } elseif (empty($location)) {
        $error = "Location must be selected!";
    }

    if (empty($error)) {
        $storeID = generateStoreID($conn);

        $safeName = mysqli_real_escape_string($conn, $name);
        $safeLoc  = mysqli_real_escape_string($conn, $location);

        $insert = "
            INSERT INTO Store (StoreID, StoreName, StoreLocation)
            VALUES ('$storeID', '$safeName', '$safeLoc')
        ";

        if (mysqli_query($conn, $insert)) {
            header("Location: manageStore.php");
            exit;
        } else {
            $error = "Insert failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Store</title>
    <link rel="stylesheet" href="./css/addStore.css">
</head>

<body>
    <header>
        <nav>
            <?php include "./utils/navbarAdmin.php"; ?>
        </nav>
    </header>

    <main>
        <div class="add-container">
            <h2 class="title">Add Store</h2>

            <form method="POST" class="add-form">
                <div class="input-box">
                    <label>Store Name:</label>
                    <input type="text" name="name" placeholder="Enter store name">
                </div>

                <div class="input-box">
                    <label>Store Location:</label>
                    <select name="location">
                        <option value="">– Choose Location –</option>
                        <option value="Jakarta">Jakarta</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Yogyakarta">Yogyakarta</option>
                        <option value="Medan">Medan</option>
                        <option value="Bali">Bali</option>
                    </select>
                </div>

                <?php if ($error): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>

                <button type="submit" class="add-btn">Add Store</button>

                <a href="manageStore.php" class="back-btn">← Back</a>
            </form>
        </div>
    </main>

    <footer>
        <?php include "./utils/footer.php"; ?>
    </footer>

</body>
</html>
