<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:../backoffice/iniciar-sessao.php");
    exit();
} else {
    // header("location:meus-projetos.php");

    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $nome;
    $email;
    $tipo = $_SESSION['tipo'];
    $cargo = $_SESSION['cargo'];
    $username = $_SESSION['username'];

    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    if ($tipo == 1) {
        $sql = "SELECT nome, email, fotografia FROM aluno WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "SELECT nome, email, fotografia FROM docente WHERE fk_idutilizador=?";
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
                $stmt->bind_result($r_nome, $r_email, $r_foto);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    $nome = $r_nome;
                    $email = $r_email;
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

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Design de Ambientes</title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/thumbnail-gallery.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="font/stylesheet.css" rel="stylesheet" />

    <link href="../backoffice/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="icon" href="../backoffice/images/website/logotipo_transparente.png">

    <script src="js/index.js"></script>

    <style>
    /* .dropdown-submenu {
        position: relative;
    } */

    /* .dropdown-submenu .dropdown-menu {
        left: 20%;
        margin-top: -1px;
    } */

    .link-menu {
        color: #000;
        text-decoration: none;
    }

    .link-menu:hover {
        color: #868686;
        text-decoration: none;

    }

    .dropbtn {
        background-color: rgb(238, 238, 238);
        color: black;
        padding: 5px;
        font-size: 13px;
        border-radius: 5px;
        border: 1px solid rgb(185, 185, 185);
        margin: 0 0 0 5px;
    }


    .dropup {
        position: relative;
        display: inline-block;
    }

    .dropup-content {
        display: none;
        position: absolute;
        /* background-color: #f1f1f1; */
        background-color: rgb(245, 245, 245);
        min-width: 160px;
        bottom: 30px;
        z-index: 1;
        margin-top: auto;
        margin-bottom: auto;
        border: 1px solid rgb(185, 185, 185);
        cursor: pointer;
    }

    .dropup-content label {
        color: black;
        padding: 5px 5px;
        text-decoration: none;
        display: inline-flex;
        min-height: 40px;
    }

    .dropup-content label p {
        font-size: 13px;
    }

    .dropup-content label:hover {
        background-color: #ccc
    }

    .dropup:hover .dropup-content {
        display: block;
    }

    .dropup:hover .dropbtn {
        background-color: rgb(185, 185, 185);
    }

    /* Scroll For dropup menu */
    .scrollable-menu {
        height: auto;
        max-height: 300px;
        overflow-x: hidden;
    }

    .img-projeto {
        min-width: 100%;
        min-height: auto;
        max-width: 100%;
        max-height: 100%;
        height: 150px;
        border: none;
        border-radius: 0px;
        padding: 0px;
        overflow: hidden;
        object-fit: cover;
    }

    .scale-site {}

    body {
        padding-top: 40px !important;
    }
    </style>
</head>

<body>

    <!-- Page Content -->
    <div class="container scale-site">
        <div class="row" style="min-height: 456px;">

            <div class=" col-md-11">
                <div class="row text-center text-lg-left0 " data-grid-projetos></div>


            </div>
            <div class="col-md-1">
                <!-- <div class="row" style="display: inline-block; height: 100%;"> -->

                <div class="align-top" style="display: flex; height: 50%; align-items: start;">
                    <a data-pagina-baixo style="margin:0px auto 0px auto">
                        <i class='fa fa-chevron-up fa-fw' style="font-size: 200%;"></i>
                    </a>
                </div>
                <div class="align-bottom" style="display: flex; height: 50%;align-items: flex-end;">
                    <a data-pagina-cima style="margin:0px auto 0px auto">
                        <i class='fa fa-chevron-down fa-fw' style="font-size: 200%;"></i>
                    </a>
                </div>

                <!-- </div> -->
            </div>
        </div>

        <!-- MENU -->
        <div class="row">
            <div style="display: -ms-flexbox; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap; margin-top:10px">
                <div class="col-md-1 col-xs-12">

                    <a href="index.php"><img src="img/logo.png" alt="Italian Trulli"
                            style="max-width: 100%; height: auto;"></a>
                </div>

                <!-- Filtros -->
                <div class="col-md-6 col-xs-12" style="margin-top:auto; margin-bottom:auto;">
                    <div style="display:inline-flex; flex-wrap: wrap;">

                        <!-- UC -->
                        <div>
                            <div class="dropup">
                                <button class="dropbtn custom_font" style="min-width:120px">Unidade
                                    Curricular</button>
                                <!-- <div class="dropup-content dropdown-menu scrollable-menu" -->
                                <div class="dropup-content scrollable-menu" style="min-width:250px;">

                                    <li class="dropdown-submenu" style="display: grid;">
                                        <?php
                                        $result = mysqli_query($connectDB, "select unidade_curricular.* from unidade_curricular");
                                        
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = $result->fetch_assoc()) { // percorre o array
                                                echo "
                                                <label class='custom_font'>
                                                    <input style='margin:auto 0px auto 0px' type='checkbox' data-tabela='unidade_curricular' data-tipo='idunidade_curricular' data-valor='".($row['idunidade_curricular'])."'/>
                                                    <p style='padding-left: 5px; margin:auto 0px auto 0px'>".($row['nome'])."</p>
                                                </label>";
                                            }
                                        }
                                        ?>

                                    </li>
                                </div>
                            </div>
                        </div>

                        <!-- Ano Letivo -->
                        <div>
                            <div class="dropup">
                                <button class="dropbtn custom_font" style="min-width:90px">Ano Letivo</button>
                                <div class="dropup-content scrollable-menu">

                                    <li class="dropdown-submenu" style="display: grid;">
                                        <?php
                                        $result = mysqli_query($connectDB, "select ano from projeto GROUP BY ano");

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = $result->fetch_assoc()) { // percorre o array
                                        
                                                $ano_letivo= ($row['ano']);
                            
                                                echo "
                                                <label class='custom_font' >
                                                    <input style='margin:auto 0px auto 0px' type='checkbox' data-tabela='projeto' data-tipo='ano' data-valor='".$ano_letivo."'>
                                                    <p style='padding-left: 5px; margin:auto 0px auto 0px'>".$ano_letivo." Ano</p>
                                                </label>";
                                            }
                                        }

                                        ?>

                                    </li>
                                </div>
                            </div>
                        </div>

                        <!-- Semestre -->
                        <div>

                            <div class="dropup">
                                <button class="dropbtn custom_font" style="min-width:90px">Semestre</button>
                                <div class="dropup-content scrollable-menu">

                                    <li class="dropdown-submenu" style="display: grid;">
                                        <?php
                                        $result = mysqli_query($connectDB, "select projeto.semestre from projeto GROUP BY semestre");
  
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = $result->fetch_assoc()) { // percorre o array
                                        
                                                $semestre= ($row['semestre']);
                            
                                                echo "
                                                <label class='custom_font' data-filtro>
                                                <input style='margin:auto 0px auto 0px' type='checkbox' data-tabela='projeto' data-tipo='semestre' data-valor='".$semestre."'/>
                                                <p style='padding-left: 5px; margin:auto 0px auto 0px'>".$semestre." Semestre</p>
                                                </label>
                                                ";
                                            }
                                        }

                                        ?>

                                    </li>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div>

                            <div class="dropup">
                                <button class="dropbtn custom_font" style="min-width:70px">Tipo</button>
                                <div class="dropup-content scrollable-menu">

                                    <li class="dropdown-submenu" style="display: grid;">
                                        <!-- <ul class="dropdown-menu"> -->
                                        <label class='custom_font'>
                                            <input style='margin:auto 0px auto 0px' type="checkbox"
                                                data-tabela="projeto" data-tipo='tipo' data-valor='1' />
                                            <p style='padding-left: 5px; margin:auto 0px auto 0px'>Teórico</p>
                                        </label>
                                        <label class='custom_font'>
                                            <input style='margin:auto 0px auto 0px' type="checkbox"
                                                data-tabela="projeto" data-tipo='tipo' data-valor='2' />
                                            <p style='padding-left: 5px; margin:auto 0px auto 0px'>Prático</p>
                                        </label>
                                        <!-- </ul> -->

                                    </li>
                                </div>
                            </div>
                        </div>

                        <!-- Ano -->
                        <div>

                            <div class="dropup">
                                <button class="dropbtn custom_font" style="min-width:70px">Ano</button>
                                <div class="dropup-content scrollable-menu">

                                    <li class="dropdown-submenu" style="display: grid;">
                                        <?php
                                         $result = mysqli_query($connectDB, "SELECT data, SUBSTRING(data,1,4) as anos from projeto GROUP BY anos ORDER BY anos");
                            
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = $result->fetch_assoc()) { // percorre o array
                                        
                                                $ano = ($row['anos']);
                                        
                                                echo "
                                                <label class='custom_font' data-filtro>
                                                    <input style='margin:auto 0px auto 0px' type='checkbox' data-tabela='projeto' data-tipo='data' data-valor='".$ano."'/>
                                                    <p style='padding-left: 5px; margin:auto 0px auto 0px'>".$ano."</p>
                                                </label>
                                                ";
                                            }
                                        }


                                        ?>

                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END: Filtros -->
                <div class="col-md-5" style="margin:0px 0px 0px 0px; display:flex;">
                    <!-- Pesquisa -->
                    <div class="col-md-5 col-xs-12 custom_font" style="margin:0px 0px 0px 0px; display:flex;">
                        <!-- <h6>Pesquisa</h6> -->
                        <div class="form-inline">
                            <input type="search" class="form-control" data-tabela="palavra_chave" data-tipo="palavra"
                                data-valor="" data-procura="procura" placeholder="Pesquisar..." style="width:90%">
                            <!-- <input type="search" data-tabela="projeto" data-tipo="titulo" data-valor=""
                                data-procura="procura" placeholder="Procurar por titulo"> -->
                            <!-- <button data-btn-pesquisa
                                style='width:10%; height:100%; background: Transparent no-repeat; border: none; margin-top:auto; margin-bottom:auto'> -->
                            <a data-btn-pesquisa style="margin-top:auto; margin-bottom:auto; width:10%; height:auto;"><i
                                    class='fa fa-search fa-fw' style="font-size: 150%;"></i></a>
                            <!-- </button> -->
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12  custom_font " style="margin-top:0px; display:flex;">
                        <!-- Nome | <span id="myText"></span><a id="signout_button"  href="#"> logout </a> -->
                        <div
                            style="width:fit-content; margin-left:auto; zoom:100%; margin-top:auto; margin-bottom:auto; font-size:13px">
                            <?php
                            if ($cargo === "Administrador") {
                                echo("<a class='link-menu custom-font' href='../backoffice/index.php'>".$username."</a>");
                            } else {
                                echo("<a class='link-menu custom-font'  href='../backoffice/index.php'>".$nome."</a>");
                            }
                            ?>
                            | <span id="myText"></span><a id="signout_button" class="link-menu custom-font"
                                href="../backoffice/logout.php"> Logout
                            </a>
                        </div>
                        <div>
                            <!-- <button type="button" data-pagina-baixo> Baixo</button> -->
                            <!-- <button type="button" data-pagina-baixo
                                style='background: Transparent no-repeat; border: none;'>
                                <a><i class='fa fa-chevron-up fa-fw' style="font-size: 200%;"></i></a>
                            </button>
                            <button type="button" data-pagina-cima
                                style='background: Transparent no-repeat; border: none;'>
                                <a><i class='fa fa-chevron-down fa-fw' style="font-size: 200%;"></i></a>
                            </button> -->
                            <!-- Botão com icon para pesquisa -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>