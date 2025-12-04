<?php

session_start();
include "./utils/db.php";
$message = "";
$error = "";
// if (!isset($_SESSION['UserID'])) {
//     header("Location: login.php");
//     exit;
// }

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $coffeeName = $_POST['coffeeName'];
    $coffeePrice = $_POST['coffeePrice'];
    $coffeeDescription = $_POST['coffeeDescription'];

    //validasi nama
    if(empty($coffeeName)){
        $error = "Name must be filled.";
    }
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $coffeeName)) {
        $error = "Name must be alphabetic only.";
    }

    //valdiasi harga
    elseif (!is_numeric($coffeePrice) || $coffeePrice < 10000 || $coffeePrice > 100000) {
        $error = "Price must be between 10.000 and 100.000.";
    }

    //validasi deskripsi
    elseif (empty($coffeeDescription)) {
        $error = "Description must be filled.";
    } 
    elseif (str_word_count($coffeeDescription) < 3) {
        $error = "Description must be minimum 3 words.";
    }

    if($message =="" && $error == ""){
        //bagian ID nya biar bisa auto kek "C0001" dst
        $queryGetLast = "SELECT CoffeeID FROM coffee ORDER BY CoffeeID DESC LIMIT 1";
        $resultLast = mysqli_query($conn, $queryGetLast);
        $rowLast = mysqli_fetch_assoc($resultLast);
    
        if ($rowLast) {
            //ngecek angka trahkir
            $lastID = $rowLast['CoffeeID'];
            $number = (int)substr($lastID, 1); 
            $number++; 
        } else {//klo kosong
            $number = 1;
        }
    
        //format id coffee kita
        $newID = "C" . sprintf("%04s", $number);
    
        $query = "INSERT INTO coffee (CoffeeID, CoffeeName, CoffeePrice, CoffeeDesc) VALUES ('$newID','$coffeeName', '$coffeePrice', '$coffeeDescription')";
    
        if(mysqli_query($conn, $query)){
            $message = "Added ". $coffeeName;
        }else {
            $message = "Add failed";
        }
    }


}




?>
<!-- masih blm ada css -->
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
            <label for="coffeeName">Coffee Name : </label> <br>
            <input type="text" name="coffeeName" id="coffeeName"> <br>

            <label for="coffeePrice">Price : </label> <br>
            <input type="text" name="coffeePrice" id="coffeePrice"> <br>

            <label for="coffeeDescription">Description : </label> <br>
            <input type="text" name="coffeeDescription" id="coffeeDescription"> <br>

            <button type="submit">Add</button> <br>

            <a href=""><< back</a>
        </form>
        <?php echo $message; ?>
        <?php echo $error; ?>
    </main>
</body>
</html>