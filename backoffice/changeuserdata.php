<?php
session_start();

require_once("connectdb.php");

$username = $_SESSION['username'];
$id = $_SESSION['id'];
$tipo = $_SESSION['tipo'];

if ($_POST['action'] == 'change_name') {
    changeName($connectDB, $id, $tipo);
} elseif ($_POST['action'] == 'change_email') {
    changeEmail($connectDB, $id, $tipo);
} elseif ($_POST['action'] == 'change_password') {
    changePassword($connectDB, $id, $tipo, $username);
} elseif ($_POST['action'] == 'change_fotografia') {
    changeFotografia($connectDB, $id, $tipo);
}

//-- Function that change the name of a user in the DB
function changeName($connectDB, $id, $tipo)
{
    // Attempt update query execution
    if ($tipo == 1) {
        $sql = "UPDATE aluno SET nome=? WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "UPDATE docente SET nome=? WHERE fk_idutilizador=?";
    }

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_nome, $param_idutilizador);

        // Set parameters
        $param_nome = trim($_POST['name']);
        $param_idutilizador = (int)$id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Nome Alterado com Sucesso!";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Function that change the email of a user in the DB
function changeEmail($connectDB, $id, $tipo)
{
    // Attempt update query execution
    if ($tipo == 1) {
        $sql = "UPDATE aluno SET email=? WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "UPDATE docente SET email=? WHERE fk_idutilizador=?";
    }

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_email, $param_idutilizador);

        // Set parameters
        $param_email = trim($_POST['email']);
        $param_idutilizador = (int)$id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Email Alterado com Sucesso!";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
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

//-- Function that change the photo of a user in the DB
function changeFotografia($connectDB, $id, $tipo)
{
    $mimeExt = array();
    $mimeExt['image/jpeg'] = '.jpg';
    $mimeExt['image/pjpeg'] = '.jpg';
    $mimeExt['image/png'] = '.png';

    //-- Definicao de variaveis de tamanho
    define('KB', 1024);
    define('MB', 1048576);
    define('GB', 1073741824);
    define('TB', 1099511627776);

    $tamanhoMax = 1 * MB;
    $image = ($_FILES["fotografia"]['tmp_name']);
    $imageinfo = getimagesize($image);  // intem informação do tamanho da imagem


    deleteOldFoto($connectDB, $id, $tipo);

    if ($tipo == 1) {
        $sql = "UPDATE aluno SET fotografia=? WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "UPDATE docente SET fotografia=? WHERE fk_idutilizador=?";
    }

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_fotografia, $param_id);

        // Set parameters
        $param_id = $id;

        //-- Se não existir foto, aborta
        if (!isset($_FILES['fotografia']) || $_FILES['fotografia']['size'] > $tamanhoMax || $imageinfo[0] > 1960 || $imageinfo[1] > 1080) {
            header("location: dados-pessoais.php");
            echo "Sem foto";

            return;
        } else {
            //-- Caso contrario insere a foto selecionada na pasta e na DB
            $diretorio = "images/utilizadores/";
            $type = mime_content_type($_FILES['fotografia']['tmp_name']);
                
            //Begins image upload
            $id_fotografia = md5(uniqid(time())) . $mimeExt[$_FILES["fotografia"]["type"]]; //Get image extension
            $user_foto_dir = $diretorio . $id_fotografia; //Path file
            $param_fotografia = $id_fotografia;

            //--Move image
            move_uploaded_file($_FILES["fotografia"]["tmp_name"], $user_foto_dir);
        }

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            echo "Fotografia mudada com sucesso";
            header("location: dados-pessoais.php");
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
}

//-- Apaga a foto antiga do user
function deleteOldFoto($connectDB, $id, $tipo)
{
    $diretorio = "images/utilizadores/";

    if ($tipo == 1) {
        $sql = "SELECT fotografia FROM aluno WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "SELECT fotografia FROM docente WHERE fk_idutilizador=?";
    }

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_idutilizador);
            
        // Set parameters
        $param_idutilizador = $id;
            
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_nome);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    unlink($diretorio . $r_nome);   // apaga foto com um nome
                }
            } else {
                // echo"Não foi encontrado video ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
    $stmt->close();
}


// Close connection
$connectDB->close();