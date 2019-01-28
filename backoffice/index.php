<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
    exit();
} else {
    // header("location:meus-projetos.php");

    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $nome;
    $email;
    $tipo = $_SESSION['tipo'];
    $cargo = $_SESSION['cargo'];
    /*
    //-- Converte int em string para mostrar o cargo do user no menu superior
    if ($tipo == 0) $cargo = "Administrador";
    else if ($tipo == 1) $cargo = "Aluno";
    else $cargo = "Professor";
     */
    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    $result = mysqli_query($connectDB, "select * from view_useralunosdocentes where idutilizador=$id");
    if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $nome = ($row['nome']);
        $email = ($row['email']);
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
}
</style>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Area de Utilizador</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right" style="padding-left:10px">

                <li>
                    <?php echo $cargo; ?>
                </li>
                <li><a><i class="fa fa-user fa-fw"></i>
                        <?php echo $username; ?> </a>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Sair</a>

            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <?php include "sidemenu.php";?>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" style="min-height:500px">
                <div class="row" style="display: flex; flex-wrap: wrap; margin-top:30px; margin-bottom:30px;">
                    <!-- <div class="col-lg-12">
                        <h1 class="page-header">Página Vazia</h1>
                    </div> -->
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
                                <a class='btn-index-page' href='meus-projetos.php' style=''>
                                    <button class='btn btn-default btn-index-page' style=''>
                                        Projetos
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='gerir-docentes.php' style=''>
                                    <button class='btn btn-default btn-index-page' style=''>
                                        Docentes
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='gerir-alunos.php' style=''>
                                    <button class='btn btn-default btn-index-page' style=''>
                                        Alunos
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='gerir-ucs.php' style=''>
                                    <button class='btn btn-default btn-index-page'
                                        style='overflow: hidden; word-wrap: break-word;'>
                                        Unidades Curriculares
                                    </button>
                                </a>
                            </div>
                        </div>
                        ";
                    } elseif ($cargo == "Professor") {
                        echo "
                        <div class='col-lg-12 col-md-12 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='meus-projetos.php' style=''>
                                    <button class='btn btn-default btn-index-page' style=''>
                                        Projetos
                                    </button>
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
                                <a class='btn-index-page' href='meus-projetos.php' style=''>
                                    <button class='btn btn-default btn-index-page' style=''>
                                        Meus Projetos
                                    </button>
                                </a>
                            </div>
                        </div>
                        
                        <div class='col-lg-6 col-md-6 col-xs-12' style='margin-top:30px'>
                            <div style='display: flex;'>
                                <a class='btn-index-page' href='gerir-ucs.php' style=''>
                                    <button class='btn btn-default btn-index-page'
                                        style='overflow: hidden; word-wrap: break-word;'>
                                        Novo Projeto
                                    </button>
                                </a>
                            </div>
                        </div>
                        ";
                    }
                    ?>
                    <!-- <div class="col-lg-3" style="margin-top:30px"></div> -->
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