<?php

session_start();

clearstatcache();   // limpa a cache

require_once("connectdb.php");

$mimeExt = array();
$mimeExt['image/jpeg'] = '.jpg';
$mimeExt['image/pjpeg'] = '.jpg';
$mimeExt['image/bmp'] = '.bmp';
$mimeExt['image/gif'] = '.gif';
$mimeExt['image/x-icon'] = '.ico';
$mimeExt['image/png'] = '.png';

//-- Definicao de variaveis de tamanho
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

$diretorio = "images/utilizadores/";
$tipo = $_SESSION['tipo'];
$id = null;
if (isset($_POST['iduser'])) $id = $_POST['iduser'];
$tamanhoMax = 0.5 * MB;
$change = false;    // se a imagem pode ou não ser mudada/inserida
$type = mime_content_type($_FILES['avatar']['tmp_name']);


// limite de tamanho da imagem
if ($_FILES['avatar']['size'] > $tamanhoMax) {
    echo "<script type='text/javascript'>alert('A tamanho da imagem excede o limite. Insira uma imagem com menos de 500KB'); window.location.replace('dados-pessoais.php');</script>";
    //exit("A tamanho da imagem excede o limite.");
    $change = false;
} else {
    echo ("\nO tamanho é inferior ao permitido");
    $change = true;
}

// limita o tipo de imagem suportado
if ($type !== "image/jpeg" && $type !== "image/png" && $change) {
    //echo ("Tipo não permitido");
    echo "<script type='text/javascript'>alert('A imagem não é permitida. Insira uma imagem .png ou .jpeg'); window.location.replace('dados-pessoais.php');</script>";
    $change = false;

} else {
    //echo ("Tipo permitido");
    $change = true;
}

if ($change) {
    $image = ($_FILES["avatar"]['tmp_name']);
    $imageinfo = getimagesize($image);  // intem informação do tamanho da imagem
    
    //-- Limita a resolução -> $imageinfo[0] = width | $imageinfo[1] = height
    if ($imageinfo[0] > 1500 || $imageinfo[1] > 1000) {
        echo "<script type='text/javascript'>alert('A imagem possui uma resolução demasiado grande. Insira uma imagem com dimensões até 1920x1080.'); window.location.replace('dados-pessoais.php');</script>";
        //echo ("A imagem possui uma resolução demasiado grande!");
        $change = false;
    } else {
        //echo ("A imagem possui uma resolução aceitavel!");
        $change = true;

    }
}

//-- Se existir ficheiro
if (isset($_FILES["avatar"]) && $change) { 
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
    header("location:dados-pessoais.php");  // se não receber imagem volta a pagina
    echo ("dentro do else");
}

//-- Insere imagem na DB
function insertImage($connectDB, $id, $tipo, $user_foto, $user_foto_dir)
{
    // Attempt update query execution
    if ($tipo == 1)
        $editFotografiaQuery = "UPDATE aluno SET fotografia='$user_foto' WHERE fk_idutilizador=$id";
    else if ($tipo == 2)
        $editFotografiaQuery = "UPDATE docente SET fotografia='$user_foto' WHERE fk_idutilizador=$id";

    if ($connectDB->query($editFotografiaQuery) === true) {
        //-- move imagem para o projeto
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $user_foto_dir);

        echo "Fotografia Alterada com Sucesso!";
        header("location:dados-pessoais.php");  // volta a pagina
    } else {
        echo "Erro: Não conseguiu alterar a fotografia. " . $connectDB->error;
    }
}


?>