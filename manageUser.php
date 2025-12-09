<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}

$query = "SELECT UserID, FullName, UserName, UserEmail FROM Users WHERE UserRole != 'Admin'";
$res = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage User - KenanginKopi</title>
    <link rel="stylesheet" href="./css/manageUser.css">
</head>
<body>

    <?php include "./utils/navbarAdmin.php"; ?>

    <main>
            <div class="container">

        <h2 class="title">Manage User</h2>

        <table class="user-table">
            <tr>
                <th>UserID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>

            <?php while ($u = mysqli_fetch_assoc($res)) : ?>
                <tr>
                    <td><?= htmlspecialchars($u['UserID']); ?></td>
                    <td><?= htmlspecialchars($u['FullName']); ?></td>
                    <td><?= htmlspecialchars($u['UserName']); ?></td>
                    <td><?= htmlspecialchars($u['UserEmail']); ?></td>

                    <td>
                        <form method="POST" action="deleteUser.php">
                            <input type="hidden" name="userid" value="<?= $u['UserID'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

    </div>
    </main>

    <?php include "./utils/footer.php"; ?>

</body>
</html>
