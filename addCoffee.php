<?php

session_start();
include "./utils/db.php";
$message = "";
$error = "";


if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== "Admin") {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['StoreID'])) {
    $error = "Store ID is missing.";
    
    $StoreID = null; 
} else {
    $StoreID = $_GET['StoreID'];
}


function generateCoffeeID($conn) {
    
    $query = "SELECT CoffeeID FROM Coffee ORDER BY CoffeeID DESC LIMIT 1";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) == 0) {
        return "C0001";
    }

    $row = mysqli_fetch_assoc($res);
    $lastID = intval(substr($row["CoffeeID"], 1));
    $newID = $lastID + 1;

    
    return "C" . str_pad($newID, 4, "0", STR_PAD_LEFT);
}


if($_SERVER['REQUEST_METHOD'] == "POST" && $StoreID){
    $coffeeName = $_POST['coffeeName'];
    $coffeePrice = $_POST['coffeePrice'];
    $coffeeDescription = $_POST['coffeeDescription'];
    
    
    $postedStoreID = $_POST['StoreID'];
    
    
    if(empty($coffeeName)){
        $error = "Name must be filled.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $coffeeName)) {
        $error = "Name must be alphabetic only.";
    } elseif (!is_numeric($coffeePrice) || $coffeePrice < 10000 || $coffeePrice > 100000) {
        $error = "Price must be between 10.000 and 100.000.";
    } elseif (empty($coffeeDescription)) {
        $error = "Description must be filled.";
    } elseif (str_word_count($coffeeDescription) < 3) {
        $error = "Description must be minimum 3 words.";
    }
    
    elseif ($postedStoreID !== $StoreID) {
        $error = "Security error: Store ID mismatch.";
    }


    if(empty($error)){
        
        $newID = generateCoffeeID($conn);
        
        
        $stmt_coffee = mysqli_prepare($conn, "INSERT INTO Coffee (CoffeeID, CoffeeName, CoffeePrice, CoffeeDesc) VALUES (?, ?, ?, ?)");
        
        
        
        if ($stmt_coffee) {
            mysqli_stmt_bind_param($stmt_coffee, "siss", $newID, $coffeeName, $coffeePrice, $coffeeDescription);
            
            if (mysqli_stmt_execute($stmt_coffee)) {
                
                
                $stmt_storecoffee = mysqli_prepare($conn, "INSERT INTO StoreCoffee (StoreID, CoffeeID, Price) VALUES (?, ?, ?)");
                
                if ($stmt_storecoffee) {
                    mysqli_stmt_bind_param($stmt_storecoffee, "ssd", $StoreID, $newID, $coffeePrice);
                    
                    if (mysqli_stmt_execute($stmt_storecoffee)) {
                        $message = "Added " . htmlspecialchars($coffeeName) . " successfully!";
                        
                        header("Location: manageCoffee.php?StoreID=" . urlencode($StoreID));
                        exit;
                    } else {
                        mysqli_query($conn, "DELETE FROM Coffee WHERE CoffeeID = '$newID'");
                        $error = "Add failed: Could not link coffee to store. " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt_storecoffee);
                }
                
            } else {
                $error = "Add failed: Could not add coffee data. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt_coffee);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AddCoffee</title>
</head>
<body>
    <header>
        </header>

    <main>
        <form action="" method="POST">
            
            <input type="hidden" name="StoreID" value="<?= htmlspecialchars($StoreID ?? '') ?>">

            <label for="coffeeName">Coffee Name : </label> <br>
            <input type="text" name="coffeeName" id="coffeeName" value="<?= htmlspecialchars($coffeeName ?? '') ?>"> <br>

            <label for="coffeePrice">Price : </label> <br>
            <input type="text" name="coffeePrice" id="coffeePrice" value="<?= htmlspecialchars($coffeePrice ?? '') ?>"> <br>

            <label for="coffeeDescription">Description : </label> <br>
            <input type="text" name="coffeeDescription" id="coffeeDescription" value="<?= htmlspecialchars($coffeeDescription ?? '') ?>"> <br>

            <button type="submit">Add</button> <br>

            <a href="manageCoffee.php?StoreID=<?= urlencode($StoreID ?? '') ?>"><< back</a>
        </form>
        
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= $message; ?></p>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= $error; ?></p>
        <?php endif; ?>
    </main>
</body>
</html>