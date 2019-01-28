<?php

session_start();

require_once("connectdb.php");

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
    header("location:index.php");
    session_write_close();

    exit();
}

// se não existir continua no codido

?>

<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Criar Nova Conta</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor2/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor2/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- estilos desenvolvidos -->
    <link rel="stylesheet" href="css/stylesheet.css" </head> <body>

    <div class="container">
        <div class="card card-register mx-auto mt-5">
            <div class="card-header">Criar Nova Conta</div>
            <div class="card-body" style="padding-top:0.5rem">
                <form id="formregister" action="register.php" method="post">

                    <!-- Nome -->
                    <div class="form-group">
                        <label class="label-bold" style="margin-top:0">Dados</label>
                        <div class="form-label-group">
                            <input type="text" name="nome" id="inome" class="form-control" placeholder="Nome Completo"
                                minlength="10" maxlength="100" required="required">
                            <label for="inome"> Nome Completo</label>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" name="email" id="iemail" class="form-control" placeholder="Email"
                                minlength="5" maxlength="40" required="required">
                            <label for="iemail">Email</label>
                        </div>
                        <p id="ihelpEmail" name="helpEmail" class="help-block" style="color:#555555; padding:5px">
                            Exemplo: user@ipvc.pt</p>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" name="username" id="iusername" class="form-control" minlength="5"
                                maxlength="20" placeholder="Nome de Utilizador" required="required">
                            <label for="iusername">Nome de Utilizador</label>
                        </div>
                        <p id="ihelpUsername" name="helpUsername" class="help-block" style="color:#555555; padding:5px">
                            Insira um
                            nome de utilizador
                            com pelo menos 5 carateres.</p>
                    </div>

                    <!-- Passwords -->
                    <div class="form-group">
                        <div class="form-row">

                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" name="password" id="ipassword" class="form-control"
                                        minlength="5" maxlength="30" placeholder="Palavra Passe" required="required">
                                    <label for="ipassword">Palavra Passe</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-label-group">
                                    <input type="password" name="confirmPassword" id="iconfirmPassword" minlength="5"
                                        maxlength="30" class="form-control" placeholder="Confirme a Palavra Passe"
                                        required="required">
                                    <label for="iconfirmPassword">Confirme a Palavra Passe</label>
                                </div>
                            </div>
                            <p id="ihelpPass" name="helpPass" class="help-block" style="color:#555555; padding:5px"></p>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">

                            <!-- Opções -> Professor/Aluno -->
                            <div class="col-md-6" style="max-width: 40%;">
                                <label class="label-bold">Eu sou...</label>
                                <div style="display: block;">
                                    <label class="radio-inline">
                                        <input type="radio" name="profissao" id="profissao1" value="1" checked>Aluno
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="profissao" id="profissao2" value="2">Professor
                                    </label>
                                </div>
                            </div>

                            <!-- Carregar fotografia -->
                            <!--
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="label-bold">Fotografia</label>
                      <input type="file" name="fotografia" id="avatar_file_upload_field" required accept="image/jpeg,image/png"/>
                  </div>
                </div>
                -->
                        </div>
                    </div>

                    <input type="submit" id="iSubmitForm" class="btn btn-lg btn-success btn-block" value="Submeter">

                </form>
                <div class="text-center">
                    <a class="d-block small mt-3" href="iniciar-sessao.php">Iniciar Sessão</a>
                    <!-- <a class="d-block small" href="">Esqueceu-se da Palavra Passe?</a> -->
                    <!--
            -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor2/jquery/jquery.min.js"></script>
    <script src="vendor2/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor2/jquery-easing/jquery.easing.min.js"></script>

    </body>

    <script>
    var input_username = document.getElementById("iusername");
    var input_email = document.getElementById("iemail");
    // var input_username = $("#iusername").text($(this).val().length;
    // var input_username = $('input[name="username"]').val();

    $(document).ready(function() {
        buscaUtilizadores();
        buscaEmails();
        comparaPassword();
        verificaNome();

        $("input[type=submit]").attr("disabled", "disabled");
    });

    input_username.addEventListener("keyup", buscaUtilizadores);
    input_email.addEventListener("keyup", buscaEmails);

    function buscaUtilizadores() {
        // console.log("Input: " + input_username.value);

        if ($('input[name="username"]').val().length >= 5) {
            $.ajax({
                type: "POST",
                url: 'fetch_ucs_users.php',
                data: {
                    'action': 'fetch_users',
                    'input_utilizador': input_username.value
                },
                success: function(response) {
                    if (response == "True") {
                        // console.log("Encontrou");
                        // $('p[name="helpUsername"]').val("Nome de utilizador inválido");
                        $("input[type=submit]").attr("disabled", "disabled");
                        $("#ihelpUsername").text("Nome de utilizador já existe");
                        $("#ihelpUsername").css({
                            "color": "rgb(216, 79, 79)",
                            "padding": "5px"
                        });


                    } else if (response == "False") {
                        // console.log("Não Encontrou");
                        // $("input[type=submit]").removeAttr("disabled");
                        $("#ihelpUsername").text("Nome de utilizador válido");
                        $("#ihelpUsername").css({
                            "color": "rgb(79, 216, 132)",
                            "padding": "5px"
                        });

                    }

                }
            });
        } else {
            // $("#iSubmitForm").attr("disabled", "disabled");
            $("input[type=submit]").attr("disabled", "disabled");
            $("#ihelpUsername").text("Insira um nome de utilizador com pelo menos 5 carateres.");
            $("#ihelpUsername").css({
                "color": "#555555",
                "padding": "5px"
            });

        }
    }

    function buscaEmails() {
        // console.log("Input: " + input_email.value);
        var email = $('input[name="email"]').val()

        if (validateEmail(email)) {

            $.ajax({
                type: "POST",
                url: 'fetch_ucs_users.php',
                data: {
                    'action': 'fetch_emails',
                    'input_email': input_email.value
                },
                success: function(response) {
                    console.log(response);

                    if (response == "True") {
                        // console.log("Encontrou");
                        $("input[type=submit]").attr("disabled", "disabled");
                        $("#ihelpEmail").text("O email já está atribuido a um utilizador");
                        $("#ihelpEmail").css({
                            "color": "rgb(216, 79, 79)",
                            "padding": "5px"
                        });

                    } else if (response == "False") {
                        // console.log("Não Encontrou");
                        // $("input[type=submit]").removeAttr("disabled");
                        $("#ihelpEmail").text("Email válido");
                        $("#ihelpEmail").css({
                            "color": "rgb(79, 216, 132)",
                            "padding": "5px"
                        });
                    }
                }
            });
        } else {
            // $("#iSubmitForm").attr("disabled", "disabled");
            $("input[type=submit]").attr("disabled", "disabled");
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

    var comfirma_pass = document.getElementById("iconfirmPassword");
    comfirma_pass.addEventListener("keyup", comparaPassword);

    //-- Verifica se as palavras pass são iguais
    function comparaPassword() {
        // console.log($('input[name="password"]').val());
        // console.log($('input[name="confirmPassword"]').val());

        if ($('input[name="password"]').val().length >= 5) {
            if ($('input[name="password"]').val() === $('input[name="confirmPassword"]').val()) {
                // console.log("As passwords são iguais!");

                $("input[type=submit]").removeAttr("disabled");
                $("#ihelpPass").text("Palavra passe válida!");
                $("#ihelpPass").css({
                    "color": "rgb(79, 216, 132)",
                    "padding": "5px"
                });

            } else {
                // console.log("As passwords não são iguais!");

                $("input[type=submit]").attr("disabled", "disabled");
                $("#ihelpPass").text("As palavras passe não são iguais!");
                $("#ihelpPass").css({
                    "color": "rgb(216, 79, 79)",
                    "padding": "5px"
                });
            }
        }



    }

    var nome_utilizador = document.getElementById("inome");
    nome_utilizador.addEventListener("keyup", verificaNome);

    //-- Verifica se as palavras pass são iguais
    function verificaNome() {
        // console.log($('input[name="nome"]').val());
        // console.log($('input[name="confirmPassword"]').val());

        if ($('input[name="nome"]').val().length > 10) {
            // $("input[type=submit]").removeAttr("disabled");

        } else {
            $("input[type=submit]").attr("disabled", "disabled");
        }
    }
    </script>

</html>