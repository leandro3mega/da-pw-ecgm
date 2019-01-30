<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
    exit();
} else {
    $nome = $email = $id_projeto = "";
    $id_user = $_SESSION['id'];
    $username = $_SESSION['username'];
    $tipo = $_SESSION['tipo'];
    $cargo = $_SESSION['cargo'];
    // $id_projeto = $_POST['id_projeto'];
    
    if (isset($_POST['id_projeto'])) {
        $_SESSION['edit_projeto_id'] = $_POST['id_projeto'];
        $id_projeto = $_SESSION['edit_projeto_id'];
    } else {
        $id_projeto = $_SESSION['edit_projeto_id'];
    }
    // echo("ID do projeto: " . $id_projeto);
        
    //--FIXME: este select penso que não seja necessário nesta pagina, pois não usamos o nome ou email do utilizador em lado nenhum
    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    /*
    if ($stmt = $connectDB->prepare("SELECT nome, email FROM view_useralunosdocentes WHERE idutilizador=?")) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_id);

        // Set parameters
        $param_id = $id_user;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_nome, $r_email);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    $nome = $r_nome;
                    $email = $r_email;
                }
            } else {
                echo"Não foi encontrada conta ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }

    // Close statement
    $stmt->close();
    */
    $projeto_iduc = $projeto_titulo = $projeto_descricao = $projeto_autores = $projeto_data = $projeto_ano = $projeto_semestre = $projeto_tipo = "";
    $projeto_palavraschave = $projeto_video = $projeto_unidade_curricular = $projeto_ficheiro = "";
    $ferramentas = array();

    //-- Seleciona os dados do projeto
    selectProjeto($connectDB, $id_projeto, $projeto_iduc, $projeto_titulo, $projeto_descricao, $projeto_autores, $projeto_data, $projeto_ano, $projeto_semestre, $projeto_tipo);
    //-- Seleciona as palavras chave deste projeto
    selectPalavrasChave($connectDB, $id_projeto, $projeto_palavraschave);
    //-- Seleciona o nome da UC deste projeto
    selectUC($connectDB, $id_projeto, $projeto_iduc, $projeto_unidade_curricular);
    //-- Seleciona o video deste projeto
    selectVideo($connectDB, $id_projeto, $projeto_video);
    //-- Seleciona o ficheiro deste projeto
    selectFicheiro($connectDB, $id_projeto, $projeto_ficheiro);
}

//-- Seleciona toda a informação de um projeto
function selectProjeto($connectDB, $id_projeto, &$projeto_iduc, &$projeto_titulo, &$projeto_descricao, &$projeto_autores, &$projeto_data, &$projeto_ano, &$projeto_semestre, &$projeto_tipo)
{
    $query = "SELECT fk_iduc, titulo, descricao, autores, data, ano, semestre, tipo FROM projeto WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
            
        // Set parameters
        $param_id = $id_projeto;
            
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
                
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_iduc, $r_titulo, $r_descricao, $r_autores, $r_data, $r_ano, $r_semestre, $r_tipo);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    $projeto_iduc = $r_iduc;
                    $projeto_titulo = $r_titulo;
                    $projeto_descricao = $r_descricao;
                    $projeto_autores = $r_autores;
                    $projeto_data = $r_data;
                    $projeto_ano = $r_ano;
                    $projeto_semestre = $r_semestre;
                    $projeto_tipo = $r_tipo;
                }
            } else {
                echo"Não foi encontrado Projeto ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
    $stmt->close();
}

//-- seleciona as palavras chave de um projeto
function selectPalavrasChave($connectDB, $id_projeto, &$projeto_palavraschave)
{
    $query = "SELECT palavra FROM palavra_chave WHERE fk_idprojeto=?";

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
            
        // Set parameters
        $param_id = $id_projeto;
            
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
                
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_palavra);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    $projeto_palavraschave = $r_palavra;
                }
            } else {
                // echo"Não foram encontradas palavras-chave ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
    $stmt->close();
}

//-- seleciona a UC de um projeto
function selectUC($connectDB, $id_projeto, $projeto_iduc, &$projeto_unidade_curricular)
{
    $query = "SELECT nome FROM unidade_curricular WHERE idunidade_curricular=?";

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_iduc);
            
        // Set parameters
        $param_iduc = $projeto_iduc;
            
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
                
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_palavra);
                if ($stmt->fetch()) {
                    //-- Atribui variaveis
                    $projeto_unidade_curricular = $r_palavra;
                }
            } else {
                echo"Não foi encontrada UC ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
    $stmt->close();
}

//-- seleciona o video de um projeto
function selectVideo($connectDB, $id_projeto, &$projeto_video)
{
    $query = "SELECT nome FROM video WHERE fk_idprojeto=?";

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_idprojeto);
            
        // Set parameters
        $param_idprojeto = $id_projeto;
            
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
                    $projeto_video = $r_nome;
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

