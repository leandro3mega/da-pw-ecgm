<?php

session_start();

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
    <link rel="stylesheet" href="css/stylesheet.css"

  </head>

  <body>

    <div class="container">
      <div class="card card-register mx-auto mt-5">
        <div class="card-header">Criar Nova Conta</div>
        <div class="card-body" style="padding-top:0.5rem">
          <form id="formregister" action="register.php" method="post">
            <div class="form-group">
              <label class="label-bold" style="margin-top:0">Dados</label>
              <div class="form-label-group">
                <input type="text" name="nome" id="inome" class="form-control" placeholder="Nome Completo" required="required">
                <label for="inome"> Nome Completo</label>
              </div>
            </div>

            <div class="form-group">
              <div class="form-label-group">
                <input type="email" name="email" id="iemail" class="form-control" placeholder="Email address" required="required">
                <label for="iemail">Email address</label>
              </div>
            </div>

            <div class="form-group">
              <div class="form-label-group">
                <input type="text" name="username" id="iusername" class="form-control" placeholder="Nome de Utilizador" required="required">
                <label for="iusername">Nome de Utilizador</label>
              </div>
            </div>

            <div class="form-group">
              <div class="form-row">

                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="password" name="password" id="ipassword" class="form-control" placeholder="Palavra Passe" required="required">
                    <label for="ipassword">Palavra Passe</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-label-group">
                    <input type="password" name="confirmPassword" id="iconfirmPassword" class="form-control" placeholder="Confirme a Palavra Passe" required="required">
                    <label for="iconfirmPassword">Confirme a Palavra Passe</label>
                  </div>
                </div>

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
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="label-bold">Fotografia</label>
                      <input type="file">
                  </div>
                </div>

              </div>
            </div>
                
            <input type="submit" class="btn btn-lg btn-success btn-block" value="Submeter">

          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="loginpage.php">Iniciar Sessão</a>
            <a class="d-block small" href="">Esqueceu-se da Palavra Passe?</a>
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

</html>
