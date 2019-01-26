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
        $_SESSION['nome'] = $nome;
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

    <title>Gerir Administradores</title>

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

            <ul class="nav navbar-top-links navbar-right" style="padding-left:10px">
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
                    <?php include "sidemenu.php"; ?>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" style="min-height:500px">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Gerir Administradores</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">

                </div>

                <!-- /.row -->
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

    //var inputNome = document.getElementById("iInputNome");
    //var btnSubmeterNome = document.getElementById("iBtnSubmeterNome");
    //var novoNome = inputNome.value;
    /*
    btnSubmeterNome.onclick = function () {
        novoNome = $('#iInputNome').val();
        //$('#iInputNome').val("Fodasse");
        console.log("Novo nome: ", novoNome);
        
        $.ajax({
            type: "POST",
            url: 'changeusername.php',
            data:{'action':'change_name', 'name': novoNome},
            success:function(html) {
                alert(html);
            }

        });
    }
    */


    //-- Ajax to submite change of the user name
    function changeNome() {
        novoNome = $('#iInputNome').val();
        console.log("Novo nome: " + novoNome);

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

    //-- Ajax to submite change of the user email
    function changeEmail() {
        novoEmail = $('#iInputEmail').val();
        console.log("Novo Email: " + novoEmail);

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
    </script>

</body>

</html>