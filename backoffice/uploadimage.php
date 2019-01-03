<?php

session_start();

require_once("connectdb.php");

$mimeExt = array();
$mimeExt['image/jpeg'] = '.jpg';
$mimeExt['image/pjpeg'] = '.jpg';
$mimeExt['image/bmp'] = '.bmp';
$mimeExt['image/gif'] = '.gif';
$mimeExt['image/x-icon'] = '.ico';
$mimeExt['image/png'] = '.png';

$diretorio = "images/utilizadores/";
$tipo = $_SESSION['tipo'];
$id = $_POST['iduser'];

if (isset($_FILES["avatar"])) { 
     //Begins image upload
    //$user_avatar = md5(uniqid(time())) . $mimeExt[$_FILES["avatar"]["type"]]; //Get image extension
    $user_foto = $id . $mimeExt[$_FILES["avatar"]["type"]]; //Get image extension
    $user_foto_dir = $diretorio . $user_foto; //Path file
    

    //-- Insert image name into de DB
    insertImage($connectDB, $id, $tipo, $user_foto, $user_foto_dir);
    echo ("Nome: " . $user_foto);
    echo ("\nDiretorio: " . $user_foto_dir);

} else {
    //$user_avatar = "default_130x130.png";
    //header("location:dados-pessoais.php");  // se não receber imagem volta a pagina
    echo ("dentro do else");
}

function insertImage($connectDB, $id, $tipo, $user_foto, $user_foto_dir)
{
    echo ("(2)" . $user_foto);
    // Attempt update query execution
    if ($tipo == 1)
        $editFotografiaQuery = "UPDATE aluno SET fotografia='$user_foto' WHERE fk_idutilizador=$id";
    else if ($tipo == 2)
        $editFotografiaQuery = "UPDATE docente SET fotografia='$user_foto' WHERE fk_idutilizador=$id";

    if ($connectDB->query($editFotografiaQuery) === true) {
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $user_foto_dir);
        echo "Fotografia Alterada com Sucesso!";
        header("location:dados-pessoais.php");  // se não receber imagem volta a pagina
    } else {
        echo "Erro: Não conseguiu alterar a fotografia. " . $connectDB->error;
    }
}


?>