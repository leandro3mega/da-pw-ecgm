<?php

session_start();

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
    header("location:../frontoffice/index.php");
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

    <!-- Browser image -->
    <link rel="icon" href="images/website/logotipo_transparente.png">

    <!-- Bootstrap core CSS-->
    <link href="vendor2/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor2/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- estilos desenvolvidos -->
    <link rel="stylesheet" href="css/stylesheet.css">
</head>

<style>
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: center;
    margin-bottom: 20px
}

.btn-success-v2 {
    color: #333;
    background-color: #f5f5f5;
    border-color: #c8c8c8;
}

.btn-success-v2:hover {
    background-color: #c8c8c8;
}
</style>

<body>

    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Iniciar Sessão</div>
            <div class="card-body">
                <form id="formlogin" action="login.php" method="post">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="iusername" class="form-control" name="username"
                                placeholder="Nome de utilizador" minlength="5" maxlength="20" required="required"
                                autofocus="autofocus">
                            <label for="iusername">Nome de utilizador</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="ipassword" class="form-control" name="password"
                                placeholder="Palavra Passe" minlength="5" maxlength="30" required="required">
                            <label for="ipassword">Palavra Passe</label>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="remember-me"> Lembrar Palavra Passe</label>
                        </div>
                    </div> -->
                    <input type="submit" class="btn btn-lg btn-success-v2 btn-block" value="Iniciar Sessão">
                    <!--
            <a class="btn btn-primary btn-block" href="index.html">Login</a>
            -->
                </form>
                <div class="text-center">
                    <a class="d-block small mt-3" href="registar-utilizador.php">Registar</a>
                    <!-- <a class="d-block small" href="">Esqueceu-se da palavra passe?</a> -->
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer">
            <a target="_blank" rel="noopener noreferrer" href="http://cursos.estg.ipvc.pt/DA">
                <img src="images/website/logotipo_v2_transparente.png" style="max-height:50px;" alt="">
            </a>
            <a target="_blank" rel="noopener noreferrer" href="http://portal.ipvc.pt/portal/page/portal/estg">
                <img src="images/website/logo_estg.png" style="max-height:50px; margin: 0px 30px 0px 30px;" alt="">
            </a>
            <a target="_blank" rel="noopener noreferrer" href="http://www.ipvc.pt/">
                <img src="images/website/logo_ipvc.png" style="max-height:50px" alt="">
            </a>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor2/jquery/jquery.min.js"></script>
    <script src="vendor2/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor2/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>