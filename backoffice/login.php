<?php
session_start();

require_once("connectdb.php");

//-- query para obter utilizadores
$usersQuery = "select * from utilizador";

$usersResult = $connectDB->query($usersQuery);

// check for errors
if ($connectDB->errno) {
    exit("query error");
} else {
    /*
    // fetch results
    while($user = $usersResult->fetch_row()) {
        echo "<li>".$user[1]." : ".$user[2]."</li>";
    }
     */
    // fetch results as object
    $usersResult->data_seek(0);
    while ($user = $usersResult->fetch_object()) {
        echo "<li>" . $user->idutilizador . " | " . $user->username . " | " . $user->password . " | " . $user->tipo . "</li>";


        $PASS = $user->username;
        $USER = $user->password;
        if (isset($_SESSION['username'])) {
            header("location:loginpage.php");
        } else if (!(isset($_POST['username']) || !(isset($_POST['password'])))) {
            header("location:loginpage.php");
        } else if ($_POST['username'] !== $USER || $_POST['password'] !== $PASS) {
            header("location:loginpage.php");
        } else {
            $_SESSION['username'] = $_POST['username'];
            header("location:loginpage.php");
        }
    }
}

session_write_close();
// Close connection
mysqli_close($connectDB);
exit();
?>