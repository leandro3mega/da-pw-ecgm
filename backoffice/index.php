<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
    exit();
} else {
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $nome = $email = $fotografia = "";
    $tipo = $_SESSION['tipo'];
    $cargo = $_SESSION['cargo'];

    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    if ($tipo == 1) {
        $sql = "SELECT nome, email, fotografia FROM aluno WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "SELECT nome, email, fotografia FROM docente WHERE fk_idutilizador=?";
    }

    if ($tipo == 1 || $tipo == 2) {
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
                    $stmt->bind_result($r_nome, $r_email, $r_foto);
                    if ($stmt->fetch()) {
                        //-- Atribui variaveis
                        $nome = $r_nome;
                        $email = $r_email;
                        $fotografia = $r_foto;
                        $_SESSION['nome'] = $nome;
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Página Inicial</title>

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

<style>
.btn-index-page {
    margin-left: auto;
    margin-right: auto;
    width: -webkit-fill-available;
    min-width: 100px;
    max-width: 400px;
    min-height: 100px;
    font-size: 26px;
    display: grid;
    border-color: rgb(185, 185, 185);
    background-color: #f4f4f4;
    border-radius: 40px;
    overflow: hidden;
    word-wrap: break-word;
}

.btn-index-page:hover {
    background-color: rgb(185, 185, 185);
}
</style>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "sidemenu.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" style="min-height:500px">
                <div class="row" style="display: flex; flex-wrap: wrap; margin-top:30px; margin-bottom:30px;">

                    <div class="col-lg-12" style="width:100%;">

                        <div style="width:100%; text-align: center;">
                            <h1>
                                <?php
                                if ($cargo == "Administrador") {
                                    echo("Olá " . $cargo . "!");
                                } else {
                                    echo("Olá " . $nome . "!");
                                }
                                ?>
                            </h1>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row" style="display: flex; flex-wrap: wrap; margin:0px; margin-top:50px">
                    <?php

                    if ($cargo == "Administrador") {
                        echo "
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='meus-projetos.php' style=''>
                                    <i class='fa fa-th-list fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                    Projetos
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='gerir-docentes.php' style=''>
                                    <i class='fa fa-group fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                    Docentes
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='gerir-alunos.php' style=''>
                                    <i class='fa fa-graduation-cap fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                    Alunos
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='gerir-ucs.php' style=''>
                                    <i class='fa fa-book fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                    Unidades Curriculares
                                </a>
                            </div>
                        </div>
                        ";
                    } elseif ($cargo == "Professor") {
                        echo "
                        <div class='col-lg-12 col-md-12 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='meus-projetos.php' style=''>
                                    <i class='fa fa-th-list fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                        Projetos
                                </a>
                            </div>
                        </div>
                        
                        <!--<div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='gerir-ucs.php' style=''>
                                    <button class='btn btn-default btn-index-page'
                                        style='overflow: hidden; word-wrap: break-word;'>
                                        Unidades Curriculares
                                    </button>
                                </a>
                            </div>
                        </div>-->
                        ";
                    } elseif ($cargo == "Aluno") {
                        echo "
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='meus-projetos.php' style=''>
                                        <i class='fa fa-th-list fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                        Meus Projetos
                                </a>
                            </div>
                        </div>
                        
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn btn-default btn-index-page' href='novo-projeto.php' style=''>
                                    <i class='fa fa-file-o fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                    Novo Projeto
                                </a>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                </div>

                <div class="row">
                    <?php
                    echo("
                    <div class='col-lg-12 col-md-12 col-xs-12' style='margin-top:30px'>
                        <div style='display: flex;'>
                            <a class='btn btn-default btn-index-page' href='../frontoffice/index.php' style=''>
                                <i class='fa fa-home fa-fw' style='color: rgb(66, 66, 66); font-size: 100%; padding:10px; width: auto;'></i>
                                Página Inicial
                            </a>
                        </div>
                    </div>");
                    ?>
                </div>

            </div>
            <!-- /.container-fluid -->

            <?php
                include 'footer.html';
            ?>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

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