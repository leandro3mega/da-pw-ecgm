<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

clearstatcache();   // limpa a cache

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
    exit();
} elseif ($_SESSION['cargo'] === "Administrador") {
    header("location:index.php");
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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Alterar Dados Pessoais</title>

    <!-- Browser image -->
    <link rel="icon" href="images/website/logotipo_transparente.png">

    <script src="js/jquery-3.1.1.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- estilos desenvolvidos -->
    <link rel="stylesheet" href="css/stylesheet.css" <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and
        media queries -->
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
                        <h1 class="page-header">Alterar Dados Pessoais</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- Nome -->
                                    <div class="form-group" id="iDivLabelNome" style="display:block;">
                                        <label>Nome</label>
                                        <p class="form-control-static">
                                            <?php echo($nome); ?>
                                        </p>
                                    </div>

                                    <div class="form-group" id="iDivInputNome" style="display:none">
                                        <label>Nome</label>
                                        <input type="text" id="iInputNome" class="form-control"
                                            placeholder="Insira o seu nome" value="<?php echo $nome; ?>">
                                        <button id="iBtnSubmeterNome" class="btn btn-default btn-backoffice-size"
                                            onclick="changeNome()">
                                            Submeter
                                        </button>
                                        <button id="iBtnCancelarNome" onclick="showhideNome()"
                                            class="btn btn-default btn-backoffice-size">
                                            Cancelar
                                        </button>
                                        <div class="form-group"></div>
                                    </div>
                                    <button id="iBtnAlterarNome" onclick="showhideNome()"
                                        class="btn btn-default btn-backoffice-size">
                                        Alterar
                                    </button>
                                    <!-- End Nome -->

                                    <!-- Email -->
                                    <div class="form-group div-margin-separa" id="iDivLabelEmail" style="display:block">
                                        <label>Email</label>
                                        <p class="form-control-static">
                                            <?php echo $email; ?>
                                        </p>
                                    </div>

                                    <div class="form-group div-margin-separa" id="iDivInputEmail" style="display:none">
                                        <label>Email</label>
                                        <input type="email" name="email" id="iInputEmail" class="form-control"
                                            placeholder="Insira novo e-mail" value="<?php echo $email; ?>">
                                        <p id="ihelpEmail" name="helpEmail" class="help-block"
                                            style="color:#555555; padding:5px">
                                            Exemplo: user@ipvc.pt</p>
                                        <button id="iBtnSubmeterEmail" name="btn_submeter_email"
                                            class="btn btn-default btn-backoffice-size" style="margin-top:15px"
                                            onclick="changeEmail()">
                                            Submeter
                                        </button>
                                        <button id="iBtnCancelarEmail" class="btn btn-default btn-backoffice-size"
                                            style="margin-top:15px" onclick="showhideEmail()">
                                            Cancelar
                                        </button>
                                    </div>
                                    <button id="iBtnAlterarEmail" onclick="showhideEmail()"
                                        class="btn btn-default btn-backoffice-size" style="margin-bottom:20px">
                                        Alterar
                                    </button>
                                    <!-- End Email -->

                                </div>
                                <div class="col-lg-6">
                                    <!--Fotografia-->
                                    <!--Fotografia Atual-->
                                    <div class="form-group" id="iDivImgFotografia" style="display:block;">
                                        <label>Fotografia</label>

                                        <!-- <div class="form-control-static" style="width:50%; heigth:auto;"> -->
                                        <div class="form-control-static" style="width:auto; heigth:200px;">
                                            <?php
                                            $diretorioIMG = "images/utilizadores/";

                                            if ($tipo == 1) {
                                                $sql = "SELECT fotografia FROM aluno WHERE fk_idutilizador=?";
                                            } elseif ($tipo == 2) {
                                                $sql = "SELECT fotografia FROM docente WHERE fk_idutilizador=?";
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
                                                        $stmt->bind_result($r_nome);
                                                        if ($stmt->fetch()) {
                                                            echo("<img class=' img-fluid img-thumbnail' src='" . $diretorioIMG . $r_nome . "' alt=''>");
                                                        }
                                                    } else {
                                                        // echo"Não foi encontrado video ";
                                                    }
                                                } else {
                                                    echo "Algo correu mal. Por favor tente de novo.";
                                                }
                                            }
                                            $stmt->close();
                                            
                                            ?>
                                        </div>
                                    </div>
                                    <!--Inserir Fotografia-->
                                    <div class="form-group" id="iDivFileFotografia" style="display:none">
                                        <label>Fotografia</label>
                                        <!-- <form id="avatar_file_upload_form" role="form" action="uploadimage.php" -->
                                        <form id="avatar_file_upload_form" role="form" action="changeuserdata.php"
                                            method="post" enctype='multipart/form-data' style="">
                                            <div class="form-group">
                                                <input type="file" id="ifotografia" name="fotografia" required
                                                    id="avatar_file_upload_field" accept="image/jpeg,image/png"
                                                    onChange="verificaLimitesImagem()" />
                                                <!-- <input type="file" id="fotografia" name="avatar" id="avatar_file_upload_field"
                                                    accept="image/jpeg,image/png" onChange="verificaLimitesImagem()" /> -->
                                                <p class="help-block" id="iHintImagem">Insira uma Imagem PNG/JPEG com
                                                    tamanho máximo de 1MB</p>
                                                <input type="hidden" name="action" value="change_fotografia" />
                                                <input type="hidden" name="iduser" id="iIdUser"
                                                    value="<?php echo($id); ?>" />
                                                <input type="submit" class="btn btn-default btn-backoffice-size"
                                                    style="margin-top:15px" />
                                                <button id="iBtnCancelarFotografia" onclick="showhideFotografia()"
                                                    class="btn btn-default btn-backoffice-size" style="margin-top:15px">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </form>
                                        <!--
                                        <button id="iBtnSubmeterFotografia" class="btn btn-default btn-backoffice-size" onclick="changeFotografia()">
                                            Submeter
                                        </button>
                                        -->

                                        <div class="form-group"></div>
                                    </div>

                                    <button id="iBtnAlterarFotografia" onclick="showhideFotografia()"
                                        class="btn btn-default btn-backoffice-size">
                                        Alterar
                                    </button>
                                    <!-- End Fotografia -->
                                    <!--
                                    <div class="form-group">
                                        <form id="avatar_file_upload_form" role="form" action="uploadimage.php" method="post" enctype='multipart/form-data'style="">
                                            <div class="form-group">
                                                <input type="file" name="avatar" id="avatar_file_upload_field" accept="image/jpeg,image/pjpeg,image/bmp,image/gif,image/jpeg,image/png"/>
                                                <input type="hidden" name="iduser" id="iIdUser" value="<?php echo($id); ?>"/>
                                                <input type="submit" class="btn btn-default btn-backoffice-size" style="margin-top:15px"/>
                                            </div>
                                        </form>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <!--
                <footer style="width: calc(100% - 225px);display: flex;
                position: absolute; right: 0; bottom: 0; height: 80px; background-color: #e9ecef;">
                    <div style="margin-bottom: auto!important;margin-top: auto!important;max-width: 960px;width: 100%;
                    padding-right: 15px;padding-left: 15px;margin-right: auto; margin-left: auto;">
                        <div style="line-height: 1; font-size: 0.8rem;text-align:center!important; 
                        margin-bottom: auto!important;margin-top: auto!important;">
                            <span>Copyright © Your Website 2018</span>
                        </div>
                    </div>
                </footer>
