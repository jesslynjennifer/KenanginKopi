<?php
session_start();
include "db.php";  // file koneksi ke database

// Auto Login if remember me cookie exists
if (!isset($_SESSION['logged_in_user']) && isset($_COOKIE['remember_user'])) {
    $_SESSION['logged_in_user'] = $_COOKIE['remember_user'];
    header("Location: home.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $remember = isset($_POST["remember"]);

    // Validation
    if (empty($username) || empty($password)) {
        $error = "Username and Password must be filled!";
    } else {

        // Check user in DB
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            if ($password === $user['password']) { // gunakan password_hash kalau mau lebih secure

                // SET SESSION
                $_SESSION['logged_in_user'] = $user['UserID'];
                $_SESSION['role'] = $user['Role'];
                $_SESSION['username'] = $user['username'];

                // REMEMBER ME
                if ($remember) {
                    setcookie("remember_user", $user['UserID'], time() + (7 * 24 * 60 * 60), "/");
                }

                // Redirect setelah berhasil login
                header("Location: home.php");
                exit;

            } else {
                $error = "Wrong password!";
            }

        } else {
            $error = "Username not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="login-container">
    <h2 style="text-align:center;">Login</h2>

    <form method="POST">
        Username
        <input type="text" name="username">

        Password
        <input type="password" name="password">

        <label>
            <input type="checkbox" name="remember"> Remember me
        </label>

        <button type="submit">Login</button>
    </form>

    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>

    <div class="register">
        <a href="register.php">Don’t have an account? Register</a>
    </div>
</div>
</body>
</html>