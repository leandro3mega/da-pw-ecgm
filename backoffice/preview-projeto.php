<?php
session_start();

// $action = $_GET['action'];
$titulo = $_POST['preview_titulo'];
$descricao =  $_POST['preview_descricao'];
$autores =  $_POST['preview_autores'];
$palavras =  $_POST['preview_palavras'];
$uc =  $_POST['preview_uc'];
$data = "25 de janeiro";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Preview</title>

    <!-- Icon image -->
    <link rel="icon" href="images/website/logotipo_transparente.png">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="row" style="display: flex; flex-wrap: wrap; margin:50px 30px 30px 30px; ">

        <!-- <div class="col-lg-6" style="background-color: rgb(79, 216, 132);"> -->
        <div class="col-lg-6">
            <img src="images/projetos/imagens/25_0.jpg" style="max-width:100%; height:auto" alt="">

        </div>
        <!-- <div class="col-lg-6" style="background-color: rgb(13, 140, 243);"> -->
        <div class="col-lg-6">
            <div>
                <h3 style="margin-top: 0px;"><?php echo($titulo); ?></h3>
                <p style="min-height:190px; line-height: 1.5; font-size: 16px;"><?php echo($descricao);?></p>
                <p><b>Autores: </b> <?php echo($autores); ?></p>
                <p><b>Unidade Curricular: </b><?php echo($uc); ?></p>
                <p><b>Data: </b><?php echo($data); ?></p>
                <p><b>Palavras-Chave: </b> <?php echo($palavras); ?></p>
            </div>
        </div>
    </div>



    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>