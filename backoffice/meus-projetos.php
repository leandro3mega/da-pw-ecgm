<?php
require_once("connectdb.php");

session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} else {
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

// Close connection
//$connectDB->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
    <?php 
    if ($tipo == 0) echo ("Projetos");
    else if ($tipo == 1) echo ("Meus Projetos");
    else echo ("Projetos");
    ?>
    </title>

    <!-- Browser image -->
    <link rel="icon" href="images/website/logotipo_transparente.png">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

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
                <a class="navbar-brand" href="">Area de Utilizador</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <li> <?php echo $cargo; ?> </li>
                <li><a><i class="fa fa-user fa-fw"></i> <?php echo $username; ?> </a>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Sair</a>

            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        
                        <li>
                            <a href="meus-projetos.php"><i class="fa fa-th-list fa-fw"></i> Meus Projetos</a>
                        </li>

                        <li>
                            <a href="novo-projeto.php"><i class="fa fa-file-o fa-fw"></i> Novo Projeto</a>
                        </li>
                        
                        <li>
                            <a href="#"><i class="fa fa-gear fa-fw"></i> Editar Conta<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="alterar-password.php"><i class="fa fa-key fa-fw"></i> Alterar Palavra Passe</a>
                                </li>
                                <li>
                                    <a href="dados-pessoais.php"><i class="fa fa-edit fa-fw"></i> Alterar Dados Pessoais</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php 
                            if ($tipo == 0) echo ("Projetos");
                            else if ($tipo == 1) echo ("Meus Projetos");
                            else echo ("Projetos");
                            ?>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <!--
                            <div class="panel-heading">
                                DataTables Advanced Tables
                            </div>
                            -->
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    
                                        <!--###### Start of script ######-->
                                        <?php 
                                        //require_once("connectdb.php");
                                        if ($tipo == 0) {
                                            selectProjetoAdmin($connectDB);
                                        } else if ($tipo == 1) {
                                            selectProjetoAluno($connectDB, $id);

                                        } else {
                                            selectProjetoDocente($connectDB, $id);

                                        }

                                        //-- Seleciona todos os projetos existentes na DB
                                        function selectProjetoAdmin($connectDB)
                                        {
                                            echo
                                                "<thead>
                                                    <tr>
                                                        <th style='max-width:150px'>Imagem</th>
                                                        <th>Título</th>
                                                        <th style='max-width:60px'>Data</th>
                                                        <th>Autor(es)</th>
                                                        <th>Unidade Curricular</th>
                                                        <th style='max-width:88px'>Ações</th>
                                                   </tr>
                                                </thead>" .
                                                "<tbody>";

                                            $sql = mysqli_query($connectDB, "SELECT idprojeto, titulo, descricao, data, ano, semestre, tipo, fk_iduc FROM projeto");

                                            while ($row = $sql->fetch_assoc()) {
                                                $idUC = $row['fk_iduc'];

                                                $nomeUC;
                                                selectUC($connectDB, $idUC, $nomeUC);   //-- Obtem o nome da UC

                                                //-- Identifier do projeto
                                                $idProjeto = $row['idprojeto'];
                                                $autoresProjeto = "";
                                                selectAlunoProjeto($connectDB, $idProjeto, $autoresProjeto);   //-- Obtem os autores do projeto

                                                //-- Descrição do projeto
                                                $descricaoProjeto = $row['descricao'];
                                                $descricaoProjetostr = substr($descricaoProjeto, 0, 30) . "...";    //-- substring que limita o tamanho da descrição

                                                //-- Data que o projeto foi finalizado
                                                $dataProjeto = $row['data'];
                                                $diaProjetostr = substr($dataProjeto, 0, -6);    //-- substring que limita o tamanho da descrição
                                                $mesProjetostr = substr($dataProjeto, -6, -4);    //-- substring que limita o tamanho da descrição
                                                $anoProjetostr = substr($dataProjeto, -4);    //-- substring que limita o tamanho da descrição
                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //-- Print a new table line
                                                echo

                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'><img class='img-fluid img-thumbnail' src='" . $imageURL . $imageName . "' alt=''></td>" .
                                                    "<td style='vertical-align: middle'>" . $row['titulo'] . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $autoresProjeto . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                    "</ul>" .
                                                    "</td>" .
                                                    "</tr>";

                                            }
                                            echo "</tbody>";
                                        }

                                        //-- Seleciona apenas os projetos do utilizador da sessão
                                        function selectProjetoAluno($connectDB, $id)
                                        {
                                            echo
                                                "<thead>
                                                    <tr>
                                                        <th style='max-width:150px'>Imagem</th>
                                                        <th>Título</th>
                                                        <th style='max-width:60px'>Data</th>
                                                        <th>Unidade Curricular</th>
                                                        <th style='max-width:88px'>Ações</th>
                                                    </tr>
                                                </thead>" .
                                                "<tbody>";
                                            $sql = mysqli_query($connectDB, "SELECT p.idprojeto, p.titulo, p.descricao, p.data, p.ano, p.semestre, p.tipo, p.fk_iduc FROM projeto p, aluno_projeto ap WHERE fk_aluno=$id AND p.idprojeto=ap.fk_projeto");

                                            while ($row = $sql->fetch_assoc()) {
                                                $idUC = $row['fk_iduc'];    //-- Identifier do projeto
                                                $idProjeto = $row['idprojeto'];
                                                $nomeUC;
                                                selectUC($connectDB, $idUC, $nomeUC);   //-- Obtem o nome da UC

                                                //-- Descrição do projeto
                                                $descricaoProjeto = $row['descricao'];
                                                $descricaoProjetostr = substr($descricaoProjeto, 0, 30) . "...";    //-- substring que limita o tamanho da descrição

                                                //-- Data que o projeto foi finalizado
                                                $dataProjeto = $row['data'];
                                                $diaProjetostr = substr($dataProjeto, 0, -6);    //-- substring que limita o tamanho da descrição
                                                $mesProjetostr = substr($dataProjeto, -6, -4);    //-- substring que limita o tamanho da descrição
                                                $anoProjetostr = substr($dataProjeto, -4);    //-- substring que limita o tamanho da descrição
                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //-- Print a new table line
                                                echo
                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'><img class='img-fluid img-thumbnail' src='" . $imageURL . $imageName . "' alt=''></td>" .
                                                    "<td style='vertical-align: middle'>" . $row['titulo'] . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                    "</ul>" .
                                                    "</td>" .
                                                    "</tr>";
                                            }
                                            echo "</tbody>";
                                        }

                                        //-- Seleciona os projetos para as UC do docente na sessão
                                        function selectProjetoDocente($connectDB, $id)
                                        {
                                            echo
                                                "<thead>
                                                    <tr>
                                                        <th style='max-width:150px'>Imagem</th>
                                                        <th>Título</th>
                                                        <th style='max-width:60px'>Data</th>
                                                        <th>Tipo</th>
                                                        <th>Unidade Curricular</th>
                                                        <th style='max-width:88px'>Ações</th>
                                                    </tr>
                                                </thead>" .
                                                "<tbody>";

                                            $sql = mysqli_query($connectDB, "SELECT p.idprojeto, p.titulo, p.descricao, p.data, p.ano, p.semestre, p.tipo, p.fk_iduc 
                                                                            FROM projeto p, docente_projeto dp, unidade_curricular uc
                                                                            WHERE dp.fk_docente=$id AND p.idprojeto=dp.fk_projeto AND uc.fk_idutilizador=$id
                                                                            GROUP BY p.idprojeto");

                                            while ($row = $sql->fetch_assoc()) {
                                                $idUC = $row['fk_iduc'];
                                                $idProjeto = $row['idprojeto'];

                                                $nomeUC;
                                                selectUC($connectDB, $idUC, $nomeUC);   //-- Obtem o nome da UC

                                                //$autoresProjeto = "";
                                                //selectAlunoProjeto($connectDB, $idProjeto, $autoresProjeto);   //-- Obtem os autores do projeto

                                                $descricaoProjeto = $row['descricao'];
                                                $descricaoProjetostr = substr($descricaoProjeto, 0, 30) . "...";    //-- substring que limita o tamanho da descrição

                                                $dataProjeto = $row['data'];
                                                $diaProjetostr = substr($dataProjeto, 0, -6);    //-- substring que limita o tamanho da descrição
                                                $mesProjetostr = substr($dataProjeto, -6, -4);    //-- substring que limita o tamanho da descrição
                                                $anoProjetostr = substr($dataProjeto, -4);    //-- substring que limita o tamanho da descrição
                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- Tipo de projeto
                                                //####
                                                $tProjeto = $row['tipo'];
                                                if ($tProjeto == 1) $tipoProjeto = "Teórico";
                                                else $tipoProjeto = "Prático";

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //echo (alert($row['titulo'] . " | " . $dataProjetostr . " | " . $tipo . " | " . $nomeUC));
                                            //-- Print a new table line
                                                echo
                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'><img class='img-fluid img-thumbnail' src='" . $imageURL . $imageName . "' alt=''></td>" .
                                                    "<td style='vertical-align: middle'>" . $row['titulo'] . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $tipoProjeto . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
                                                    "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                    "</ul>" .
                                                    "</td>" .
                                                    "</tr>";
                                            }
                                            echo "</tbody>";
                                        }

                                        //-- Select do nome da UC associada a um projeto pelo id da UC
                                        function selectUC($connectDB, $idUC, &$nomeUC)
                                        {
                                            $resultUC = mysqli_query($connectDB, "SELECT nome FROM unidade_curricular WHERE idunidade_curricular=$idUC");
                                            if (mysqli_num_rows($resultUC) == 1) {
                                                $row = $resultUC->fetch_assoc();
                                                $nomeUC = ($row['nome']);
                                            }
                                        }
                                        
                                        //-- Select do nome dos autores do projeto (com formatação dos nomes)
                                        function selectAlunoProjeto($connectDB, $idProjeto, &$autoresProjeto)
                                        {
                                            $count = 0;
                                            $resultQuery = mysqli_query($connectDB, "SELECT a.nome FROM aluno a, aluno_projeto ap WHERE ap.fk_projeto=$idProjeto AND a.fk_idutilizador=ap.fk_aluno ORDER BY a.nome");
                                            if (mysqli_num_rows($resultQuery) > 0) {    // se existerem resultados
                                                while ($row = $resultQuery->fetch_assoc()) { // enquanto houverem resultados
                                                    if ($count == 0) $autoresProjeto .= $row['nome'];
                                                    else $autoresProjeto .= ",\n" . $row['nome'];

                                                    $count++;
                                                }
                                                $autoresProjeto .= ".";
                                            }
                                        }

                                        //-- Select da 1ª imagem associada a um projeto pelo id do projeto
                                        function selectProjetoImage($connectDB, $idProjeto, &$imageName)
                                        {
                                            $resultImage = mysqli_query($connectDB, "SELECT nome FROM imagem WHERE fk_idprojeto=$idProjeto ORDER BY nome");
                                            if (mysqli_num_rows($resultImage) > 0) {
                                                $row = $resultImage->fetch_assoc();
                                                $imageName = ($row['nome']);
                                            }
                                        }

                                        $connectDB->close();    // Close connection

                                        ?>
                                        <!--###### End of script ######-->
                                        
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            </div>
            <!-- /.container-fluid -->

            <!--
            <footer style="width: auto;">
            <div style="margin-top: auto!important; margin-bottom: auto!important;">
                <div class="text-center" style="padding-top: 15px; padding-bottom: 30px; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; margin-top: auto!important; line-height: 1; font-size: 1.2rem;">
                <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro Magalhães</a> 2019</span>
                </div>
            </div>
            </footer>
                                    -->
            <footer style="position: absolute; width: calc(100% - 310px); bottom: 0;">
            <div style="margin: 20px 0; padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;text-align:center!important;line-height: 1; font-size: 1.2rem;">
                <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro Magalhães</a> 2019</span>
            </div>
            </footer>                            





            
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

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });

        //-- Year for the copyright label
        //var d = new Date()
        //document.write(d.getFullYear())
        $(document).ready(function(){ 
            //$(".form-control input-sm").attr("placeholder", "Type a name (Lastname, Firstname)").blur();
            //$(".form-control input-sm").attr("placeholder", "variable");
            var campo;
            $('body').find("input[type=search], input[type=text], search").each(function(ev)
            {
                if(!$(this).val()) { 
                    $(this).attr("placeholder", "Pesquisar...");
                    //$(this).val("asdasdasdasd");
                    //campo = $(this).val();
                }
            });
            //console.log(campo);
        });

    </script>

</body>

</html>
