<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM Store ORDER BY StoreID ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Store - KenanginKopi</title>
    <link rel="stylesheet" href="css/manageStore.css">
</head>
<body>

<?php include "./utils/navbarAdmin.php"; ?>

<div class="container">

    <h2 class="title">Manage Store</h2>

    <a href="addStore.php" class="add-btn">Add Store</a>

    <table class="store-table">
        <tr>
            <th>Store ID</th>
            <th>Store Name</th>
            <th>Location</th>
            <th>Coffee</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['StoreID']; ?></td>
            <td><?= htmlspecialchars($row['StoreName']); ?></td>
            <td><?= htmlspecialchars($row['StoreLocation']); ?></td>

            <td>
                <a href="manageCoffee.php?storeid=<?= $row['StoreID']; ?>" class="manage-btn">Manage</a>
            </td>

            <td>
                <a href="deleteStore.php?storeid=<?= $row['StoreID']; ?>" class="delete-btn" onclick="return confirm('Are you sure want to delete this store?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>

<?php include "./utils/footer.php"; ?>

</body>
</html>
