<?php

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);


if ($_POST['action'] == 'check_pdf') {
    //$file = $_REQUEST['ficheiro'];

    echo("no script");

    $change = true;

    checkFicheiro($change);
}
    
function checkFicheiro(&$change)
{
    $tamanhoMax = 0.5 * MB;

    $type = mime_content_type($_FILES['file']['tmp_name']);

    // limite de tamanho
    if ($_POST['file']['size'] > $tamanhoMax) {
        //echo "<script type='text/javascript'>alert('A tamanho da imagem excede o limite. Insira uma imagem com menos de 500KB'); window.location.replace('dados-pessoais.php');</script>";
        echo "('A tamanho da imagem excede o limite. Insira uma imagem com menos de 500KB'); window.location.replace('dados-pessoais.php')";
        //exit("A tamanho da imagem excede o limite.");
        $change = false;
    } else {
        echo("\nO tamanho é inferior ao permitido");
        $change = true;
    }

    // limita o tipo de imagem suportado
    if ($type !== "application/pdf" && $change) {
        //echo ("Tipo não permitido");
        //echo "<script type='text/javascript'>alert('A imagem não é permitida. Insira uma imagem .png ou .jpeg'); window.location.replace('dados-pessoais.php');</script>";
        echo "('A imagem não é permitida. Insira uma imagem .png ou .jpeg'); window.location.replace('dados-pessoais.php')";
        $change = false;
    } else {
        //echo ("Tipo permitido");
        $change = true;
    }
    /*
    if ($change) {
        $image = ($_FILES["file"]['tmp_name']);
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
    }*/
}