//-- seleciona o FICHEIRO de um projeto
function selectFicheiro($connectDB, $id_projeto, &$projeto_ficheiro)
{
    $query = "SELECT nome FROM ficheiro WHERE fk_idprojeto=?";

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_idprojeto);
            
        // Set parameters
        $param_idprojeto = $id_projeto;
            
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
                    $projeto_ficheiro = $r_nome;
                }
            } else {
                // echo"Não foi encontrado ficheiro ";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Editar Projeto</title>

    <!-- Browser image -->
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

<!-- <style>
    .resp-container {
        position: relative;
        overflow: hidden;
        padding-top: 56.25%;
    }

    .resp-iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style> -->

<style>
.img-thumbnail-v2 {
    max-width: 100%;
    border-radius: 4px;
    webkit-transition: all .2s ease-in-out;
    transition-property: all;
    transition-duration: 0.2s;
    transition-timing-function: ease-in-out;
    transition-delay: 0s;
}
</style>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "sidemenu.php"; ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Editar Projeto</h1>
                        <!-- Input hidden que guarda o id do projeto para ser usado -->
                        <input type='hidden' value="<?php echo($id_projeto) ?>" name='id_projeto'>
                    </div>
                    <!-- /.col-lg-12 -->

                    <!-- Coluna 1 -->
                    <div class="col-lg-6" style="border-right: 1px solid #eee;">
                        <!-- Titulo -->
                        <div class="form-group">
                            <label>Título</label>
                            <p class="form-control-static">
                                <?php echo($projeto_titulo); ?>
                            </p>
                            <!-- Trigger/Open The Modal -->
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalTitulo">
                                Alterar
                            </button>
                        </div>

                        <!-- <button id="myBtn" class="btn btn-default btn-backoffice-size" style="margin-top:10px">Alterar</button> -->
                        <!-- Descrição -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Descrição</label>
                            <p class="form-control-static">
                                <?php echo($projeto_descricao); ?>
                            </p>
                            <!-- Trigger/Open The Modal -->
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalDescricao">
                                Alterar
                            </button>
                        </div>

                        <!-- Autores -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Autor(es) do projeto</label>

                            <p class="form-control-static">
                                <?php echo($projeto_autores); ?>
                            </p>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalAutores">
                                Alterar
                            </button>
                        </div>

                        <!-- Tipo -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">

                            <div class="form-inline">
                                <label>Trabalho... </label>

                                <p class="form-control-static">
                                    <?php
                                if ($projeto_tipo == 1) {
                                    echo("Teórico");
                                } else {
                                    echo("Prático");
                                }
                                ?>
                                </p>
                            </div>

                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalTipo">
                                Alterar
                            </button>
                        </div>

                        <!-- Ano letivo -->
                        <div style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <div class="form-inline">
                                <label style="padding-top:10px">Desenvolvido durante o... </label>
                                <p class="form-control-static">
                                    <?php
                                    if ($projeto_ano == 1) {
                                        echo(" 1º ano");
                                    } elseif ($projeto_ano == 2) {
                                        echo(" 2º ano");
                                    } elseif ($projeto_ano == 3) {
                                        echo(" 3º ano");
                                    }
                                    ?>
                                </p>
                            </div>
                            <!-- <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalAnoLetivo">
                                Alterar
                            </button> -->
                        </div>

                        <!-- Semestre -->
                        <div>
                            <div class="form-inline">
                                <label>Do... </label>

                                <p class="form-control-static">
                                    <?php
                                    if ($projeto_semestre == 1) {
                                        echo("1º Semestre");
                                    } else {
                                        echo("2º Semestre");
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <!-- Unidade Curricular -->
                        <div>
                            <div class="form-inline">
                                <label>Na Unidade Curricular... </label>
                                <p class="form-control-static">
                                    <?php echo($projeto_unidade_curricular); ?>
                                </p>
                            </div>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalSemestreUC">
                                Alterar
                            </button>
                        </div>

                        <!-- Data -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <div class="form-inline">
                                <label style="padding-top:10px">Projeto finalizado a...</label>
                                <p class="form-control-static">
                                    <?php
                                    $meses = array(
                                    "01" => "Janeiro",
                                    "02" => "Fevereiro",
                                    "03" => "Março",
                                    "04" => "Abril",
                                    "05" => "Maio",
                                    "06" => "Junho",
                                    "07" => "Julho",
                                    "08" => 'Agosto',
                                    "09" => "Setembro",
                                    "10" => "Outubro",
                                    "11" => "Novembro",
                                    "12" => "Dezembro");
                                    
                                    $diaProjetostr = substr($projeto_data, -2);    //-- substring
                                    $mesProjetostr = substr($projeto_data, -4, 2);    //-- substring
                                    $anoProjetostr = substr($projeto_data, 0, 4);    //-- substring
                                    $projeto_data_str = $diaProjetostr . " de " . $meses[$mesProjetostr] . ", " . $anoProjetostr;
                                    
                                    echo($projeto_data_str);
                                    ?>
                                </p>
                            </div>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalData">
                                Alterar
                            </button>
                        </div>



                        <!-- Ferramentas -->
                        <!-- <div class="form-group" style="margin-top:30px">
                            <label>Ferramentas utilizadas</label>
                            <div>
                                <?php
                                    //-- Script de selecionar ferramentas
                                    // $resultCategoria = mysqli_query($connectDB, "SELECT idferramenta, nome, descricao, empresa FROM ferramenta ORDER BY nome");

                                    // if (mysqli_num_rows($resultCategoria) > 0) {
                                    //     while ($row = $resultCategoria->fetch_assoc()) {
                                    //         echo("
                                    //         <input type='checkbox' name='cb[]' value='" . $row['idferramenta'] . "'/> " . $row['nome'] . "<br/>
                                    //         ");
                                    //     }
                                    // }
                                    ?>
                            </div>
                        </div> -->

                        <!-- Palavras Chave -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Palavras-Chave</label>

                            <?php
                            
                            if (!empty($projeto_palavraschave)) {
                                echo("<p class='form-control-static'>" . $projeto_palavraschave . "</p>");
                            } else {
                                echo("<p class='help-block'>Não existem Palavras Chave!</p>");
                            }
                            
                            ?>

                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalPalavrasChave">
                                Alterar
                            </button>
                        </div>

                    </div>
                    <!-- End: Coluna 1 -->

                    <!-- Coluna 2 -->
                    <div class="col-lg-6" style="">

                        <!-- Imagens -->
                        <div class="form-group" style="">
                            <label style="padding-top:10px; padding-bottom:8px">Imagens do Projeto</label>
                            <div class="row" style="display: flex; flex-wrap: wrap; margin:0px;">

                                <?php
                                $num_imagens = 0;   //-- Numero de imagens deste projeto
                                selectImagens($connectDB, $id_projeto, $num_imagens);

                                //-- Seleciona as IMAGENS de um PROJETO
                                function selectImagens($connectDB, $id_projeto, &$num_imagens)
                                {
                                    $diretorio = "images/projetos/imagens/";

                                    if ($stmt = $connectDB->prepare("SELECT idimagem, nome FROM imagem WHERE fk_idprojeto=?")) {
                                        // Bind variables to the prepared statement as parameters
                                        $stmt->bind_param("i", $param_idprojeto);
                                            
                                        // Set parameters
                                        $param_idprojeto = $id_projeto;
                                            
                                        // Attempt to execute the prepared statement
                                        if ($stmt->execute()) {
                                            $result = $stmt->get_result();
                                            $num_imagens = $result->num_rows;
                                            if ($result->num_rows !== 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $image_id = $row['idimagem'];   //-- ID da IMAGEM
                                                    $image_nome = $row['nome'];     //-- NOME da IMAGEM

                                                    echo("
                                                    <div class='col-lg-4 col-md-6 col-xs-12' style='padding-left:2px; padding-right:2px; margin-bottom:20px;'>
                                                        <div style='background-color:rgb(245, 245, 245); border: 1px solid rgba(154, 234, 234, 0.322); border-radius: 4px;'>   
                                                            
                                                            <div style='min-height:150px; display: flex; justify-content: center;'>
                                                                <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                    <img id='".$image_nome."' class='img-fluid img-thumbnail' onclick='parseImage(this.id)' data-toggle='modal' data-target='#modalVerImagem' style='max-height:150px' src='" . $diretorio . $image_nome . "' alt=''>
                                                                </div>
                                                            </div>

                                                            <div class='form-inline'>
                                                                <div style ='margin-left:auto; margin-right:auto; margin-top:5px; display: flex;'>
                                                                    <button class='icon-meus-projetos' id='" . $image_nome . "' onclick='deleteImagem(this.id)' style='background: Transparent no-repeat; border: none; width:60%; margin-right:auto; margin-left: auto;'>
                                                                        <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); font-size: 120%; padding:10px; width: auto;'></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <input type='hidden' value=" . $num_imagens . " name='numero_imagens'>
                                                    </div>
                                                    ");
                                                }
                                            }
                                        }
                                    }
                                    //-- So pode adicionar se existirem 5 ou menos imagens associadas ao projeto
                                    if ($num_imagens <= 5) {
                                        echo("
                                        <div id='" . $id_projeto . "' class='col-lg-4 col-md-6 col-xs-12' style='padding-left:2px; padding-right:2px;'>
                                            <div style='background-color:rgb(245, 245, 245); border: 1px solid rgba(154, 234, 234, 0.322); border-radius: 4px;'>
                                                <button class='btn btn-default btn-backoffice-size' data-toggle='modal' data-target='#modalAddImagem' style='background: Transparent no-repeat; border: none; width:100%'>
                                                    <div style='display: flex; justify-content: center; width:auto; height:100%;'>
                                                        <div style='min-height:180px; display: flex; flex-direction: column; justify-content: center; width:auto; height:100%;'>
                                                            <a><i class='fa fa-plus fa-fw' style='color: rgb(13, 140, 243); font-size: 200%;'></i></a>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                        ");
                                    }
                                    $stmt->close();
                                }
                                ?>

                            </div>
                        </div>

                        <!-- Video -->
                        <div class="form-group"
                            style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Vídeo - Youtube</label>

                            <div style="margin-bottom: 15px">
                                <?php
                                if (empty($projeto_video)) {
                                    echo("<p class='help-block'>Sem vídeo!</p>");
                                } else {
                                    echo("
                                    <div class='embed-responsive embed-responsive-16by9' style='margin-bottom: 15px'>
                                        <iframe class='embed-responsive-item' src='". "https://www.youtube.com/embed/" . $projeto_video . "' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
                                    </div>
                                    ");
                                }
                                ?>
                            </div>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalVideo">
                                <!-- Nome do botão -->
                                <?php
                                if (!empty($projeto_video)) {
                                    echo("Alterar");
                                } else {
                                    echo("Inserir");
                                }
                                ?>
                            </button>
                            <?php
                                if (!empty($projeto_video)) {
                                    echo "<button class='btn btn-default btn-backoffice-size' onclick='removeVideo()'>Remover</button>";
                                }
                            ?>
                        </div>

                        <!-- Ficheiro -->
                        <div class="form-group" style="margin-top:30px">
                            <label>Documento PDF</label>
                            <p class="help-block">
                                <?php
                                if (empty($projeto_ficheiro)) {
                                    echo("Não existe documento PDF!");
                                } else {
                                    echo("<a href='images/projetos/ficheiros/" . $projeto_ficheiro . "' download='" . $projeto_titulo . "'>Download</a>");
                                }
                                ?>

                            </p>

                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalFicheiro">
                                <!-- Nome do botão -->
                                <?php
                                if (!empty($projeto_ficheiro)) {
                                    echo("Alterar");
                                } else {
                                    echo("Inserir");
                                }
                                ?>
                            </button>
                            <?php
                                if (!empty($projeto_ficheiro)) {
                                    echo "<button class='btn btn-default btn-backoffice-size' onclick='removeFicheiro()'>Remover</button>";
                                }
                            ?>

                        </div>

                    </div>
                    <!-- End: Coluna 2 -->

                </div>
                <!-- /.row -->


            </div>
            <!-- /#wrapper -->
            <footer class="sticky-footer">
                <div
                    style="margin: 20px 0; padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;text-align:center!important;line-height: 1; font-size: 1.2rem;">
                    <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro
                            Magalhães</a> 2019</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal -> TITULO -->
    <div class="modal fade" id="modalTitulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Título</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <!-- <h4 class="modal-title" id="myModalLabel">Modal title</h4> -->
                </div>
                <!-- <form id='form_titulo' action='delete_edit_projeto.php' enctype='multipart/form-data' method='POST'> -->
                <div class="modal-body">
                    <input type="text" class="form-control" name="titulo_projeto" minlength="8" maxlength="50" required
                        placeholder="Insira o título do projeto" value="<?php echo($projeto_titulo); ?>">
                    <!-- atributos hidden para enviar no submit do form -->
                    <input type='hidden' value="change_titulo" name='action'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeTitulo()">Alterar</button>
                </div>
                <!-- </form> -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> TITULO -->

    <!-- Modal -> DESCRICAO -->
    <div class="modal fade" id="modalDescricao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Descrição</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea id="idescricao" class="form-control" name="descricao_projeto"
                        pattern="[a-zA-Z0-9!@#$%^*_|]{6,1000}" rows="10" minlength="10" maxlength="600" required
                        placeholder="Insira a descrição do projeto"><?php echo($projeto_descricao); ?></textarea>
                    <p id="helpDescricao" class="help-block">Carateres: 0 de 600</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeDescricao()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> DESCRICAO -->

    <!-- Modal -> AUTORES -->
    <div class="modal fade" id="modalAutores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Autor(es) deste projeto</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="autores_projeto" minlength="8" maxlength="120"
                        placeholder="Insira os autores do projeto (separados por ponto e vígula)"
                        value="<?php echo($projeto_autores); ?>">
                    <p class="help-block tooltip-demo">Exemplo: Luís Mota; Maria Inês Pinto
                        <a><i class="fa fa-info-circle fa-fw" data-toggle="tooltip" data-placement="right"
                                title="Insira o nome de todos os autores, incluindo o seu."></i></a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeAutores()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> AUTORES -->

    <!-- Modal -> TIPO -->
    <div class="modal fade" id="modalTipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Trabalho...</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="display: block;">
                        <?php
                        if ($projeto_tipo == 1) {
                            echo "
                            <label class='radio-inline'>
                                <input type='radio' name='tipo_projeto' id='tipo1' value='1' checked>Teórico
                            </label>";
                            echo "
                            <label class='radio-inline'>
                                <input type='radio' name='tipo_projeto' id='tipo2' value='2'>Prático
                            </label>";
                        } elseif ($projeto_tipo == 2) {
                            echo "
                            <label class='radio-inline'>
                                <input type='radio' name='tipo_projeto' id='tipo1' value='1'>Teórico
                            </label>";
                            echo "
                            <label class='radio-inline'>
                                <input type='radio' name='tipo_projeto' id='tipo2' value='2' checked>Prático
                            </label>";
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeTipo()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> TIPO -->

    <!-- Modal -> ANO & SEMESTRE & UC -->
    <div class="modal fade" id="modalSemestreUC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Trabalho desenvolvido no... </label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">

                    <!-- Ano Curricular -->
                    <div class="form-group">
                        <label>Ano Curricular</label>
                        <div style="display: block;">
                            <label class="radio-inline">
                                <input type="radio" class="ano_curricular" name="ano_curricular" onChange="getUCS()"
                                    id="ano_curricular1" value="1" checked>1º Ano
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="ano_curricular" name="ano_curricular" onChange="getUCS()"
                                    id="ano_curricular2" value="2">2º Ano
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="ano_curricular" name="ano_curricular" onChange="getUCS()"
                                    id="ano_curricular3" value="3">3º Ano
                            </label>
                        </div>
                    </div>

                    <!-- Semestre -->
                    <div class="form-group">
                        <label>Semestre</label>
                        <div style="display: block;">
                            <label class="radio-inline">
                                <input type="radio" class="semestre" name="semestre_projeto" onChange="getUCS()"
                                    id="semestre1" value="1" checked>1º Semestre
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="semestre" name="semestre_projeto" onChange="getUCS()"
                                    id="semestre2" value="2">2º Semestre
                            </label>
                        </div>
                    </div>

                    <!-- Unidade Curricular -->
                    <div class="form-group">
                        <label>Na Unidade Curricular de... </label>
                        <select id="iSelectUC" class="form-control" name="selectUC_projeto" required>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeAnoSemestreUC()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> ANO & SEMESTRE & UC -->

    <!-- Modal -> DATA -->
    <div class="modal fade" id="modalData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Projeto finalizado a...</label>
                </div>
                <div class="modal-body">
                    <?php
                    $dataValue = $anoProjetostr . '-' . $mesProjetostr . '-' . $diaProjetostr;
                    ?>
                    <input type="date" class="form-control" name="data_projeto" required
                        value="<?php echo($dataValue);?>">
                    <p class="help-block">Exemplo: 12/03/2019</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeData()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> DATA -->

    <!-- Modal -> PALAVRAS-CHAVE -->
    <div class="modal fade" id="modalPalavrasChave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Palavras-Chave</label>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="palavraschave_projeto"
                        value="<?php echo($projeto_palavraschave); ?>" minlength="5" maxlength="100"
                        placeholder="Insira as palavras chave do projeto (separadas por ponto e vígula)">
                    <p class="help-block">Exemplo: Desenho; Mockup</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changePalavrasChave()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> PALAVRAS-CHAVE -->

    <!-- Modal -> VIDEO -->
    <div class="modal fade" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Vídeo - Youtube</label>
                </div>
                <div class="modal-body">
                    <?php
                    $input_video_value = "";
                    if (empty($projeto_video)) {
                        $input_video_value = "";
                    } else {
                        $input_video_value = "https://www.youtube.com/watch?v=" . $projeto_video;
                    }

                    ?>
                    <input type="url" class="form-control" name="video_projeto"
                        value="<?php echo($input_video_value); ?>" minlength="10" maxlength="50"
                        placeholder="https://www.youtube.com/watch?v=BrK9atbrWFY" />

                    <p class="help-block" id="help_video">Exemplo: https://www.youtube.com/watch?v=BrK9atbrWFY</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeVideo()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> VIDEO -->

    <!-- Modal -> FICHEIRO -->
    <div class="modal fade" id="modalFicheiro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Documento PDF</label>
                </div>
                <form role="form" id="form_ficheiro" action="delete_edit_projeto.php" method="post"
                    enctype="multipart/form-data">
                    <div class=" modal-body">
                        <input type="file" id="iFicheiro" name="ficheiro_projeto" onChange="verificaLimitesFicheiro()"
                            accept="application/pdf">

                        <input type='hidden' value="change_ficheiro" name='action'>
                        <input type='hidden' value="<?php echo($id_projeto) ?>" name='form_id_projeto'>

                        <p class="help-block" id="iHintFicheito">Insira um ficheiro PDF com tamanho máximo
                            de 2MB</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="changeFicheiro()">Alterar</button> -->
                        <input type="submit" class="btn btn-primary" value="Submeter">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> FICHEIRO -->

    <!-- Modal -> ADICIONA IMAGEM -->
    <div class="modal fade" id="modalAddImagem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Adicionar Imagem</label>
                </div>
                <form role="form" action="delete_edit_projeto.php" method="post"
                    enctype='multipart/form-data'">
                    <div class=" modal-body">
                    <input type="file" id="iAddImagem" name="new_imagem_projeto" onChange="verificaLimitesImagem()"
                        accept="image/jpeg, image/png">

                    <input type='hidden' value="add_imagem_projeto" name='action'>
                    <input type='hidden' value="<?php echo($id_projeto) ?>" name='form_add_image_id_projeto'>

                    <p class="help-block" id="iHintImagem">Insira uma Imagem PNG/JPEG com tamanho máximo de 1MB</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <!-- <button type="button" class="btn btn-primary" onclick="changeFicheiro()">Alterar</button> -->
                <input type="submit" class="btn btn-primary" value="Submeter">
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> ADICIONA IMAGEM -->

    <!-- Modal -> VER IMAGEM -->
    <div class="modal fade" id="modalVerImagem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class=" modal-body">

                    <div style='min-height:150px; display: flex; justify-content: center;'>
                        <div style='display: flex; flex-direction: column; justify-content: center;'>
                            <img class='img-thumbnail-v2' name="big_image"
                                style='width:auto; height:auto; max-height: 500px;' src='' alt=''>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> VER IMAGEM -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>
<script>
$(document).ready(function() {
    getUCS();
});

//-- Semestre selecionado
function getUCS() {
    var semestre = $('.semestre:checked').val();
    var ano = $('.ano_curricular:checked').val();
    // var ano_curricular = $('.iAnoLetivo').val();

    console.log("Semestre: " + semestre);
    console.log("Ano: " + ano);
    // console.log("Ano: " + ano_curricular);
    $('#iSelectUC').empty();

    $.ajax({
        type: "GET",
        url: 'fetch_ucs_users.php',
        data: {
            'action': 'get_ucs',
            'semestre': semestre,
            'ano_curricular': ano
        },
        dataType: 'json',
        success: function(response) {
            console.log("Funciona");

            $.each(response, function(index, element) {
                console.log(element); // print json code
                $("#iSelectUC").append("<option value='" + element.idunidade_curricular + "'>" +
                    element.nome + "</option>");
            });
            // alert(response);
            console.log(response);
        }
    });
}

//-- Mostra o numero de letras na descrição (textarea)
$("#idescricao").keyup(function() {
    $("#helpDescricao").text($(this).val().length + "/600");
});

//-- Verifica Limites da IMAGEM (se é PNG/JPG, e se o tamanho é abaixo do limite)
function verificaLimitesImagem() {
    var input_imagem = document.getElementById("iAddImagem");
    var hint_imagem = document.getElementById("iHintImagem");

    var limiteSize = 1020; // 1 Megabyte
    var file = input_imagem.files[0];
    // console.log("ficheiro: " + file);
    // console.log("Tipo: " + file.type);

    if (file.type === "image/png" || file.type === "image/jpeg") {
        // console.log("Ficheiro é png ou jpeg!");
    } else {
        // console.log("Ficheiro não é png ou jpeg!");
        alert("A imagem não é de tipo suportado.");
        input_imagem.value = "";

        return;
    }

    //##### Start of reader
    var reader = new FileReader(); // CREATE AN NEW INSTANCE.

    reader.onload = function(e) {
        var img = new Image();
        img.src = e.target.result;

        img.onload = function() {
            var valido = true;
            var w = this.width;
            var h = this.height;
            var size = Math.round((file.size / 1024));

            if (file.type == "image/png" || file.type == "image/jpeg") {
                console.log("A imagem é png ou jpeg");
                valido = true;
                //-- Check size and dimensions of image
                if (w > 1980 || h > 1080 || size > limiteSize) {
                    alert(
                        "A imagem tem tamanho superior a 1MB ou dimensões superiores a 1960*1080."
                    );
                    // console.log("A imagem tem tamanho superior a 1mb ou dimensoes superiores a 1960*1080");
                    valido = false;

                } else {
                    console.log("A imagem não tem tamanho superior a 1mb");
                    valido = true;
                }
            } else {
                // console.log("A imagem não é png ou jpeg");
                alert("Imagens não é de tipo suportado!");

                valido = false;
            }

            if (!valido) {
                // console.log("Imagem não valida");
                input_imagem.value = "";
                hint_imagem.innerHTML = "Insira uma PNG/JPEG com tamanho máximo de 1MB";
                hint_imagem.style = "color:rgb(216, 79, 79);";

            } else {
                // console.log("Imagem é valida");
                hint_imagem.innerHTML = "Imagem válida";
                hint_imagem.style = "color:rgb(79, 216, 132);";
            }
        }
    };
    reader.readAsDataURL(file, input_imagem);
    //##### End of reader
}

//-- Verifica se o ficheiro obdece aos limites estabelecidos
function verificaLimitesFicheiro() {

    var ficheiro = document.getElementById("iFicheiro");
    var hint = document.getElementById("iHintFicheito");

    var file_info = ficheiro.files[0];
    //console.log(file_info);
    var nome = file_info.name;
    //console.log("File Name: " + nome);
    var tipo = file_info.type;
    //console.log("File type: " + tipo);
    var tamanho = file_info.size;
    //console.log("File size: " + tamanho / 1024);

    if (tipo != "application/pdf" || (tamanho / 1024) > 2000) {
        ficheiro.value = "";

        //-- Mostra hint de Invalidez
        hint.style = "color:rgb(216, 79, 79);";
        hint.innerHTML = "Ficheiro inválido. Insira um ficheiro PDF com até 2MB.";

    } else {
        //-- Mostra hint de Sucesso
        hint.style = "color:rgb(79, 216, 132)";
        hint.innerHTML = "Ficheiro válido.";

    }
}

//## FIXME: NOT USED
// GET THE IMAGE WIDTH AND HEIGHT USING fileReader() API.
function readImageFile(file) {
    var reader = new FileReader(); // CREATE AN NEW INSTANCE.

    reader.onload = function(e) {
        var img = new Image();
        img.src = e.target.result;

        img.onload = function() {
            var w = this.width;
            var h = this.height;

            // console.log("File Name: " + file.name);
            // console.log("Width: " + w);
            // console.log("Height: " + h);
            // console.log("Size: " + Math.round((file.size / 1024)));
            // console.log("File Type: " + file.type);
        }
    };
    reader.readAsDataURL(file);
}


// hint popup
$('.tooltip-demo').tooltip({
    selector: "[data-toggle=tooltip]",
    container: "body"
})

//-- Pedido AJAX para alterar titulo do projeto
function changeTitulo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var tituloprojeto = $('input[name="titulo_projeto"]').val();

    if (tituloprojeto.length > 10) {
        $.ajax({
            type: "POST",
            url: 'delete_edit_projeto.php',
            data: {
                'action': 'change_titulo',
                'id_projeto': idprojeto,
                'titulo_projeto': tituloprojeto
            },
            success: function(response) {
                // alert(response);
                location.reload();

            }
        });
    } else {
        alert("Insira pelo menos 10 caracteres!");
    }
}

//-- Pedido AJAX para alterar descricao do projeto
function changeDescricao() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var descricaoprojeto = $('textarea[name="descricao_projeto"]').val();

    // console.log("Descricao " + descricaoprojeto);
    if (descricaoprojeto.length > 10) {
        $.ajax({
            type: "POST",
            url: 'delete_edit_projeto.php',
            data: {
                'action': 'change_descricao',
                'id_projeto': idprojeto,
                'descricao_projeto': descricaoprojeto
            },
            success: function(response) {
                // alert(response);
                location.reload();

            }
        });
    } else {
        alert("Insira pelo menos 10 caracteres!");

    }
}

//-- Pedido AJAX para alterar os autores do projeto
//-- TODO: Meter user a ser sempre dono do projeto
function changeAutores() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var autoresprojeto = $('input[name="autores_projeto"]').val();

    // console.log("autores: " + autoresprojeto);
    if (autoresprojeto.length > 10){
        $.ajax({
            type: "POST",
            url: 'delete_edit_projeto.php',
            data: {
                'action': 'change_autores',
                'id_projeto': idprojeto,
                'autores_projeto': autoresprojeto
            },
            success: function(response) {
                alert(response);
                location.reload();

            }
        });
    } else {
        alert("Insira pelo menos 10 caracteres!");

    }
}

//-- Pedido AJAX para alterar as palavras-chave do projeto
function changePalavrasChave() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var palavras_chave_projeto = $('input[name="palavraschave_projeto"]').val();

    // console.log("Palavras-chave: " + palavras_chave_projeto);
    if (autoresprojeto.length > 5){
        $.ajax({
            type: "POST",
            url: 'delete_edit_projeto.php',
            data: {
                'action': 'change_palavraschave',
                'id_projeto': idprojeto,
                'palavraschave_projeto': palavras_chave_projeto
            },
            success: function(response) {
                // alert(response);
                location.reload();

            }
        });
    } else {
        alert("Insira pelo menos 5 caracteres!");
    }
}

