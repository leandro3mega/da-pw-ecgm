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
    $id_projeto = $_POST['id_projeto'];
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
    $projeto_palavraschave = $projeto_video = $projeto_unidade_curricular = "";
    $ferramentas = array();

    //-- Seleciona os dados do projeto
    selectProjeto($connectDB, $id_projeto, $projeto_iduc, $projeto_titulo, $projeto_descricao, $projeto_autores, $projeto_data, $projeto_ano, $projeto_semestre, $projeto_tipo);
    //-- Seleciona as palavras chave deste projeto
    selectPalavrasChave($connectDB, $id_projeto, $projeto_palavraschave);
    //-- TODO: Seleciona o nome da UC deste projeto
    selectUC($connectDB, $id_projeto, $projeto_iduc, $projeto_unidade_curricular);
    
    //-- TODO:Seleciona o video deste projeto
    selectVideo($connectDB, $id_projeto, $projeto_video);
    //-- TODO:Seleciona as palavras chave deste projeto
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
                
            // Check if username exists, if yes then verify password
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
                echo"Não foi encontrada conta ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
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
                echo"Não foi encontrada conta ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
    }
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
                echo"Não foi encontrado video ";
            }
        } else {
            echo "Algo correu mal. Por favor tente de novo.";
        }
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

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button> -->
                <a class="navbar-brand" href="#">Area de Utilizador</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

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
                    <ul class="nav" id="side-menu">

                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>

                        <li>
                            <a href="meus-projetos.php"><i class="fa fa-th-list fa-fw"></i> Meus Projetos</a>
                        </li>

                        <li>
                            <a href="novo-projeto.php"><i class="fa fa-file-o fa-fw"></i> Novo Projetos</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-gear fa-fw"></i> Editar Conta<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="alterar-password.php"><i class="fa fa-key fa-fw"></i> Alterar Palavra
                                        Passe</a>
                                </li>
                                <li>
                                    <a href="dados-pessoais.php"><i class="fa fa-edit fa-fw"></i> Alterar Dados
                                        Pessoais</a>
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
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalTitulo">
                                Alterar
                            </button>
                        </div>

                        <!-- <button id="myBtn" class="btn btn-default btn-backoffice-size" style="margin-top:10px">Alterar</button> -->
                        <!-- Descrição -->
                        <div class="form-group" style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Descrição</label>
                            <p class="form-control-static">
                                <?php echo($projeto_descricao); ?>
                            </p>
                            <!-- Trigger/Open The Modal -->
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalDescricao">
                                Alterar
                            </button>
                        </div>

                        <!-- Autores -->
                        <div class="form-group" style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Autor(es) do projeto</label>

                            <p class="form-control-static">
                                <?php echo($projeto_autores); ?>
                            </p>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalAutores">
                                Alterar
                            </button>
                        </div>

                        <!-- Tipo -->
                        <div class="form-group" style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <!-- <label>Trabalho...</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" name="tipo" id="tipo1" value="1" checked>Teórico
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tipo" id="tipo2" value="2">Prático
                                </label>
                            </div> -->

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

                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalTipo">
                                Alterar
                            </button>
                        </div>

                        <!-- Ano letivo -->
                        <div class="form-group" style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <div class="form-inline">
                                <label style="padding-top:10px">Realizado no... </label>
                                <!-- <select id="iAnoLetivo" class="form-control" name="selectAnoLetivo" required>
                                <option value="1">1º ano</option>
                                <option value="2">2º ano</option>
                                <option value="3">3º ano</option>
                            </select> -->
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
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalAnoLetivo">
                                Alterar
                            </button>
                        </div>

                        <!-- Semestre -->
                        <div class="form-group" style="margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <!-- <label>Semestre</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" class="semestre" name="semestre" onChange="getUCS()" id="semestre1"
                                        value="1">1º
                                    Semestre
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" class="semestre" name="semestre" onChange="getUCS()" id="semestre2"
                                        value="2">2º Semestre
                                </label>
                            </div> -->

                            <div class="form-inline">
                                <label>Desenvolvido no... </label>

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
                        <div class="form-group">
                            <!-- <label>Na Unidade Curricular... </label> -->
                            <!-- <select id="iSelectUC" class="form-control" name="selectUC" required> -->
                            <?php
                                    /*
                                    //-- Select do nome das UCs
                                    $resultUC = mysqli_query($connectDB, "SELECT nome FROM unidade_curricular ORDER BY nome");

                                    if (mysqli_num_rows($resultUC) > 0) {
                                        while ($row = $resultUC->fetch_assoc()) {
                                            echo ("<option>" . $row['nome'] . "</option>");
                                        }
                                    }*/
                                    ?>
                            <!-- </select> -->
                            <div class="form-inline">
                                <label>Na Unidade Curricular de... </label>
                                <p class="form-control-static">
                                    <?php echo($projeto_unidade_curricular); ?>
                                </p>
                            </div>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalSemestreUC">
                                Alterar
                            </button>
                        </div>
                    </div>
                    <!-- End: Coluna 1 -->

                    <!-- Coluna 2 -->
                    <div class="col-lg-6" style="">

                        <!-- Data -->
                        <div class="form-group">
                            <label>Data em que o projeto foi finalizado</label>
                            <input type="date" class="form-control" name="data" required>
                            <p class="help-block">Exemplo: 12/03/2019</p>
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
                        <div class="form-group" style="margin-top:30px; margin-top:30px; border-top: 1px solid rgba(46, 207, 207, 0.322);">
                            <label style="padding-top:10px">Palavras-Chave</label>

                            <p class="form-control-static">
                                <?php echo($projeto_palavraschave); ?>
                            </p>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalPalavrasChave">
                                Alterar
                            </button>
                        </div>

                        <!-- Fotografia -->
                        <div class="form-group" style="margin-top:30px">
                            <label>Insira imagem</label>
                            </br>
                            <div style="display:inline-flex; margin-bottom: 15px">
                                <p style="min-width:110px">Nº de Imagens:</p>
                                <select id="iselectIMG" class="form-control" name="num-fotos" onchange="addRemoveIMG()"
                                    style="max-width: 100px; max-height:30px">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                            </div>
                            <div id="icontainerIMG">
                                <input type="file" name="imagem">
                            </div>
                        </div>

                        <!-- Ficheiro -->
                        <div class="form-group" style="margin-top:30px">
                            <label>Insira documento PDF (Opcional)</label>

                            <input type="file" id="iFicheiro" name="ficheiro" onChange="verificaLimitesFicheiro()"
                                accept="application/pdf">

                            <p class="help-block" id="iHintFicheito">Insira um ficheiro PDF com tamanho máximo
                                de 2MB</p>

                        </div>

                        <!-- Video -->
                        <div class="form-group" style="margin-top:30px">
                            <label>Vídeo - Youtube</label>
                            <p class="form-control-static">
                                <!-- Conteúdo do paragrafo -->
                                <?php
                                if (empty($projeto_video)) {
                                    echo("Sem vídeo!");
                                } else {
                                    echo(" https://www.youtube.com/watch?v=" . $projeto_video);
                                }
                                ?>
                            </p>
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal" data-target="#modalVideo">
                                <!-- Nome do botão -->
                                <?php
                                if (!empty($projeto_video)) {
                                    echo("Alterar");
                                } else {
                                    echo("Inserir");
                                }
                                ?>
                            </button>
                        </div>

                    </div>
                    <!-- End: Coluna 2 -->

                </div>
                <!-- /.row -->


            </div>
            <!-- /#wrapper -->
            <footer class="sticky-footer">
                <div style="margin: 20px 0; padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;text-align:center!important;line-height: 1; font-size: 1.2rem;">
                    <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro
                            Magalhães</a> 2019</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal -> TITULO -->
    <div class="modal fade" id="modalTitulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Título</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <!-- <h4 class="modal-title" id="myModalLabel">Modal title</h4> -->
                </div>
                <!-- <form id='form_titulo' action='delete_edit_projeto.php' enctype='multipart/form-data' method='POST'> -->
                <div class="modal-body">
                    <input type="text" class="form-control" name="titulo_projeto" maxlength="50" required placeholder="Insira o título do projeto"
                        value="<?php echo($projeto_titulo); ?>">
                    <!-- atributos hidden para enviar no submit do form -->
                    <input type='hidden' value="change_titulo" name='action'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeTitulo()">Alterar</button>
                    <!-- <input type="submit" class="btn btn-primary" value="Alterar"> -->
                </div>
                <!-- </form> -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> TITULO -->

    <!-- Modal -> DESCRICAO -->
    <div class="modal fade" id="modalDescricao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Descrição</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea id="idescricao" class="form-control" name="descricao_projeto" pattern="[a-zA-Z0-9!@#$%^*_|]{6,1000}"
                        rows="10" maxlength="1000" required placeholder="Insira a descrição do projeto"><?php echo($projeto_descricao); ?></textarea>
                    <p id="helpDescricao" class="help-block">Carateres: 0 de 1000</p>
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
    <div class="modal fade" id="modalAutores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Autor(es) deste projeto</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="autores_projeto" minlength="8" maxlength="100"
                        placeholder="Insira os autores do projeto (separados por ponto e vígula)" value="<?php echo($projeto_autores); ?>">
                    <p class="help-block tooltip-demo">Exemplo: Luís Mota;João Almeida
                        <a><i class="fa fa-info-circle fa-fw" data-toggle="tooltip" data-placement="right" title="De modo a que outros autores possam editar o projeto, insira o nome tal como estes estão registados no site."></i></a>
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
    <div class="modal fade" id="modalTipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Trabalho...</label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="display: block;">
                        <label class="radio-inline">
                            <input type="radio" name="tipo_projeto" id="tipo1" value="1" checked>Teórico
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="tipo_projeto" id="tipo2" value="2">Prático
                        </label>
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

    <!-- Modal -> SEMESTRE -->
    <div class="modal fade" id="modalSemestreUC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <label>Trabalho desenvolvido no... </label>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Semestre -->
                    <div class="form-group">
                        <div style="display: block;">
                            <label class="radio-inline">
                                <input type="radio" class="semestre" name="semestre_projeto" onChange="getUCS()" id="semestre1"
                                    value="1">1º Semestre
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="semestre" name="semestre_projeto" onChange="getUCS()" id="semestre2"
                                    value="2">2º Semestre
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
                    <button type="button" class="btn btn-primary" onclick="changeSemestreUC()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> SEMESTRE -->

    <!-- Modal -> ANO LETIVO -->
    <div class="modal fade" id="modalAnoLetivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Projeto realizado no...</label>
                </div>
                <div class="modal-body">
                    <select id="iAnoLetivo" class="form-control" name="selectAnoLetivo_projeto" required>
                        <option value="1">1º ano</option>
                        <option value="2">2º ano</option>
                        <option value="3">3º ano</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="changeAnoLetivo()">Alterar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> ANO LETIVO -->

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
                    <input type="text" class="form-control" name="palavraschave_projeto" value="<?php echo($projeto_palavraschave); ?>"
                        minlength="5" maxlength="50" placeholder="Insira as palavras chave do projeto (separadas por ponto e vígula)">
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
    <div class="modal fade" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <input type="url" class="form-control" name="video_projeto" value="<?php echo($input_video_value); ?>"
                        minlength="10" maxlength="50" placeholder="https://www.youtube.com/watch?v=Cq54GSWDaYI" />

                    <p class="help-block" id="help_video">Exemplo: https://www.youtube.com/watch?v=Cq54GSWDaYI</p>
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
//-- Semestre selecionado
function getUCS() {
    var semestre = $('.semestre:checked').val();

    //console.log("Semestre: " + semestre);
    $('#iSelectUC').empty();

    $.ajax({
        type: "GET",
        url: 'novoprojeto_ucs.php',
        data: {
            'action': 'get_ucs',
            'semestre': semestre
        },
        dataType: 'json',
        success: function(response) {
            $.each(response, function(index, element) {
                console.log(element); // print json code
                $("#iSelectUC").append("<option value='" + element.idunidade_curricular + "'>" +
                    element.nome + "</option>");
            });
            //alert(response);
        }
    });
}

//-- Mostra o numero de letras na descrição (textarea)
$("#idescricao").keyup(function() {
    $("#helpDescricao").text($(this).val().length + "/1000");
});

//-- Adiciona ou remove imagens dependendo do numero selecionado
function addRemoveIMG() {
    var containerIMG = document.getElementById('icontainerIMG');
    var selector = document.getElementById('iselectIMG');
    var value = selector[selector.selectedIndex].value;

    containerIMG.innerHTML = "";

    for (var i = 1; i <= value; i++) {
        var tempimg = document.createElement("input");
        tempimg.setAttribute("type", "file");
        tempimg.name = "image[]";
        tempimg.required = true;
        tempimg.accept = "image/jpeg, image/png";
        tempimg.multiple = true;

        tempimg.onchange = function() {
            var limiteSize = 1020; // 1 Megabyte
            var file = this.files[0];
            var input = this;
            console.log(file);

            if (file.type === "image/png" || file.type === "image/jpeg") {
                console.log("Ficheiro é png ou jpeg!");

            } else {
                console.log("Ficheiro não é png ou jpeg!");
                alert("A imagem não é de tipo suportado.");
                this.value = "";


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
                        //readImageFile(file, );
                        if (w > 1980 || h > 1080 || size > limiteSize) {
                            alert(
                                "A imagem tem tamanho superior a 1MB ou dimensões superiores a 1960*1080."
                            );

                            console.log(
                                "A imagem tem tamanho superior a 1mb ou dimensoes superiores a 1960*1080"
                            );
                            valido = false;

                        } else {
                            console.log("A imagem não tem tamanho superior a 1mb");
                            valido = true;
                        }
                    } else {
                        console.log("A imagem não é png ou jpeg");
                        alert("Imagens não é de tipo suportado!");

                        valido = false;
                    }

                    if (!valido) {
                        console.log("Imagem não valida");
                        input.value = "";

                    } else {
                        console.log("Imagem é valida");
                    }
                }
            };
            reader.readAsDataURL(file, input);
            //##### End of reader

        }

        if (i > 1)
            tempimg.style = "margin-top:15px; margin-bottom:15px";

        containerIMG.appendChild(tempimg);

        //<p class="help-block" id="iHintFicheito">Insira um ficheiro PDF com tamanho máximo de 500KB</p>
        var tempHint = document.createElement("p");
        tempHint.name = "hintImage";
        tempHint.id = "hintImage";
        tempHint.className = "help-block";
        tempHint.innerHTML = "Insira uma PNG/JPEG com tamanho máximo de 1MB";
        containerIMG.appendChild(tempHint);
    }
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

