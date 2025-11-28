<?php
session_start();
include "db.php";

$error = "";
$success = "";

// Generate UserID CHAR(5) — contoh: U0001
function generateUserID($conn) {
    $result = mysqli_query($conn, "SELECT UserID FROM Users ORDER BY UserID DESC LIMIT 1");

    if (mysqli_num_rows($result) == 0) {
        return "U0001";
    } else {
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['UserID'];   // U0007 → ambil 0007
        $num = intval(substr($lastID, 1)); 
        $num++; 
        return "U" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = "Customer"; // default

    // 🌟 VALIDATION

    // Full Name: wajib alfabet + spasi
    if (empty($fullname) || !preg_match("/^[a-zA-Z ]+$/", $fullname)) {
        $error = "Full name must contain alphabet & spaces only!";
    }
    // Username: wajib alfabet
    elseif (empty($username) || !ctype_alpha($username)) {
        $error = "Username must be alphabetic only!";
    }
    // Email: cek format manual
    elseif (
        empty($email) ||
        strpos($email, "@") === false ||
        strpos($email, ".") === false ||
        str_starts_with($email, "@") ||
        str_starts_with($email, ".") ||
        str_ends_with($email, "@") ||
        str_ends_with($email, ".") ||
        str_contains($email, "@@") ||
        str_contains($email, "..") ||
        str_contains($email, "@.") ||
        str_contains($email, ".@")
    ) {
        $error = "Invalid email format!";
    }
    // Cek duplicate email/username
    else {
        $check = mysqli_query($conn, "SELECT * FROM Users WHERE UserName='$username' OR UserEmail='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username or Email already exists!";
        }
        // Password rules
        elseif (
            strlen($password) < 8 ||
            !preg_match("/[A-Z]/", $password) ||
            !preg_match("/[a-z]/", $password) ||
            !preg_match("/[0-9]/", $password)
        ) {
            $error = "Password must have 8 chars, uppercase, lowercase, and number!";
        }
    }

    // Jika tidak ada error → proses register
    if (empty($error)) {

        $UserID = generateUserID($conn);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO Users (UserID, FullName, UserName, UserEmail, UserPassword, UserRole)
                  VALUES ('$UserID', '$fullname', '$username', '$email', '$hashedPassword', '$role')";

        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - KenanginKopi</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="reg-container">
    <h2>Register</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        Full Name  
        <input type="text" name="fullname">

        Username  
        <input type="text" name="username">

        Email  
        <input type="text" name="email">

        Password  
        <input type="password" name="password">

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>

</body>
</html>