//-- Pedido AJAX para alterar o video do projeto
function changeVideo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var video_projeto = $('input[name="video_projeto"]').val();

    // console.log("video: " + video_projeto);

    if (video_projeto != undefined || video_projeto != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = video_projeto.match(regExp);
        if (match && match[2].length == 11) {
            // console.log("URL valido");
            // if need to change the url to embed url then use below line
            // $('#ytplayerSide').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0');
        } else {
            // console.log("URL invalido");
            $('p[id="help_video"]').text("URL inválido. Exemplo: " +
                "https://www.youtube.com/watch?v=BrK9atbrWFY");
            $('p[id="help_video"]').css({
                "color": "rgb(216, 79, 79)"
            })
            return;
        }
    }

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_video',
            'id_projeto': idprojeto,
            'video_projeto': video_projeto
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o Tipo do projeto
function changeTipo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var tipo_projeto = $('input[name="tipo_projeto"]:checked').val();

    // console.log("Tipo: " + tipo_projeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_tipo',
            'id_projeto': idprojeto,
            'tipo_projeto': tipo_projeto
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o Ano Letivo do projeto
function changeAnoLetivo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var ano_letivo_projeto = $('select[name="selectAnoLetivo_projeto"]').val();

    // console.log("Ano_Letivo: " + ano_letivo_projeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_anoletivo',
            'id_projeto': idprojeto,
            'anoletivo_projeto': ano_letivo_projeto
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o semestre e unidade curricular do projeto
function changeAnoSemestreUC() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var ano_projeto = $('input[name="ano_curricular"]:checked').val();
    var semestre_projeto = $('input[name="semestre_projeto"]:checked').val();
    var uc_projeto = $('select[name="selectUC_projeto"]').val();

    // console.log("Semestre: " + semestre_projeto);
    // console.log("UCS: " + uc_projeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_semestre_uc',
            'id_projeto': idprojeto,
            'ano_projeto': ano_projeto,
            'semestre_projeto': semestre_projeto,
            'uc_projeto': uc_projeto
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o semestre e unidade curricular do projeto
function changeData() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var data_projeto = $('input[name="data_projeto"]').val();

    console.log("Data: " + data_projeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_data',
            'id_projeto': idprojeto,
            'data_projeto': data_projeto
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o semestre e unidade curricular do projeto
function removeFicheiro() {
    var idprojeto = $('input[name="id_projeto"]').val();

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_remove_ficheiro',
            'id_projeto': idprojeto,
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o semestre e unidade curricular do projeto
function removeVideo() {
    var idprojeto = $('input[name="id_projeto"]').val();

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_remove_video',
            'id_projeto': idprojeto,
        },
        success: function(response) {
            // alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para REMOVER IMAGEM de um projeto
function deleteImagem(nome) {
    var idprojeto = $('input[name="id_projeto"]').val();
    var num_imagens = $('input[name="numero_imagens"]').val();

    // console.log("Imagem: " + nome);
    // console.log("Numero Imagens: " + num_imagens);

    if (num_imagens == 1) {
        alert("O projeto tem de possuir pelo menos uma imagem!");
    } else if (confirm('Tem a certeza que pretende remover a imagem?')) {
        // console.log("A imagem será removida!");

        $.ajax({
            type: "POST",
            url: 'delete_edit_projeto.php',
            data: {
                'action': 'remove_image_projeto',
                'id_projeto': idprojeto,
                'nome_imagem_projeto': nome
            },
            success: function(response) {
                // alert(response);
                location.reload();
            }
        });
    }
}

function parseImage(nome) {
    $('img[name="big_image"]').attr("src", "images/projetos/imagens/" + nome);
}

//##FIXME: LIXO
$(document).ready(function() {

    // $('#btnInsert').click(function() {
    //     addCheckbox2($('#txtNameCat').val());
    // });

});


//##FIXME: LIXO
function addCheckbox(name) {
    var container = $('#cblist');
    var inputs = container.find('input');
    var id = inputs.length + 1;

    $('<input />', {
        type: 'checkbox',
        id: 'cb' + id,
        value: name
    }).appendTo(container);
    $('<label />', {
        'for': 'cb' + id,
        text: name
    }).appendTo(container);
}
</script>

</html>