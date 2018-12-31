<?php
session_start();

require_once("connectdb.php");

$username = $_SESSION['username'];
$id = $_SESSION['id'];
$tipo = $_SESSION['tipo'];

if ($_POST['action'] == 'change_name') {
    $new_nome = $_POST['name'];
    changeName($connectDB, $id, $tipo, $new_nome);

} else if ($_POST['action'] == 'change_email') {
    $new_email = $_POST['email'];
    changeEmail($connectDB, $id, $tipo, $new_email);

} else if ($_POST['action'] == 'change_password') {
    changePassword($connectDB, $id, $tipo, $username);
}

//-- Function that change the name of a user in the DB
function changeName($connectDB, $id, $tipo, $new_nome)
{
    // Attempt update query execution
    if ($tipo == 1)
        $editNomeQuery = "UPDATE aluno SET nome='$new_nome' WHERE fk_idutilizador=$id";
    else if ($tipo == 2)
        $editNomeQuery = "UPDATE docente SET nome='$new_nome' WHERE fk_idutilizador=$id";

    if ($connectDB->query($editNomeQuery) === true) {
        echo "Email Alterado com Sucesso!";
    } else {
        echo "Erro: Não conseguiu alterar o email. " . $connectDB->error;
    }
}

//-- Function that change the email of a user in the DB
function changeEmail($connectDB, $id, $tipo, $new_email)
{
    // Attempt update query execution
    if ($tipo == 1)
        $editNomeQuery = "UPDATE aluno SET email='$new_email' WHERE fk_idutilizador=$id";
    else if ($tipo == 2)
        $editNomeQuery = "UPDATE docente SET email='$new_email' WHERE fk_idutilizador=$id";
    if ($connectDB->query($editNomeQuery) === true) {
        echo "Email Alterado com Sucesso!";
    } else {
        echo "Erro: Não conseguiu alterar o email. " . $connectDB->error;
    }
}

//-- Function that change the password of a user in the DB
function changePassword($connectDB, $id, $tipo, $username)
{
    $pass_old = $_POST['password_old'];
    $pass_new = $_POST['password_new'];

    //echo ("Irá Mudar a passe de " . $pass_old . " para " . $pass_new);
    
    // query à base de dados
    $sqlSelect = "SELECT idutilizador, password FROM utilizador WHERE username=?";

    // inicializar prepared statement
    $stmt = $connectDB->prepare($sqlSelect);

    // md5 para desincriptar a password
    //$password = md5($pass_old);
    $stmt->bind_param("s", $username);

    // executar
    $stmt->execute();

    // associar os parametros de output
    $stmt->bind_result($r_idUtilizador, $r_password);

    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();

    // iterar / obter resultados
    $stmt->fetch();

    //echo ($stmt->num_rows == 1);
    if ($stmt->num_rows == 1) { // seleciona o resultado da base de dados
        //echo ("Para o utilizador " . $id . " " . $username . " com a password (" . $pass_old . ") foi encontrado um com o id: " . $r_idUtilizador . " e com a pass ->" . $r_password);
        //-- Se a password inserida e a na DB forem iguais
        if ($pass_old === $pass_new) {
            echo "Password que pretende inserir é igual à atual!";
        } else if ($r_password === $pass_old) {
            // Attempt update query execution
            $editPassQuery = "UPDATE utilizador SET password='$pass_new' WHERE idutilizador=$id";
            if ($connectDB->query($editPassQuery) === true) {
                echo "Password Alterada com Sucesso!";
            } else {
                echo "Erro: Não conseguiu alterar a password. " . $connectDB->error;
            }
        } else {
            //-- se as password não forem iguais
            echo ("A password atual inserida está errada!");

        }
    }
    $stmt->close();
}


/*
function selectUser($connectDB, $id, $username, $new_nome)
{
    //-- query para obter utilizadore com id = $id
    $userQuery = "select * from utilizador where id=$id";

    if ($userResult = mysqli_query($connectDB, $userQuery)) {
        if (mysqli_num_rows($userResult) > 0) {
            while (!$encontrado && $user = mysqli_fetch_array($userResult)) {
                if ($username === $user['username']) {
                    $tipo =
                        changeName($connectDB, $id, $username, $new_nome, $tipo);

                }
            }
        }
    }
}
 */
// Close connection
$connectDB->close();

?>