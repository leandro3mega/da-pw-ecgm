<?php
require_once("connectdb.php");

session_start();
if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
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
    if ($tipo == 0) {
        echo("Projetos");
    } elseif ($tipo == 1) {
        echo("Meus Projetos");
    } else {
        echo("Projetos");
    }
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

    <!-- Meus estilos -->
    <!-- <link href="css/stylesheet.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="css/stylesheet.css" type="text/css">

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
        <?php include "sidemenu.php"; ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" style="min-height:500px">
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php
                            if ($tipo == 0) {
                                echo("Projetos");
                            } elseif ($tipo == 1) {
                                echo("Meus Projetos");
                            } else {
                                echo("Projetos");
                            }
                            ?>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">

                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover"
                                    id="dataTables-example">

                                    <!--###### Start of script ######-->
                                    <?php
                                        //require_once("connectdb.php");
                                        if ($tipo == 0) {
                                            selectProjetoAdmin($connectDB);
                                        } elseif ($tipo == 1) {
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
                                                        <th style='max-width:100px'>Ações</th>
                                                   </tr>
                                                </thead>" .
                                                "<tbody>";

                                            $sql = mysqli_query($connectDB, "SELECT idprojeto, titulo, descricao, autores, data, ano, semestre, tipo, fk_iduc 
                                                                            FROM projeto");

                                            while ($row = $sql->fetch_assoc()) {
                                                $idUC = $row['fk_iduc'];

                                                $nomeUC;
                                                selectUC($connectDB, $idUC, $nomeUC);   //-- Obtem o nome da UC

                                                //-- Identifier do projeto
                                                $idProjeto = $row['idprojeto'];
                                                $autoresProjeto = $row['autores'];
                                                // $autoresProjeto = "";
                                                // selectAlunoProjeto($connectDB, $idProjeto, $autoresProjeto);   //-- Obtem os autores do projeto

                                                //-- Descrição do projeto
                                                $descricaoProjeto = $row['descricao'];
                                                $descricaoProjetostr = substr($descricaoProjeto, 0, 30) . "...";    //-- substring que limita o tamanho da descrição

                                                //-- Data que o projeto foi finalizado
                                                $dataProjeto = $row['data'];
                                                $diaProjetostr = substr($dataProjeto, -2);    //-- substring
                                                $mesProjetostr = substr($dataProjeto, -4, 2);    //-- substring
                                                $anoProjetostr = substr($dataProjeto, 0, 4);    //-- substring

                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/imagens/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //-- Print a new table line
                                                echo
                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'>
                                                        <div style='height:80px; display: flex; justify-content: center;'>
                                                            <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                <img id='".$idProjeto."' class='img-fluid img-thumbnail' onclick='viewProjeto(this.id)' src='" . $imageURL . $imageName . "' alt='' style='width:auto; max-height:80px;' >
                                                            </div>
                                                        </div>
                                                    </td>" .
                                                    "<td id='".$idProjeto."' style='vertical-align: middle; overflow: hidden; word-wrap: break-word;' onclick='viewProjeto(this.id)'>
                                                        <form id='formViewProjeto_". $idProjeto ."' action='../frontoffice/indexport.php' method='GET'>
                                                            <input type = 'hidden' value = '" . $idProjeto ."'name = 'idprojeto' >
                                                        </form>
                                                        " . $row['titulo'] . "
                                                    </td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle; overflow: hidden; word-wrap: break-word;'>" . $autoresProjeto . "</td>" .
                                                    "<td style='vertical-align: middle; overflow: hidden; word-wrap: break-word;'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    // "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                    // "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
                                                    // "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                    // "</ul>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                        "<div class='form-group form-inline'>" .
                                                            "<div style ='display:flex;margin-left:auto; margin-right:auto;'>" .
                                                                "<form id='formEditProjeto[". $idProjeto ."]' action='editar-projeto.php' enctype='multipart/form-data' method='POST'>" .
                                                                    "<input type = 'hidden' value = '" . $idProjeto ."'name = 'id_projeto' >" .
                                                                    "<li style='margin-left:auto;'>
                                                                        <button href='#' id='".$idProjeto."' onclick='editaProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                            <i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96); padding:10px; width: auto;'></i>
                                                                        </button>
                                                                    </li>" .
                                                                "</form>" .
                                                                // "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                                "<li style='margin-right:auto;'>
                                                                    <button class='icon-meus-projetos' id='" . $idProjeto . "' onclick='deleteProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                        <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); padding:10px; width: auto;'></i>
                                                                    </button>
                                                                </li>" .
                                                            "</div>" .
                                                        "</div>" .
                                                    // "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
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
                                                        <th style='max-width:100px'>Ações</th>
                                                    </tr>
                                                </thead>" .
                                                "<tbody>";
                                            $sql = mysqli_query($connectDB, "SELECT p.idprojeto, p.titulo, p.descricao, p.data, p.ano, p.semestre, p.tipo, p.fk_iduc 
                                                                            FROM projeto p, aluno_projeto ap 
                                                                            WHERE fk_aluno=$id AND p.idprojeto=ap.fk_projeto");

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
                                                $diaProjetostr = substr($dataProjeto, -2);    //-- substring
                                                $mesProjetostr = substr($dataProjeto, -4, 2);    //-- substring
                                                $anoProjetostr = substr($dataProjeto, 0, 4);    //-- substring

                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/imagens/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //-- Print a new table line
                                                echo
                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'>
                                                        <div style='height:80px; display: flex; justify-content: center;'>
                                                            <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                <img id='".$idProjeto."' class='img-fluid img-thumbnail' onclick='viewProjeto(this.id)' src='" . $imageURL . $imageName . "' alt='' style='width:auto; max-height:80px;' >
                                                            </div>
                                                        </div>
                                                    </td>" .
                                                    "<td id='".$idProjeto."' style='vertical-align: middle; overflow: hidden; word-wrap: break-word;' onclick='viewProjeto(this.id)'>
                                                        <form id='formViewProjeto_". $idProjeto ."' action='../frontoffice/indexport.php' method='GET'>
                                                            <input type='hidden' value='" . $idProjeto ."' name='idprojeto' >
                                                        </form>
                                                        " . $row['titulo'] . "
                                                    </td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle; overflow: hidden; word-wrap: break-word;'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>" .
                                                        "<div class='form-group form-inline'>" .
                                                            "<div style ='display:flex;margin-left:auto; margin-right:auto;'>" .
                                                                "<form id='formEditProjeto[". $idProjeto ."]' action='../frontoffice/indexport.php' enctype='multipart/form-data' method='POST'>" .
                                                                    "<input type = 'hidden' value = '" . $idProjeto ."'name = 'id_projeto' >" .
                                                                    "<li style='margin-left:auto;'>
                                                                        <button href='#' id='".$idProjeto."' onclick='editaProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                            <i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96); padding:10px; width: auto;'></i>
                                                                        </button>
                                                                    </li>" .
                                                                "</form>" .
                                                                // "<li><a href='novo-projeto.php'><i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96)'></i></a>" .
                                                                "<li style='margin-right:auto;'>
                                                                    <button class='icon-meus-projetos' id='" . $idProjeto . "' onclick='deleteProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                        <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); padding:10px; width: auto;'></i>
                                                                    </button>
                                                                </li>" .
                                                            "</div>" .
                                                        "</div>" .
                                                    // "<li><a href='novo-projeto.php'><i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45)'></i></a>" .
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
                                                        <th style='max-width:100px'>Ações</th>
                                                    </tr>
                                                </thead>" .
                                                "<tbody>";

                                            $sql = mysqli_query($connectDB, "SELECT p.idprojeto, p.titulo, p.descricao, p.data, p.ano, p.semestre, p.tipo, p.fk_iduc, uc.nome as nome_uc 
                                                                            FROM projeto p, unidade_curricular uc, docente_uc duc
                                                                            WHERE p.fk_iduc=uc.idunidade_curricular AND p.fk_iduc=duc.fk_iduc AND duc.fk_iddocente=$id
                                                                            GROUP BY p.idprojeto");

                                            while ($row = $sql->fetch_assoc()) {
                                                $idUC = $row['fk_iduc'];
                                                $nomeUC = $row['nome_uc'];
                                                $idProjeto = $row['idprojeto'];
                                                $titulo_projeto = $row['titulo'];

                                                // $nomeUC;
                                                //selectUC($connectDB, $idUC, $nomeUC);   //-- Obtem o nome da UC

                                                //$autoresProjeto = "";
                                                //selectAlunoProjeto($connectDB, $idProjeto, $autoresProjeto);   //-- Obtem os autores do projeto

                                                $descricaoProjeto = $row['descricao'];
                                                $descricaoProjetostr = substr($descricaoProjeto, 0, 30) . "...";    //-- substring que limita o tamanho da descrição

                                                $dataProjeto = $row['data'];
                                                $diaProjetostr = substr($dataProjeto, -2);    //-- substring
                                                $mesProjetostr = substr($dataProjeto, -4, 2);    //-- substring
                                                $anoProjetostr = substr($dataProjeto, 0, 4);    //-- substring

                                                $dataProjetostr = $diaProjetostr . "/" . $mesProjetostr . "/" . $anoProjetostr;

                                                //-- Tipo de projeto
                                                //####
                                                $tProjeto = $row['tipo'];
                                                if ($tProjeto == 1) {
                                                    $tipoProjeto = "Teórico";
                                                } else {
                                                    $tipoProjeto = "Prático";
                                                }

                                                //-- 1ª imagem do projeto
                                                $imageURL = "images/projetos/imagens/";
                                                $imageName = "";
                                                selectProjetoImage($connectDB, $idProjeto, $imageName);

                                                //echo (alert($row['titulo'] . " | " . $dataProjetostr . " | " . $tipo . " | " . $nomeUC));
                                                //-- Print a new table line
                                                echo
                                                    "<tr class='odd gradeX'>" .
                                                    "<td style='vertical-align: middle'>
                                                        <div style='height:80px; display: flex; justify-content: center;'>
                                                            <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                <img id='".$idProjeto."' class='img-fluid img-thumbnail' onclick='viewProjeto(this.id)' src='" . $imageURL . $imageName . "' alt='' style='width:auto; max-height:80px;' >
                                                            </div>
                                                        </div>
                                                    </td>" .
                                                    "<td id='".$idProjeto."' onclick='viewProjeto(this.id)' style='vertical-align: middle; overflow: hidden; word-wrap: break-word;'>
                                                         <form id='formViewProjeto_". $idProjeto ."' action='../frontoffice/indexport.php' method='GET'>
                                                            <input type = 'hidden' value = '" . $idProjeto ."'name = 'idprojeto' >
                                                        </form>
                                                        " . $titulo_projeto . "
                                                    </td>" .
                                                    "<td style='vertical-align: middle'>" . $dataProjetostr . "</td>" .
                                                    "<td style='vertical-align: middle'>" . $tipoProjeto . "</td>" .
                                                    "<td style='vertical-align: middle; overflow: hidden; word-wrap: break-word;'>" . $nomeUC . "</td>" .
                                                    "<td style='vertical-align: middle'>" .
                                                    "<ul class='nav navbar-top-links' style='float: inherit; vertical-align: middle'>
                                                        <div class='form-group form-inline'>
                                                            <div style ='display:flex;margin-left:auto; margin-right:auto;'>
                                                                <form id='formEditProjeto[". $idProjeto ."]' action='editar-projeto.php' enctype='multipart/form-data' method='POST'>
                                                                    <input type = 'hidden' value = '" . $idProjeto ."'name = 'id_projeto' >
                                                                    <li style='margin-left:auto;'>
                                                                        <button href='#' id='".$idProjeto."' onclick='editaProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                            <i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96); padding:10px; width: auto;'></i>
                                                                        </button>
                                                                    </li>
                                                                </form>
                                                                <li style='margin-right:auto;'>
                                                                    <button class='icon-meus-projetos' id='" . $idProjeto . "' onclick='deleteProjeto(this.id)' style='background: Transparent no-repeat; border: none;'>
                                                                        <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); padding:10px; width: auto;'></i>
                                                                    </button>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </ul>" .
                                                    "</td>" .
                                                    "</tr>";
                                            }
                                            echo "</tbody>";
                                        }

                                        //-- Select do nome da UC associada a um projeto pelo id da UC
                                        function selectUC($connectDB, $idUC, &$nomeUC)
                                        {
                                            // query à base de dados
                                            $sql = "SELECT nome FROM unidade_curricular WHERE idunidade_curricular=?";

                                            // inicializar prepared statement
                                            $stmt = $connectDB->prepare($sql);

                                            // md5 para desincriptar a password
                                            //$password = md5($pass_old);
                                            $stmt->bind_param("i", $param_iduc);

                                            $param_iduc = (int)$idUC;

                                            // executar
                                            $stmt->execute();

                                            // associar os parametros de output
                                            $stmt->bind_result($r_nome_uc);

                                            // transfere o resultado da última query : obrigatorio para ter num_rows
                                            $stmt->store_result();

                                            // iterar / obter resultados
                                            $stmt->fetch();

                                            if ($stmt->num_rows == 1) {
                                                $nomeUC = $r_nome_uc;
                                            // echo("</br>Encontrou Docente ligado à unidade curricular.");
                                            } else {
                                                // echo("</br>Não encontrou Docente ligado à unidade curricular.");
                                            }
                                            
                                            $stmt->close();
                                        }
                                        
                                        //-- Select do nome dos autores do projeto (com formatação dos nomes)
                                        // function selectAlunoProjeto($connectDB, $idProjeto, &$autoresProjeto)
                                        // {
                                        //     $count = 0;
                                        //     $resultQuery = mysqli_query($connectDB, "SELECT a.nome FROM aluno a, aluno_projeto ap WHERE ap.fk_projeto=$idProjeto AND a.fk_idutilizador=ap.fk_aluno ORDER BY a.nome");
                                        //     if (mysqli_num_rows($resultQuery) > 0) {    // se existerem resultados
                                        //         while ($row = $resultQuery->fetch_assoc()) { // enquanto houverem resultados
                                        //             if ($count == 0) {
                                        //                 $autoresProjeto .= $row['nome'];
                                        //             } else {
                                        //                 $autoresProjeto .= ",\n" . $row['nome'];
                                        //             }

                                        //             $count++;
                                        //         }
                                        //         $autoresProjeto .= ".";
                                        //     }
                                        // }

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

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });

    //-- Year for the copyright label
    //var d = new Date()
    //document.write(d.getFullYear())
    $(document).ready(function() {
        //$(".form-control input-sm").attr("placeholder", "Type a name (Lastname, Firstname)").blur();
        //$(".form-control input-sm").attr("placeholder", "variable");
        var campo;
        $('body').find("input[type=search], input[type=text], search").each(function(ev) {
            if (!$(this).val()) {
                $(this).attr("placeholder", "Pesquisar...");
                //$(this).val("asdasdasdasd");
                //campo = $(this).val();
            }
        });
        //console.log(campo);
    });

    function deleteProjeto(user_id) {

        if (confirm('Tem a certeza que pretende remover o projeto?')) {
            console.log("O projeto será removido!");

            $.ajax({
                type: "POST",
                url: 'delete_edit_projeto.php',
                data: {
                    'action': 'delete_projeto',
                    'id_projeto': user_id
                },
                success: function(response) {
                    location.reload();
                }
            });

        } else {
            return;
        }

    }

    function editaProjeto(projeto_id) {
        // formEditProjeto[". $idProjeto ."]'
        var form = document.getElementById("formEditProjeto[" + projeto_id + "]");

        form.submit();

        // document.getElementById("your-id").addEventListener("click", function () {
        // form.submit();
        // });
    }

    function viewProjeto(id_projeto) {
        console.log("ID: " + id_projeto);


        var form = document.getElementById("formViewProjeto_" + id_projeto);
        // document.getElementById("your-id").addEventListener("click", function() {
        form.submit();
        // });

    }
    </script>

</body>

</html>