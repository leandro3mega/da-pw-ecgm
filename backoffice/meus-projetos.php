<?php
require_once("connectdb.php");

session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
} else {
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $nome;
    $email;
    $tipo = $_SESSION['tipo'];
    $cargo;

    //-- Converte int em string para mostrar o cargo do user no menu superior
    if ($tipo == 0) $cargo = "Administrador";
    else if ($tipo == 1) $cargo = "Aluno";
    else $cargo = "Professor";
    
    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    $result = mysqli_query($connectDB, "select * from view_useralunosdocentes where idutilizador=$id");
    if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $nome = ($row['nome']);
        $email = ($row['email']);
    }

}

// Close connection
//$connectDB->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Meus Projetos</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

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
                
                <li> <?php echo $cargo; ?> </li>
                <li><a><i class="fa fa-user fa-fw"></i> <?php echo $username; ?> </a>
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
                            <a href="meus-projetos.php"><i class="fa fa-th-list fa-fw"></i> Meus Projetos</a>
                        </li>

                        <li>
                            <a href="novo-projeto.php"><i class="fa fa-file-o fa-fw"></i> Novo Projeto</a>
                        </li>
                        
                        <li>
                            <a href="#"><i class="fa fa-gear fa-fw"></i> Editar Conta<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="alterar-password.php"><i class="fa fa-key fa-fw"></i> Alterar Palavra Passe</a>
                                </li>
                                <li>
                                    <a href="dados-pessoais.php"><i class="fa fa-edit fa-fw"></i> Alterar Dados Pessoais</a>
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
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Os Meus Projetos</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                DataTables Advanced Tables
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Imagem</th>
                                            <th>Titulo</th>
                                            <th>Ano Letivo</th>
                                            <th>Semestre</th>
                                            <th>Unidade Curricular</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        //require_once("connectdb.php");

                                        $sql = mysqli_query($connectDB, "SELECT idprojeto, titulo, descricao, data, ano, semestre, tipo FROM projeto");

                                        //$i = 0;

                                        while ($row = $sql->fetch_assoc()) {
                                            //$i++;
                                                    //  echo " < option value = '$i' > " . $row['nome'] . " < / option > ";
                                                    //$idPadrinho=$row['PK_idPadrinho'];
                                                    //echo $row['id_categoria'];

                                            echo
                                                "<tr class='odd gradeX'>" .
                                                "<td><img class=' img-fluid img-thumbnail' src='http://placehold.it/400x300' alt=''></td>" .
                                                "<td>" . $row['titulo'] . "</td>" .
                                                "<td>" . $row['descricao'] . "</td>" .
                                                "<td>" . $row['data'] . "</td>" .
                                                "<td>" . $row['ano'] . "</td>" .
                                                "</tr>";
                                        }
                                        //-------------
                                        /*
                                        $sql = "select * from projeto";

                                        $result = $connectDB->query($sql);
    
                                        // check for errors
                                        if ($connectDB->errno) {
                                            exit("query error");
                                        } else {
                                            $result->data_seek(0);
                                            while ($row = $result->fetch_object()) {

                                                echo
                                                    "
                                                <tr class='odd gradeX'>
                                                    <td>" . $row['titulo'] . "</td>
                                                    <td>" . $row['descricao'] . "</td>
                                                    <td>" . $row['data'] . "</td>
                                                    <td>" . $row['ano'] . "</td>
                                                    <td>" . $row['semestre'] . "</td>
                                                </tr>
                                                ";
                                            }
                                        }
                                         */
                                        // Close connection
                                        //$connectDB->close();
                                        ?>
                                        <tr class="odd gradeX">
                                            <td>Trident</td>
                                            <td>Internet Explorer 4.0</td>
                                            <td>Win 95+</td>
                                            <td class="center">4</td>
                                            <td class="center">X</td>
                                        </tr>
                                        <tr class="even gradeC">
                                                <td>Trident</td>
                                                <td>Internet Explorer 5.0</td>
                                                <td>Win 95+</td>
                                                <td class="center">5</td>
                                                <td class="center">C</td>
                                        </tr>
                                        <tr class="odd gradeA">
                                                <td>Trident</td>
                                                <td>Internet Explorer 5.5</td>
                                                <td>Win 95+</td>
                                                <td class="center">5.5</td>
                                                <td class="center">A</td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>Misc</td>
                                            <td>Dillo 0.8</td>
                                            <td>Embedded devices</td>
                                            <td class="center">-</td>
                                            <td class="center">X</td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>Misc</td>
                                            <td>Links</td>
                                            <td>Text only</td>
                                            <td class="center">-</td>
                                            <td class="center">X</td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>Misc</td>
                                            <td>Lynx</td>
                                            <td>Text only</td>
                                            <td class="center">-</td>
                                            <td class="center">X</td>
                                        </tr>
                                        <tr class="gradeC">
                                            <td>Misc</td>
                                            <td>IE Mobile</td>
                                            <td>Windows Mobile 6</td>
                                            <td class="center">-</td>
                                            <td class="center">C</td>
                                        </tr>
                                        <tr class="gradeC">
                                            <td>Misc</td>
                                            <td>PSP browser</td>
                                            <td>PSP</td>
                                            <td class="center">-</td>
                                            <td class="center">C</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            </div>
            <!-- /.container-fluid -->

            
            <footer style="width: auto;">
            <div style="margin-top: auto!important; margin-bottom: auto!important;">
                <div class="text-center" style="padding-top: 15px; padding-bottom: 30px; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; margin-top: auto!important; line-height: 1; font-size: 1.2rem;">
                <span>Copyright © <a target="_blank" href="http://www.linkedin.com/in/leandro3mega">Leandro Magalhães</a> 2019</span>
                </div>
            </div>
            </footer>
            
            
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

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });

        //-- Year for the copyright label
        //var d = new Date()
        //document.write(d.getFullYear())
        

    </script>

</body>

</html>