//## NOT USED
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
/*
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
*/

//-- Pedido AJAX para alterar titulo do projeto
function changeTitulo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var tituloprojeto = $('input[name="titulo_projeto"]').val();

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
}

//-- Pedido AJAX para alterar descricao do projeto
function changeDescricao() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var descricaoprojeto = $('textarea[name="descricao_projeto"]').val();

    // console.log("Descricao " + descricaoprojeto);

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
}

//-- Pedido AJAX para alterar os autores do projeto
//-- TODO: Meter user a ser sempre dono do projeto
function changeAutores() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var autoresprojeto = $('input[name="autores_projeto"]').val();

    console.log("autores: " + autoresprojeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_autores',
            'id_projeto': idprojeto,
            'autores_projeto': autoresprojeto
        },
        success: function(response) {
            // alert(response);
            location.reload();

        }
    });
}

//-- Pedido AJAX para alterar as palavras-chave do projeto
function changePalavrasChave() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var palavras_chave_projeto = $('input[name="palavraschave_projeto"]').val();

    console.log("Palavras-chave: " + palavras_chave_projeto);

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
}

//-- Pedido AJAX para alterar o video do projeto
function changeVideo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var video_projeto = $('input[name="video_projeto"]').val();

    console.log("video: " + video_projeto);

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
                "https://www.youtube.com/watch?v=Cq54GSWDaYI");
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
// TODO: Pronto a enviar por ajax
function changeTipo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var tipo_projeto = $('input[name="tipo_projeto"]:checked').val();

    console.log("Tipo: " + tipo_projeto);

    $.ajax({
        type: "POST",
        url: 'delete_edit_projeto.php',
        data: {
            'action': 'change_tipo',
            'id_projeto': idprojeto,
            'tipo_projeto': tipo_projeto
        },
        success: function(response) {
            alert(response);
            location.reload();
        }
    });
}

