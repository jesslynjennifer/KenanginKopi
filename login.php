<?php
session_start();
include "db.php";
include "navbarGuest.php";

$error = "";

// Auto login via cookie (optional)
if (!isset($_SESSION['logged_in_user']) && isset($_COOKIE['remember_user'])) {
    $_SESSION['logged_in_user'] = $_COOKIE['remember_user'];
    header("Location: home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    // Check empty
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled!";
    } else {

        // Cek username di DB
        $query = "SELECT * FROM Users WHERE UserName = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            // Password hashing verification
            if (password_verify($password, $user['UserPassword'])) {

                // Set session
                $_SESSION['logged_in_user'] = $user['UserID'];
                $_SESSION['role'] = $user['UserRole'];
                $_SESSION['username'] = $user['UserName'];

                // Remember me
                if ($remember) {
                    setcookie("remember_user", $user['UserID'], time() + (7 * 24 * 60 * 60), "/");
                }

                if ($user['UserRole'] == "Admin") {
                    header("Location: admin/home_admin.php");
                } else {
                    header("Location: user/home_user.php");
                }
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
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <main>
        <div class="login-container">
            <form class="login-form" method="POST">
                <h2>Login</h2>
                <div class="input-box">
                    <label for="username">Username</label>
                    <input type="text" name="username">
                </div>

                <div class="input-box">
                    <label for="password">Password</label>
                    <input type="password" name="password">
                </div>

                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <div class="login-btn">
                    <button type="submit">Login</button>
                </div>

                <div class="register">
                    <p>Don’t have an account? <a href="register.php"> Register here!</a></p>
                </div>

                <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
            </form>


        </div>
    </main>
</div>
</body>
</html>