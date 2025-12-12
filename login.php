<?php
session_start();
unset($_SESSION['logged_in_user']);
unset($_SESSION['role']);
unset($_SESSION['username']);


include "./utils/db.php";
$error = "";

if (!isset($_SESSION['UserID']) && isset($_COOKIE['remember_user'])) {
    $uid = $_COOKIE['remember_user'];
    $q = "SELECT * FROM Users WHERE UserID = '$uid'";
    $r = mysqli_query($conn, $q);

    if (mysqli_num_rows($r) == 1) {
        $user = mysqli_fetch_assoc($r);

        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['UserRole'] = $user['UserRole'];
        $_SESSION['UserName'] = $user['UserName'];

        header("Location: " . 
            ($user['UserRole'] == "Admin" ? "homeAdmin.php" : "homeUser.php"));
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled!";
    } else {

        $query = "
        SELECT * FROM Users 
        WHERE UserName = '$username'
        ";
        
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['UserPassword'])) {

                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['UserRole'] = $user['UserRole'];
                $_SESSION['UserName'] = $user['UserName'];

                    if (isset($_POST['remember'])) {
                        setcookie("remember_user", $user['UserID'], time() + (7 * 24 * 60 * 60), "/");
                    }

                header("Location: " . 
                    ($user['UserRole'] == "Admin" ? "homeAdmin.php" : "index.php"));
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
    <header>
        <nav>
            <?php include "./utils/navbarGuest.php"; ?>
        </nav>
    </header>

    <main>
        <div class="login-container">
            <form class="login-form" method="POST">
                <h2>Login</h2>
                <div class="input-box">
                    <label for="username">Username:</label>
                    <input type="text" name="username">
                </div>

                <div class="input-box">
                    <label for="password">Password:</label>
                    <input type="password" name="password">
                </div>

                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <div class="login-btn">
                    <button type="submit">Login</button>
                </div>

                <div class="register-link">
                    <p>Don’t have an account? <a href="register.php"> Register here!</a></p>
                </div>

                <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
            </form>
        </div>
    </main>
</div>
    <footer>
        <?php include './utils/footer.php'; ?>
    </footer>
</body>
</html>