//-- Pedido AJAX para alterar o Ano Letivo do projeto
// TODO: Pronto a enviar pedido ajax
function changeAnoLetivo() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var ano_letivo_projeto = $('select[name="selectAnoLetivo_projeto"]').val();

    console.log("Ano_Letivo: " + ano_letivo_projeto);

    // $.ajax({
    //     type: "POST",
    //     url: 'delete_edit_projeto.php',
    //     data: {
    //         'action': 'change_video',
    //         'id_projeto': idprojeto,
    //         'video_projeto': video_projeto
    //     },
    //     success: function(response) {
    //         // alert(response);
    //         location.reload();
    //     }
    // });
}

//-- Pedido AJAX para alterar o semestre e unidade curricular do projeto
function changeSemestreUC() {
    var idprojeto = $('input[name="id_projeto"]').val();
    var semestre_projeto = $('input[name="video_projeto"]').val();
    var uc_projeto = $('input[name="video_projeto"]').val();

    console.log("video: " + video_projeto);

    // $.ajax({
    //     type: "POST",
    //     url: 'delete_edit_projeto.php',
    //     data: {
    //         'action': 'change_video',
    //         'id_projeto': idprojeto,
    //         'video_projeto': video_projeto
    //     },
    //     success: function(response) {
    //         // alert(response);
    //         location.reload();
    //     }
    // });
}

//##FIXME: LIXO
$(document).ready(function() {

    $('#btnInsert').click(function() {
        addCheckbox2($('#txtNameCat').val());
    });

    addRemoveIMG();
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