<?php
session_start();
include "db.php";
include "navbarGuest.php";

$error = "";
$success = "";

$fullname = "";
$username = "";
$email = "";

function generateUserID($conn) {
    $result = mysqli_query($conn, "SELECT UserID FROM Users ORDER BY UserID DESC LIMIT 1");

    if ($result && mysqli_num_rows($result) == 0) {
        return "U0001";
    } else {
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['UserID'];
        $num = intval(substr($lastID, 1)); 
        $num++; 
        return "U" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // capture POST into variables (trimmed)
    $fullname = trim($_POST['fullname'] ?? "");
    $username = trim($_POST['username'] ?? "");
    $email    = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $role     = "Customer"; 

    // VALIDATION (same logic as before)
    if (empty($fullname) || !preg_match("/^[a-zA-Z ]+$/", $fullname)) {
        $error = "Full name must contain alphabet & spaces only!";
    }
    elseif (empty($username) || !ctype_alpha($username)) {
        $error = "Username must be alphabetic only!";
    }
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
    else {
        $check = mysqli_query($conn, "SELECT * FROM Users WHERE UserName='" . mysqli_real_escape_string($conn, $username) . "' OR UserEmail='" . mysqli_real_escape_string($conn, $email) . "'");
        if ($check && mysqli_num_rows($check) > 0) {
            $error = "Username or Email already exists!";
        }
        elseif (
            strlen($password) < 8 ||
            !preg_match("/[A-Z]/", $password) ||
            !preg_match("/[a-z]/", $password) ||
            !preg_match("/[0-9]/", $password)
        ) {
            $error = "Password must have 8 chars, uppercase, lowercase, and number!";
        }
    }

    if (empty($error)) {
        $UserID = generateUserID($conn);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO Users (UserID, FullName, UserName, UserEmail, UserPassword, UserRole) VALUES (
            '" . mysqli_real_escape_string($conn, $UserID) . "',
            '" . mysqli_real_escape_string($conn, $fullname) . "',
            '" . mysqli_real_escape_string($conn, $username) . "',
            '" . mysqli_real_escape_string($conn, $email) . "',
            '" . mysqli_real_escape_string($conn, $hashedPassword) . "',
            '" . mysqli_real_escape_string($conn, $role) . "'
        )";

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

    <div class="register-container">

        <form class="register-form" method="POST" novalidate>
            <h2>Register</h2>

            <div class="input-box">
                <label for="fullname">Full Name:</label>  
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
            </div>
            
            <div class="input-box">
                <label for="username">Username:</label>  
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="input-box">
                <label for="email">Email:</label>  
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="input-box">
                <label for="password">Password:</label>  
                <input type="password" name="password" value="" required>
            </div>

            <?php if ($error) : ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="register-btn">
                <button type="submit">Register</button>
            </div>
            
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>

</body>
</html>
