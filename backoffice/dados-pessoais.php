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

    <title>Alterar Dados Pessoais</title>

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
                        <h1 class="page-header">Alterar Dados Pessoais</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="row">
                                <div class="col-lg-6">
                                    
                                    <!--Nome-->
                                    <div class="form-group" id="iDivLabelNome" style="display:block">
                                        <label>Nome</label>
                                        <p class="form-control-static"><?php echo ($nome); ?></p>
                                    </div>
                                        
                                    <div class="form-group" id="iDivInputNome" style="display:none">
                                                <label>Nome</label>
                                                <input type="text" id="iInputNome" class="form-control" placeholder="Insira o seu nome" value="<?php echo $nome; ?>">
                                                <button id="iBtnSubmeterNome" class="btn btn-default btn-backoffice-size" onclick="changeNome()">
                                                        Submeter
                                                    </button>
                                                <button id="iBtnCancelarNome" onclick="showhideNome()" class="btn btn-default btn-backoffice-size">
                                                            Cancelar
                                                </button>
                                                <div class="form-group"></div>
                                    </div>
                                    <button id="iBtnAlterarNome" onclick="showhideNome()" class="btn btn-default btn-backoffice-size">
                                        Alterar
                                    </button>

                                    <!--Email-->
                                    <div class="form-group div-margin-separa" id="iDivLabelEmail" style="display:block">
                                        <label>Email</label>
                                        <p class="form-control-static"><?php echo $email; ?></p>
                                    </div>

                                    <div class="form-group div-margin-separa" id="iDivInputEmail" style="display:none">
                                        <label>Email</label>
                                        <input type="email" name="email" id="iInputEmail" class="form-control" placeholder="Insira novo e-mail" value="<?php echo $email; ?>">
                                        <button id="iBtnSubmeterEmail" class="btn btn-default btn-backoffice-size" style="margin-top:15px" onclick="changeEmail()">
                                                        Submeter
                                                    </button>
                                                <button id="iBtnCancelarEmail" class="btn btn-default btn-backoffice-size" style="margin-top:15px" onclick="showhideEmail()">
                                                            Cancelar
                                                </button>
                                    </div>
                                    <button id="iBtnAlterarEmail" onclick="showhideEmail()" class="btn btn-default btn-backoffice-size">
                                        Alterar
                                    </button>


                                    <!--
                                    <div class="form-group div-margin-separa">
                                        <label>Email</label>
                                        <p class="form-control-static">email@example.com</p>
                                    </div>

                                    <div class="form-group">
                                        <label>Novo E-mail</label>
                                        <input type="text" class="form-control" placeholder="Insira novo e-mail">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirme Novo E-mail</label>
                                        <input class="form-control" placeholder="Confirme o novo e-mail">
                                    </div>
                                    -->

                                    <!--
                                    <div class="form-group div-margin-separa">
                                        <button type="submit" class="btn btn-default btn-backoffice-size">Alterar</button>
                                        <button type="reset" class="btn btn-default btn-backoffice-size" style="margin-left: 10px">Limpar</button>
                                    </div>
                                -->
                                    <!--</form>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.row -->
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
        
        var conta = 0;
        
        function showhideNome() {
            var divLabelNome = document.getElementById("iDivLabelNome");
            var divInputNome = document.getElementById("iDivInputNome");
            var btnAlterarNome = document.getElementById("iBtnAlterarNome");
            var btnSubmeterNome = document.getElementById("iBtnSubmeterNome");
            var btnCancelarNome = document.getElementById("iBtnCancelarNome");
            
            //div.classList.toggle('hidden');
            conta += 1;
            console.log(conta);
            
            if (conta % 2 == 0) {
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
            
            //div.classList.toggle('hidden');
            conta += 1;
            console.log(conta);
            
            if (conta % 2 == 0) {
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
                data:{'action':'change_name', 'name': novoNome},
                success:function(html) {
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
                data:{'action':'change_email', 'email': novoEmail},
                success:function(html) {
                    alert(html);
                    location.reload();
                }

            });
        }
        
    </script>

</body>

</html>