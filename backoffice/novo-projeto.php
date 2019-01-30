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
        <?php include "sidemenu.php"; ?>

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
                            <input type="text" class="form-control" name="titulo" minlength="10" maxlength="50"
                                autofocus required placeholder="Insira o título do projeto">
                            <!-- oninvalid="this.setCustomValidity('ERROR_TEXT')" oninput="this.setCustomValidity('')" -->
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea id="idescricao" class="form-control" name="descricao"
                                pattern="[a-zA-Z0-9!@#$%^*_|]{6,1000}" rows="6" minlength="10" maxlength="600" required
                                placeholder="Insira a descrição do projeto"></textarea>
                            <p id="helpDescricao" class="help-block">Carateres: 0 de 600</p>
                        </div>

                        <!-- Autores -->
                        <div class="form-group">
                            <label>Autor(es) deste projeto</label>
                            <input type="text" class="form-control" name="autores" minlength="10" maxlength="200"
                                placeholder="Insira os autores do projeto (separados por ponto e vígula)">
                            <p class="help-block tooltip-demo">Exemplo: Luís Mota;João Almeida
                                <a><i class="fa fa-info-circle fa-fw" data-toggle="tooltip" data-placement="right"
                                        title="Insira o nome de todos os autores, incluindo o seu."></i></a>
                            </p>

                        </div>
                        <div class="row" style="margin-top:20px; border-top: 1px solid #eee;">

                            <!-- Coluna 1 -->
                            <div class="col-lg-6" style=" padding-top:20px;">
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

                                <!-- Ano letivo -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>O projeto foi realizado no...</label>
                                    <div style="display: block;">
                                        <label class="radio-inline">
                                            <input type="radio" class="ano_curricular" name="ano_curricular"
                                                onChange="getUCS()" id="ano_curricular1" value="1" checked>1º Ano
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="ano_curricular" name="ano_curricular"
                                                onChange="getUCS()" id="ano_curricular2" value="2">2º Ano
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="ano_curricular" name="ano_curricular"
                                                onChange="getUCS()" id="ano_curricular3" value="3">3º Ano
                                        </label>
                                    </div>
                                </div>

                                <!-- Semestre -->
                                <div class="form-group">
                                    <label>Semestre</label>
                                    <div style="display: block;">
                                        <label class="radio-inline">
                                            <input type="radio" class="semestre" name="semestre" onChange="getUCS()"
                                                id="semestre1" value="1" checked>1º
                                            Semestre
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="semestre" name="semestre" onChange="getUCS()"
                                                id="semestre2" value="2">2º Semestre
                                        </label>
                                    </div>
                                </div>

                                <!-- Unidade Curricular -->
                                <div class="form-group">
                                    <label>Unidade Curricular associada ao projeto</label>
                                    <select id="iSelectUC" class="form-control" name="selectUC" required>
                                    </select>
                                </div>

                                <!-- Palavras Chave -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Palavras-Chave</label>
                                    <input type="text" class="form-control" name="palavras-chave" minlength="4"
                                        maxlength="100" required
                                        placeholder="Insira as palavras chave do projeto (separadas por ponto e vígula)">
                                    <p class="help-block">Exemplo: Desenho; Mockup</p>
                                </div>

                                <!-- Video -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Inserir vídeo (Opcional)</label>
                                    <input type="url" class="form-control" name="video" pattern="https?://.+"
                                        placeholder="https://www.youtube.com/watch?v=BrK9atbrWFY">
                                    <p class="help-block">Exemplo: https://www.youtube.com/watch?v=BrK9atbrWFY</p>
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

                                <!-- Ficheiro -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Insira documento PDF (Opcional)</label>

                                    <input type="file" id="iFicheiro" name="ficheiro"
                                        onChange="verificaLimitesFicheiro()" accept="application/pdf">

                                    <p class="help-block" id="iHintFicheito">Insira um ficheiro PDF com tamanho máximo
                                        de 2MB</p>
                                </div>

                                <!-- Fotografia -->
                                <div class="form-group" style="margin-top:30px">
                                    <label>Insira imagem</label>
                                    </br>
                                    <div style="display:inline-flex; margin-bottom: 15px">
                                        <p style="min-width:110px">Nº de Imagens:</p>
                                        <select id="iselectIMG" class="form-control" name="num-fotos"
                                            onchange="addRemoveIMG()" style="max-width: 100px; max-height:30px">
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
                            </div>

                        </div>
                        <!-- <div style="width: 200px; display: block; margin-left:auto; margin-right: auto; margin-top:20px; margin-bottom:80px"> -->
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-backoffice-size"
                                    style="min-width:200px; float: right;" value="Submeter">
                            </div>
                            <div class="col-lg-6">
                                <a class="btn btn-default btn-backoffice-size" onclick="previewProjeto()"
                                    style="min-width:200px; float: left;">
                                    Pre-visualizar
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- /.row -->
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

            <!-- footer -->
            <?php
            include 'footer.html';
            ?>

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
        // tempimg.multiple = true;

        tempimg.onchange = function() {
            var limiteSize = 2024; // 2 Megabyte
            var file = this.files[0];
            var input = this;
            console.log(file);

            if (file.type === "image/png" || file.type === "image/jpeg") {
                console.log("Ficheiro é png ou jpeg!");

            } else {
                console.log("Ficheiro não é png ou jpeg!");
                alert("A imagem não é suportada. Insira uma imagem PNG ou JPG");
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
                                "A imagem tem tamanho superior a 2MB ou dimensões superiores a 1960x1080."
                            );

                            console.log(
                                "A imagem tem tamanho superior a 2mb ou dimensoes superiores a 1960x1080"
                            );
                            valido = false;

                        } else {
                            console.log("A imagem não tem tamanho superior a 2mb");
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
            tempimg.style = "margin-top:15px;";

        containerIMG.appendChild(tempimg);

        //<p class="help-block" id="iHintFicheito">Insira um ficheiro PDF com tamanho máximo de 500KB</p>
        var tempHint = document.createElement("p");
        tempHint.name = "hintImage";
        tempHint.id = "hintImage";
        tempHint.className = "help-block";
        tempHint.innerHTML = "Insira uma Imagem PNG/JPEG com tamanho máximo de 1MB";
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

    //-- So aceita o ficheiro de este for pdf e tiver até 2MB
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

//-- Abre pre-visualização de um novo projeto
function previewProjeto() {
    var param = {
        'preview_titulo': $('input[name="titulo"]').val(),
        'preview_descricao': $('textarea[name="descricao"]').val(),
        'preview_autores': $('input[name="autores"]').val(),
        'preview_palavras': $('input[name="palavras-chave"]').val(),
        'preview_uc': $('#iSelectUC option:selected').text()
    };

    OpenWindowWithPost(
        "preview-projeto.php",
        "width=1200,height=500,left=39,top=30,resizable=no,scrollbars=no",
        "NewFile",
        param
    );
}


function OpenWindowWithPost(url, windowoption, name, params) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = params[i];
            form.appendChild(input);
        }
    }

    document.body.appendChild(form);

    //note I am using a post.htm page since I did not want to make double request to the page 
    //it might have some Page_Load call which might screw things up.
    window.open("post.htm", name, windowoption);

    form.submit();

    document.body.removeChild(form);
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


//##TODO: LIXO
$(document).ready(function() {

    $('#btnInsert').click(function() {
        addCheckbox2($('#txtNameCat').val());
    });

    addRemoveIMG();
});

//##TODO: LIXO
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