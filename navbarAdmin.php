<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <header>
        <div class="headerContainer">
            <div class="logo">
                <a href="./homeGuest.php">KenanginKopi</a>
            </div>
            
            <div class="dateHeader">        <!-- dipisah gini biar enak nantinya klao ngedit -->    
                <?php echo date('l, d F Y'); ?>
            </div>

            <div class="dropdown">
                <div class="dropdown-content">
                    <a href="#">Manage User</a>
                    <a href="#">Manage Store</a>
                    <a href="#">Manage Coffee</a>
                </div>
            </div>
    
            <nav class="headerButton">
                <a href="#">Login</a>
            </nav>
        </div>
    </header>
</body>
</html>