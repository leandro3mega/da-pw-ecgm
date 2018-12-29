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
<!--

<body>
    <div>
        <form id="formlogin" action="login.php" method="post">
            <input type="text" name="username" id="iusername" placeholder="Username...">
            <br>
            <input type="text" name="password" id="ipassword" placeholder="Password...">
            <br>
            
            <input type="submit" value="Iniciar Sessão">
        </form>
    </div>
</body>

-->
<!DOCTYPE html>
<html lang="pt">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Iniciar Sessão</title>

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
      <div class="card card-login mx-auto mt-5">
        <div class="card-header">Iniciar Sessão</div>
        <div class="card-body">
          <form id="formlogin" action="login.php" method="post">
            <div class="form-group">
              <div class="form-label-group">
                <input  type="text" id="iusername" class="form-control" name="username" placeholder="Nome de utilizador" required="required" autofocus="autofocus">
                <label for="iusername">Nome de utilizador</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" id="ipassword" class="form-control" name="password" placeholder="Palavra Passe" required="required">
                <label for="ipassword">Palavra Passe</label>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="remember-me"> Lembrar Palavra Passe</label>
              </div>
            </div>
            <input type="submit" class="btn btn-lg btn-success btn-block" value="Iniciar Sessão">
            <!--
            <a class="btn btn-primary btn-block" href="index.html">Login</a>
            -->
          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="registerpage.php">Registar</a>
            <a class="d-block small" href="">Esqueceu-se da palavra passe?</a>
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
