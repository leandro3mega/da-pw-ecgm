<?php
session_start();
if (!isset($_SESSION['username']))  {
    header("location:index.php");
    exit();
} 
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1> olá <?php echo $username; ?></h1>
    <a href="logout.php">LOGOUT </a>
</body>
</html>