<?php
session_start();

require_once("connectdb.php");

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Novo Projeto</title>

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
                        <h1 class="page-header">Criar Novo Projeto</h1>
                    </div>
                    <!-- /.col-lg-12 -->

                    <!-- Formulário -->
                    <form role="form">
                        <!-- Titulo -->
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" class="form-control"  maxlength="50" autofocus required placeholder="Insira o título do projeto"> 
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea id="idescricao" class="form-control" pattern="[a-zA-Z0-9!@#$%^*_|]{6,1000}" rows="10" maxlength="1000" required placeholder="Insira a descrição do projeto"></textarea>
                            <p id="helpDescricao" class="help-block">Carateres: 0 de 1000</p>
                        </div>

                        <!-- Autores -->
                            <div class="form-group">
                                <label>Autor(es) deste projeto</label>
                                <input type="text" class="form-control" maxlength="100" placeholder="Insira os autores do projeto (separados por ponto e vígula)">
                                <p class="help-block">Exemplo: Luís Mota;João Almeida</p>
                            </div>
                        <div class="row" style="margin-top:20px; border-top: 1px solid #eee;">

                            <!-- Coluna 1 -->
                            <div class="col-lg-6" style=" padding-top:20px; border-right: 1px solid #eee;">
                                <!-- Tipo -->
                                <div class="form-group">
                                    <label>Trabalho...</label>
                                    <div style="display: block;">
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo" id="tipo1" value="1" checked>Teórico
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo" id="tipo2" value="2">Prático
                                        </label>
                                    </div>
                                </div>

                                <!-- Semestre -->
                                <div class="form-group">
                                    <label>Semestre</label>
                                    <div style="display: block;">
                                        <label class="radio-inline">
                                            <input type="radio" class="semestre" onChange="getUCS()" name="semestre" id="semestre1" value="1">1º
                                            Semestre
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="semestre" onChange="getUCS()" name="semestre" id="semestre2" value="2">2º Semestre
                                        </label>
                                    </div>
                                </div>

                                <!-- Unidade Curricular -->
                                <div class="form-group">
                                    <label>Selecione Unidade Curricular para a qual o projeto foi desenvolvido</label>
                                    <select id="iSelectUC" class="form-control" name="selectUC">
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
                                    </select>
                                </div>

                                <!-- Fotografia -->
                                <div class="form-group">
                                    <label>Insira imagem</label>
                                    </br>
                                    <div style="display:inline-flex; margin-bottom: 15px">
                                        <p style="min-width:110px">Nº de Imagens</p>
                                        <select class="form-control" style="max-width: 100px">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>

                                    </div>
                                    <input type="file">
                                </div>

                                <!-- Ficheiro -->
                                <div class="form-group">
                                    <label>Insira documento PDF (Opcional)</label>
                                    <input type="file">
                                </div>

                                <!-- Video -->
                                <div class="form-group">
                                    <label>Vídeo a inserir no projeto (Opcional)</label>
                                    <input type="url" class="form-control" pattern="https?://.+" placeholder="https://www.youtube.com/watch?v=Cq54GSWDaYI">
                                    <p class="help-block">Exemplo: https://www.youtube.com/watch?v=Cq54GSWDaYI</p>
                                </div>
                            </div>

                            <!-- Coluna 2 -->
                            <div class="col-lg-6" style="padding-top:20px; border-left: 1px solid #eee;">

                                <!-- Palavras Chave -->
                                <div class="form-group">
                                    <label>Palavras-Chave</label>
                                    <input type="text" class="form-control" placeholder="Insira as palavras chave do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Desenho;Mockup</p>
                                </div>

                                <!-- Data -->
                                <div class="form-group">
                                    <label>Data em que o projeto foi finalizado</label>
                                    <input type="date" class="form-control">
                                    <p class="help-block">Exemplo: 12/03/2019</p>
                                </div>

                                <!-- Categorias -->
                                <div class="form-group">
                                    <label>Categorias que pretende associar ao seu projeto</label>
                                    <input type="text" class="form-control" placeholder="Insira as categorias do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Desenho;Illustração</p>
                                </div>

                                <!-- Ferramentas -->
                                <div class="form-group">
                                    <label>Ferramentas utilizadas</label>
                                    <input type="text" class="form-control" placeholder="Insira as categorias do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Adobe Illustrator;Sketch</p>
                                </div>
                                
                                <!--
                                <div class="form-group">
                                    <label>Categorias que pretende associar ao seu projeto</label>
                                    <div id="cblist" style="border:2px solid #ccc; width:300px; height: 100px; overflow-y: scroll; border-radius: 4px;">

                                        <div id="cblist" class="checkbox">
                                            <label for="cb1">
                                                <input type="checkbox" value="1" id="cb1" />
                                                1ª Categoria
                                            </label>
                                        </div>
                                        <div id="cblist" class="checkbox">
                                            <label for="cb2">
                                                <input type="checkbox" value="2" id="cb2" />
                                                2ª Categoria
                                            </label>
                                        </div>

                                    </div>
                                    <input type="text" id="txtNameCat" placeholder="Ou insira uma nova categoria" style="width:280px" />
                                    <input type="button" value="ok" id="btnInsert" />
                                </div>
                                -->


                                <!-- Video Embed teste -->
                                <!--
                                <div class="form-group">
                                    <label>Vídeo a inserir no projeto (Opcional)</label>
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/oNXLvzKFPOI?start=10"
                                        frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                -->
                            </div>
                        </div>
                        <div style="width: 200px; display: block; margin-left:auto; margin-right: auto; margin-top:20px; margin-bottom:40px">
                            <input type="submit" class="btn btn-default btn-backoffice-size" style="min-width:200px" value="Submeter">
                        </div>
                    </form>
                    <!-- /.row -->
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
<script>
    //-- Semestre selecionado
    function getUCS(){
        var semestre = $('.semestre:checked').val();
    
        //console.log("Semestre: " + semestre);
        $('#iSelectUC').empty();

        $.ajax({
                type: "GET",
                url: 'novoprojetoHelper.php',
                data:{'action':'get_ucs', 'semestre': semestre},
                dataType: 'json',
                success:function(response) {
                    $.each(response, function(index, element) {
                        console.log(element);   // print json code
                        $("#iSelectUC").append("<option value='" + element.idunidade_curricular + "'>" + element.nome + "</option>");
                    });
                    alert(response);
                }

            });

    }

    //-- Mostra o numero de letras na descrição (textarea)
    $("#idescricao").keyup(function(){
        $("#helpDescricao").text($(this).val().length + "/1000");
    });
    


    $(document).ready(function () {

        $('#btnInsert').click(function () {
            addCheckbox2($('#txtNameCat').val());
        });


    });

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