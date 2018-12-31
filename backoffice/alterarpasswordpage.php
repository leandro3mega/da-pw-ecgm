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

    <title>Alterar Password</title>

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
                            <a href="trabalhospage.php"><i class="fa fa-th-list fa-fw"></i> Todos Trabalhos</a>
                        </li>

                        <li>
                            <a href="novotrabalhopage.php"><i class="fa fa-file-o fa-fw"></i> Novo Trabalho</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-gear fa-fw"></i> Editar Conta<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="alterarpasswordpage.php"><i class="fa fa-key fa-fw"></i> Alterar Palavra
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
                        <h1 class="page-header">Alterar Password</h1>

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
                                        <input type="password" id="iInputPass1" class="form-control" placeholder="Insira a sua palavra passe">
                                    </div>

                                    <div class="form-group div-margin-separa">
                                        <label>Nova Palavra Passe</label>
                                        <input type="password" id="iInputPass2" class="form-control" placeholder="Insira a nova palavra passe">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirmar a Nova Palavra Passe</label>
                                        <input type="password" id="iInputPass3" class="form-control" placeholder="Confirme a nova palavra passe">
                                    </div>

                                    <div class="form-group div-margin-separa">
                                        <button class="btn btn-default btn-backoffice-size" onclick="changePassword()">Alterar</button>
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
    //-- Clear the input text on button click
    function clearFields() {
        var inputPass1 = document.getElementById("iInputPass1");
        var inputPass2 = document.getElementById("iInputPass2");
        var inputPass3 = document.getElementById("iInputPass3");

        inputPass1.value = "";
        inputPass2.value = "";
        inputPass3.value = "";
    }

    //-- Ajax to submite change of the user email
    function changePassword() {
        
        //-- Compara se a nova pass é igual nos 2 campos
        if($('#iInputPass2').val() === $('#iInputPass3').val()){
            passAntiga = $('#iInputPass1').val();
            passNova = $('#iInputPass2').val();
            //alert("Pass 2 e 3 são iguais");

            $.ajax({
                type: "POST",
                url: 'changeuserdata.php',
                data: {
                    'action': 'change_password',
                    'password_old': passAntiga,
                    'password_new': passNova
                },
                success: function (html) {
                    alert(html);
                    location.reload();
                }
    
            });
        } else{
            alert("Confirme que a nova password que pretende inserir é igual à que está a confirmar.");
        }
        /*
        */
    }
</script>

</html>