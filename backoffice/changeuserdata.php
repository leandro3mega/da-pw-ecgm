<?php
session_start();

require_once("connectdb.php");

$username = $_SESSION['username'];
$id = $_SESSION['id'];
$tipo = $_SESSION['tipo'];

if ($_POST['action'] == 'change_name') {
    $new_nome = $_POST['name'];
    changeName($connectDB, $id, $tipo, $new_nome);
} elseif ($_POST['action'] == 'change_email') {
    $new_email = $_POST['email'];
    changeEmail($connectDB, $id, $tipo, $new_email);
} elseif ($_POST['action'] == 'change_password') {
    changePassword($connectDB, $id, $tipo, $username);
}

//-- Function that change the name of a user in the DB
function changeName($connectDB, $id, $tipo, $new_nome)
{
    // Attempt update query execution
    if ($tipo == 1) {
        $editNomeQuery = "UPDATE aluno SET nome='$new_nome' WHERE fk_idutilizador=$id";
    } elseif ($tipo == 2) {
        $editNomeQuery = "UPDATE docente SET nome='$new_nome' WHERE fk_idutilizador=$id";
    }

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
    if ($tipo == 1) {
        $editNomeQuery = "UPDATE aluno SET email='$new_email' WHERE fk_idutilizador=$id";
    } elseif ($tipo == 2) {
        $editNomeQuery = "UPDATE docente SET email='$new_email' WHERE fk_idutilizador=$id";
    }
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
    $pass_new2 = $_POST['password_new_2'];

    $pode_mudar = false;

    if ($pass_new === $pass_new2) {
        // query à base de dados
        $sqlSelect = "SELECT password FROM utilizador WHERE idutilizador=?";
    
        // inicializar prepared statement
        $stmt = $connectDB->prepare($sqlSelect);
    
        $stmt->bind_param("i", $id);
        // executar
        $stmt->execute();
        // associar os parametros de output
        $stmt->bind_result($r_password);
        // transfere o resultado da última query : obrigatorio para ter num_rows
        $stmt->store_result();
        // iterar / obter resultados
        $stmt->fetch();
    
        //echo ($stmt->num_rows == 1);
        if ($stmt->num_rows == 1) { // seleciona o resultado da base de dados
            //-- Se a password inserida e a na DB forem iguais
            if (password_verify($pass_old, $r_password)) {
                $pode_mudar = true;
            // echo("A password atual inserida está Correta!");
            } else {
                //-- se as password não forem iguais
                echo("A password atual inserida está errada!");
            }
        }

        if ($pode_mudar) {
            $sql = "UPDATE utilizador SET password=? WHERE idutilizador=?";
    
            if ($stmt = $connectDB->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $param_password, $param_id);
    
                $param_password = password_hash($pass_new, PASSWORD_DEFAULT); // Creates a password hash
                $param_id = $id;
    
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to login page
                    echo "Palavra passe mudada com sucesso";
                } else {
                    echo "</br>Something went wrong. Please try again later.";
                }
            }
        }
        $stmt->close();
    } else {
        echo "A nova palavra passe e a confirmada não são iguais!";
        // $msg = "A nova palavra passe e a confirmada não são iguais!";
        // echo '<script language="javascript">';
        // echo 'alert("' . $msg . '");';
        // echo 'window.location.replace("gerir-ucs.php");';
        // echo '</script>';
    }
}

// Close connection
$connectDB->close();