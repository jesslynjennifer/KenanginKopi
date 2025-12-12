<?php
session_start();
include "./utils/db.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['UserID'];
$error = "";

$q = "SELECT * FROM Users WHERE UserID = '$userid'";
$res = mysqli_query($conn, $q);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    die("User not found");
}

$username = $user['UserName'];
$email = $user['UserEmail'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);

    if ($newUsername === "") {
        $error = "Username must be filled!";
    } elseif (!ctype_alpha($newUsername)) {
        $error = "Username must be alphabetic only!";
    } elseif ($newEmail === "") {
        $error = "Email must be filled!";
    } else {
        if (substr_count($newEmail, '@') != 1) {
            $error = "Email must contain exactly one '@'";
        } elseif (!str_contains($newEmail, '.')) {
            $error = "Email must contain at least one '.'";
        } elseif ($newEmail[0] == '@' || $newEmail[0] == '.') {
            $error = "Email cannot start with '@' or '.'";
        } elseif ($newEmail[-1] == '@' || $newEmail[-1] == '.') {
            $error = "Email cannot end with '@' or '.'";
        } elseif (
            str_contains($newEmail, '@@') ||
            str_contains($newEmail, '..') ||
            str_contains($newEmail, '@.') ||
            str_contains($newEmail, '.@')
        ) {
            $error = "Email format is invalid!";
        }
    }

    if ($error === "") {
        $update = "
            UPDATE Users 
            SET UserName = '$newUsername',
                UserEmail = '$newEmail'
            WHERE UserID = '$userid'
        ";

        mysqli_query($conn, $update);

        $_SESSION['UserName'] = $newUsername;

        header("Location: profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Profile - KenanginKopi</title>
    <link rel="stylesheet" href="./css/editProfile.css">
</head>

<body>
    <header>
        <nav>
            <?php include "./utils/navbar.php"; ?>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2 class="title">Edit Profil</h2>

            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" class="profile-form">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">

                <label>Email:</label>
                <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">

                <button class="save-btn" type="submit">Save</button>
            </form>

            <a href="profile.php" class="back-btn">← Back</a>
        </div>
    </main>

    <footer>
        <?php include "./utils/footer.php"; ?>
    </footer>
</body>
</html>
