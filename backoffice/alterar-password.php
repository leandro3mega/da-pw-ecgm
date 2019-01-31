<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:iniciar-sessao.php");
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

    <title>Alterar Password</title>

    <script src="js/jquery-3.1.1.js"></script>

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
            <div class="container-fluid" style="min-height:500px">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Alterar Palavra Passe</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <!--
                                        <form role="form">
                                            <div class="form-group">
                                                <label>Palavra Passe Atual</label>
                                                <input class="form-control" placeholder="Insira a sua palavra passe">
                                            </div>
                                            
                                            <div class="form-group div-margin-separa">
                                                <label>Nova Palavra Passe</label>
                                                <input class="form-control" placeholder="Insira nova palavra passe">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Confirme Nova Palavra Passe</label>
                                                <input class="form-control" placeholder="Insira nova palavra passe">
                                            </div>
                                            
                                            <div class="form-group div-margin-separa">
                                                <button type="submit" class="btn btn-default btn-backoffice-size">Alterar</button>
                                                <button type="reset" class="btn btn-default btn-backoffice-size" style="margin-left: 10px">Limpar</button>
                                            </div>
                                        </form>
                                    -->
                                    <div class="form-group">
                                        <label>Palavra Passe Atual</label>
                                        <input type="password" id="iInputPass1" class="form-control"
                                            placeholder="Insira a sua palavra passe">
                                    </div>

                                    <div class="form-group div-margin-separa">
                                        <label>Nova Palavra Passe</label>
                                        <input type="password" id="iInputPass2" class="form-control"
                                            placeholder="Insira a nova palavra passe">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirmar a Nova Palavra Passe</label>
                                        <input type="password" id="iInputPass3" class="form-control"
                                            placeholder="Confirme a nova palavra passe">
                                        <p id="ihelpPass" name="helpPass" class="help-block"
                                            style="color:#555555; padding:5px"></p>
                                    </div>


                                    <div class="form-group div-margin-separa">
                                        <button class="btn btn-default btn-backoffice-size" name="btn_submit_form"
                                            onclick="changePassword()">Alterar</button>
                                        <button class="btn btn-default btn-backoffice-size" style="margin-left: 10px"
                                            onclick="clearFields()">Limpar</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
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
<script>
$(document).ready(function() {
    $('button[name="btn_submit_form"]').attr("disabled", "disabled");
});

//-- Clear the input text on button click
function clearFields() {
    $('#iInputPass1').val("");
    $('#iInputPass2').val("");
    $('#iInputPass3').val("");
}

var comfirma_pass2 = document.getElementById("iInputPass2");
var comfirma_pass3 = document.getElementById("iInputPass3");
comfirma_pass2.addEventListener("keyup", comparaPassword);
comfirma_pass3.addEventListener("keyup", comparaPassword);

function comparaPassword() {
    if ($('#iInputPass2').val().length >= 5) {
        //-- Compara se a nova pass é igual nos 2 campos
        if ($('#iInputPass2').val() === $('#iInputPass3').val()) {
            $('button[name="btn_submit_form"]').removeAttr("disabled");
            $("#ihelpPass").text("Palavra passe válida!");
            $("#ihelpPass").css({
                "color": "rgb(79, 216, 132)",
                "padding": "5px"
            });
        } else {
            $('button[name="btn_submit_form"]').attr("disabled", "disabled");
            $("#ihelpPass").text("As palavras passe não são iguais!");
            $("#ihelpPass").css({
                "color": "rgb(216, 79, 79)",
                "padding": "5px"
            });
        }
    }

    if ($('#iInputPass2').val().length >= 5 && $('#iInputPass3').val().length <= 5) {
        $("#ihelpPass").text("");
    }

}

//-- Ajax to submite change of the user email
function changePassword() {

    if ($('#iInputPass2').val().length > 5) {
        //-- Compara se a nova pass é igual nos 2 campos
        if ($('#iInputPass2').val() === $('#iInputPass3').val()) {
            passAntiga = $('#iInputPass1').val();
            passNova = $('#iInputPass2').val();
            passNova2 = $('#iInputPass3').val();
            //alert("Pass 2 e 3 são iguais");

            $.ajax({
                type: "POST",
                url: 'changeuserdata.php',
                data: {
                    'action': 'change_password',
                    'password_old': passAntiga,
                    'password_new': passNova,
                    'password_new_2': passNova2
                },
                success: function(html) {
                    alert(html);
                    location.reload();
                }

            });
        }
    }
}
</script>

</html>