-->
            <!-- Esta está boa
        <footer style="position: absolute; width: calc(100% - 310px); bottom: 0;">
            <div style="margin: 20px 0; padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;text-align:center!important;line-height: 1; font-size: 1.2rem;">
                <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro Magalhães</a> 2019</span>
            </div>
        </footer>
-->
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

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })
    // popover demo
    $("[data-toggle=popover]")
        .popover()

    $(document).ready(function() {
        $('button[name="btn_submeter_email"]').attr("disabled", "disabled");

    });

    var nomeInputHidden = true;
    var emailInputHidden = true;
    var fotografiaInputHidden = true;

    function showhideNome() {
        var divLabelNome = document.getElementById("iDivLabelNome");
        var divInputNome = document.getElementById("iDivInputNome");
        var btnAlterarNome = document.getElementById("iBtnAlterarNome");
        var btnSubmeterNome = document.getElementById("iBtnSubmeterNome");
        var btnCancelarNome = document.getElementById("iBtnCancelarNome");

        if (nomeInputHidden) nomeInputHidden = false;
        else nomeInputHidden = true;

        if (nomeInputHidden) {
            divLabelNome.style = "display: block";
            divInputNome.style = "display: none";
            btnAlterarNome.style = "display: block";
            btnSubmeterNome.style = "display: none; margin-top:15px";
            btnCancelarNome.style = "display: none; margin-top:15px";
        } else {
            divLabelNome.style = "display: none";
            divInputNome.style = "display: block";
            btnAlterarNome.style = "display: none; margin-top:15px";
            btnSubmeterNome.style = "display: inline-block; margin-top:15px";
            btnCancelarNome.style = "display: inline-block; margin-top:15px";
        }
    }

    function showhideEmail() {
        var divLabelEmail = document.getElementById("iDivLabelEmail");
        var divInputEmail = document.getElementById("iDivInputEmail");
        var btnAlterarEmail = document.getElementById("iBtnAlterarEmail");
        var btnSubmeterEmail = document.getElementById("iBtnSubmeterEmail");
        var btnCancelarEmail = document.getElementById("iBtnCancelarEmail");

        if (emailInputHidden) emailInputHidden = false;
        else emailInputHidden = true;

        if (emailInputHidden) {
            divLabelEmail.style = "display: block";
            divInputEmail.style = "display: none";
            btnAlterarEmail.style = "display: block";
            //btnSubmeterEmail.style = "display: none; margin-top:15px";
            //btnCancelarEmail.style = "display: none; margin-top:15px";
        } else {
            divLabelEmail.style = "display: none";
            divInputEmail.style = "display: block";
            btnAlterarEmail.style = "display: none";
            //btnSubmeterEmail.style = "display: none; margin-top:15px";
            //btnCancelarEmail.style = "display: none; margin-top:15px";

        }
    }

    function showhideFotografia() {
        var divImgFotografia = document.getElementById("iDivImgFotografia");
        var divFileFotografia = document.getElementById("iDivFileFotografia");
        var btnAlterarFotografia = document.getElementById("iBtnAlterarFotografia");
        var btnSubmeterFotografia = document.getElementById("iBtnSubmeterFotografia");
        var btnCancelarFotografia = document.getElementById("iBtnCancelarFotografia");

        if (fotografiaInputHidden) fotografiaInputHidden = false;
        else fotografiaInputHidden = true;

        if (fotografiaInputHidden) {
            divImgFotografia.style = "display: block";
            divFileFotografia.style = "display: none";
            btnAlterarFotografia.style = "display: block";
            //btnSubmeterFotografia.style = "display: none; margin-top:15px";
            //btnCancelarFotografia.style = "display: none; margin-top:15px";
        } else {
            divImgFotografia.style = "display: none";
            divFileFotografia.style = "display: block";
            btnAlterarFotografia.style = "display: none";
            //btnSubmeterFotografia.style = "display: none; margin-top:15px";
            //btnCancelarFotografia.style = "display: none; margin-top:15px";

        }
    }

    //-- Ajax to submite change of the user name
    function changeNome() {
        novoNome = $('#iInputNome').val();
        // console.log("Novo nome: " + novoNome);

        $.ajax({
            type: "POST",
            url: 'changeuserdata.php',
            data: {
                'action': 'change_name',
                'name': novoNome
            },
            success: function(html) {
                alert(html);
                location.reload();
            }

        });
    }

    var comfirma_pass = document.getElementById("iInputEmail");
    comfirma_pass.addEventListener("keyup", buscaEmails);
    var email_valido = false;

    function buscaEmails() {
        var email = $('input[name="email"]').val()
        console.log("Input: " + email);

        if (validateEmail(email)) {
            console.log("Funciona");

            $.ajax({
                type: "POST",
                url: 'fetch_ucs_users.php',
                data: {
                    'action': 'fetch_emails',
                    'input_email': email
                },
                success: function(response) {
                    console.log(response);

                    if (response == "True") {
                        console.log("Encontrou");
                        email_valido = false;
                        // $("input[type=submit]").attr("disabled", "disabled");
                        $('button[name="btn_submeter_email"]').attr("disabled", "disabled");
                        $("#ihelpEmail").text("O email já está atribuido a um utilizador");
                        $("#ihelpEmail").css({
                            "color": "rgb(216, 79, 79)",
                            "padding": "5px"
                        });

                    } else if (response == "False") {
                        email_valido = true;
                        console.log("Não Encontrou");
                        // $("input[type=submit]").removeAttr("disabled");
                        $('button[name="btn_submeter_email"]').removeAttr("disabled");
                        $("#ihelpEmail").text("Email válido");
                        $("#ihelpEmail").css({
                            "color": "rgb(79, 216, 132)",
                            "padding": "5px"
                        });
                    }
                }
            });
        } else {
            email_valido = false;
            // $("input[type=submit]").attr("disabled", "disabled");
            $('button[name="btn_submeter_email"]').attr("disabled", "disabled");
            $("#ihelpEmail").text("Exemplo: user@ipvc.pt");
            $("#ihelpEmail").css({
                "color": "#555555",
                "padding": "5px"
            });

        }
    }

    //-- Verifica o padrão do email -> exemplo: user@hotmail.com
    function validateEmail(email) {
        var re =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    //-- Ajax to submite change of the user email
    function changeEmail() {
        novoEmail = $('#iInputEmail').val();
        // console.log("Novo Email: " + novoEmail);

        if (email_valido == true) {
            $.ajax({
                type: "POST",
                url: 'changeuserdata.php',
                data: {
                    'action': 'change_email',
                    'email': novoEmail
                },
                success: function(html) {
                    alert(html);
                    location.reload();
                }

            });
        }
    }

    //-- Verifica Limites da IMAGEM (se é PNG/JPG, e se o tamanho é abaixo do limite)
    function verificaLimitesImagem() {
        var input_imagem = document.getElementById("ifotografia");
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
                    if (w > 1960 || h > 1080 || size > limiteSize) {
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
    </script>

</body>

</html>