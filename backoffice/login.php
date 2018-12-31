<?php
session_start();

require_once("connectdb.php");

// -- if user was found or not
$found = false;

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
    while (!$found && $user = $usersResult->fetch_object()) {
        echo "<li>" . $user->idutilizador . " | " . $user->username . " | " . $user->password . " | " . $user->tipo . "</li>";

        $USER = $user->username;
        $PASS = $user->password;
        $ID = $user->idutilizador;
        $TIPO = $user->tipo;


        if (isset($_SESSION['username'])) {
            header("location:loginpage.php");
        } else if (!(isset($_POST['username']) || !(isset($_POST['password'])))) {
            // se recebeu post com username e password vazios
            header("location:loginpage.php");
        } else if ($_POST['username'] !== $USER || $_POST['password'] !== $PASS) {
            // se o post é diferente do existente na DB
            header("location:loginpage.php");
        } else {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $ID;
            $_SESSION['tipo'] = $TIPO;

            //-- Get the data to add into the SESSION
            getData($connectDB, $ID, $TIPO);

            //echo ("(1) Nome na sessão: " . $_SESSION['nome']);

            //echo "</br><li>" . $USER . " | " . $PASS . "</li>";
            $found = true;
            header("location:loginpage.php");
        }
    }
}

function getData($connectDB, $ID, $TIPO)
{
    //-- é aluno
    if ($TIPO == 1) {
        $dataQuery = "select * from aluno where fk_idutilizador = '$ID'";

        $dataResult = $connectDB->query($dataQuery);
    
        // check for errors
        if ($connectDB->errno) {
            exit("query error");
        } else {
            $dataResult->data_seek(0);
            while ($data = $dataResult->fetch_object()) {
                //echo "<li>" . $user->idutilizador . " | " . $user->username . " | " . $user->password . " | " . $user->tipo . "</li>";
                $_SESSION['nome'] = $data->nome;
                //echo ("(2) Nome na sessão: " . $_SESSION['nome']);
                //$_SESSION['fotografia'] = $data->fotografia;

            }
        }
    }
}

session_write_close();
// Close connection
$connectDB->close();;
exit();
?>