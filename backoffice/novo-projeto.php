<?php
session_start();

require_once("connectdb.php");

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
                    <form role="form" action="novoprojeto.php" method="post" enctype='multipart/form-data'>
                        <!-- Titulo -->
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" class="form-control" name="titulo" maxlength="50" autofocus required
                                placeholder="Insira o título do projeto">
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea id="idescricao" class="form-control" name="descricao" pattern="[a-zA-Z0-9!@#$%^*_|]{6,1000}"
                                rows="10" maxlength="1000" required placeholder="Insira a descrição do projeto"></textarea>
                            <p id="helpDescricao" class="help-block">Carateres: 0 de 1000</p>
                        </div>

                        <!-- Autores -->
                        <div class="form-group">
                            <label>Autor(es) deste projeto</label>
                            <input type="text" class="form-control" name="autores" maxlength="100" placeholder="Insira os autores do projeto (separados por ponto e vígula)">
                            <p class="help-block tooltip-demo">Exemplo: Luís Mota;João Almeida
                                <a><i class="fa fa-info-circle fa-fw" data-toggle="tooltip" data-placement="right"
                                        title="De modo a que outros autores possam editar o projeto, insira o nome tal como estes estão registados no site."></i></a>
                            </p>

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
                                            <input type="radio" class="semestre" name="semestre" onChange="getUCS()" id="semestre1"
                                                value="1">1º
                                            Semestre
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="semestre" name="semestre" onChange="getUCS()" id="semestre2"
                                                value="2">2º Semestre
                                        </label>
                                    </div>
                                </div>

                                <!-- Unidade Curricular -->
                                <div class="form-group">
                                    <label>Unidade Curricular associada ao projeto</label>
                                    <select id="iSelectUC" class="form-control" name="selectUC" required>
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
                                        de 500KB</p>

                                </div>

                                <!-- Video -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Inserir vídeo (Opcional)</label>
                                    <input type="url" class="form-control" name="video" pattern="https?://.+"
                                        placeholder="https://www.youtube.com/watch?v=Cq54GSWDaYI">
                                    <p class="help-block">Exemplo: https://www.youtube.com/watch?v=Cq54GSWDaYI</p>
                                </div>
                            </div>

                            <!-- Coluna 2 -->
                            <div class="col-lg-6" style="padding-top:20px; border-left: 1px solid #eee;">

                                <!-- Data -->
                                <div class="form-group">
                                    <label>Data em que o projeto foi finalizado</label>
                                    <input type="date" class="form-control" name="data" required>
                                    <p class="help-block">Exemplo: 12/03/2019</p>
                                </div>

                                <!-- Ano letivo -->
                                <div class="form-group">
                                    <label>O projeto foi realizado no...</label>
                                    <select id="iAnoLetivo" class="form-control" name="selectAnoLetivo" required>
                                        <option value="1">1º ano</option>
                                        <option value="2">2º ano</option>
                                        <option value="3">3º ano</option>
                                    </select>
                                </div>

                                <!-- Categorias -->
                                <!--
                                <div class="form-group" style="margin-top:30px">
                                    <label>Categorias que pretende associar ao seu projeto</label>
                                    <input type="text" class="form-control" name="categorias" placeholder="Insira as categorias do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Desenho;Illustração</p>
                                </div>
                                -->

                                <!-- Ferramentas -->
                                <!--
                                <div class="form-group" style="margin-top:30px">
                                    <label>Ferramentas utilizadas</label>
                                    <input type="text" class="form-control" name="ferramentas" placeholder="Insira as categorias do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Adobe Illustrator;Sketch</p>
                                </div>
                                -->

                                <!-- Ferramentas -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Ferramentas utilizadas</label>
                                    <div>
                                        <?php
                                        //-- Script de selecionar ferramentas

                                        $resultCategoria = mysqli_query($connectDB, "SELECT idferramenta, nome, descricao, empresa FROM ferramenta ORDER BY nome");

                                        if (mysqli_num_rows($resultCategoria) > 0) {
                                            while ($row = $resultCategoria->fetch_assoc()) {
                                                echo("
                                                <input type='checkbox' name='cb[]' value='" . $row['idferramenta'] . "'/> " . $row['nome'] . "<br/>
                                                ");
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Palavras Chave -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Palavras-Chave</label>
                                    <input type="text" class="form-control" name="palavras-chave" placeholder="Insira as palavras chave do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Desenho; Mockup</p>
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
                        <div style="width: 200px; display: block; margin-left:auto; margin-right: auto; margin-top:20px; margin-bottom:80px">
                            <input type="submit" class="btn btn-default btn-backoffice-size" style="min-width:200px"
                                value="Submeter">
                        </div>
                    </form>
                    <!-- /.row -->
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->
            <footer class="sticky-footer">
                <div style="margin: 20px 0; padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;text-align:center!important;line-height: 1; font-size: 1.2rem;">
                    <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro
                            Magalhães</a> 2019</span>
                </div>
            </footer>

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
            //tempimg.multiple = true;

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

                        // console.log("File Name: " + file.name);
                        // console.log("Width: " + w);
                        // console.log("Height: " + h);
                        // console.log("Size: " + Math.round((file.size / 1024)));
                        // console.log("File Type: " + file.type);
                        //console.log("Limite: " + limiteSize);

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

        if (tipo != "application/pdf" || (tamanho / 1024) > 500) {
            ficheiro.value = "";

            //-- Mostra hint de Invalidez
            hint.style = "color:rgb(216, 79, 79);";
            hint.innerHTML = "Ficheiro inválido. Insira um ficheiro PDF com até 500KB.";

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


    //## LIXO
    $(document).ready(function() {

        $('#btnInsert').click(function() {
            addCheckbox2($('#txtNameCat').val());
        });

        addRemoveIMG();
    });

    //## LIXO
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