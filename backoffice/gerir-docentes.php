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

    <title>Gerir Unidades Curriculares</title>

    <!-- Browser image -->
    <link rel="icon" href="images/website/logotipo_transparente.png">

    <script src="js/jquery-3.1.1.js"></script>

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
                        <h1 class="page-header">Docentes</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <!-- Docentes -->
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover"
                                    id="dataTables-example">
                                    <?php
                                    $imageURL = "images/utilizadores/";

                                    echo "
                                    <thead>
                                        <tr>
                                            <th style='word-wrap: break-word; max-width:20%; width:20%'>Fotografia</th>
                                            <th style='width:35%; max-width:35%'>Nome</th>
                                            <th style='width:35%; max-width:35%'>Email</th>
                                            <th style='width:10%; max-width:10%'></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    ";

                                    if ($stmt = $connectDB->prepare("SELECT fk_idutilizador, nome, email, fotografia FROM docente ORDER BY nome ASC")) {
                                        
                                        // Attempt to execute the prepared statement
                                        if ($stmt->execute()) {
                                            $result = $stmt->get_result();
                                            if ($result->num_rows !== 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $docente_id = $row['fk_idutilizador'];
                                                    $docente_nome = $row['nome'];     //-- NOME
                                                    $docente_email = $row['email'];   //-- EMAIL
                                                    $docente_fotografia = $row['fotografia'];   //-- ID da FOTOGRAFIA

                                                    echo "
                                                    <tr class='odd gradeX'>
                                                        <td style='vertical-align: middle'>
                                                            <div style='height:100px; display: flex; justify-content: center;'>
                                                                <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                    <img class='img-fluid img-thumbnail' src='" . $imageURL . $docente_fotografia . "' alt='' style='width:auto; max-height:100px'>
                                                                </div>
                                                            </div>    
                                                        </td>
                                                        <td style='vertical-align: middle'>" . $docente_nome. "</td>
                                                        <td style='vertical-align: middle'>" . $docente_email . "</td>
                                                        <td style='vertical-align: middle'>
                                                            <div class='form-inline'>
                                                                <div style ='margin-left:auto; margin-right:auto; margin-top:5px; display: flex;'>
                                                                    <input type='hidden' name='docente_nome_". $docente_id ."' value='". $docente_nome ."'>
                                                                    <button class='icon-meus-projetos' id='" . $docente_id . "' onclick='deleteDocente(this.id)' style='background: Transparent no-repeat; border: none; width:60%; margin-right:auto; margin-left: auto;'>
                                                                        <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); font-size: 120%; padding:10px; width: auto;'></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    ";
                                                }
                                            }
                                        }
                                    }
                                    $stmt->close();

                                    ?>
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>

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

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });

    $(document).ready(function() {
        // $('body').find("input[type=search], input[type=text], search").each(function(ev) {
        $('body').find("input[type=search], search").each(function(ev) {
            if (!$(this).val()) {
                $(this).attr("placeholder", "Pesquisar...");
            }
        });
    });

    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })
    // popover demo
    $("[data-toggle=popover]")
        .popover()

    //-- Ajax to submite delete of student
    function deleteDocente(id_docente) {
        var nome_docente = $("input[name=docente_nome_" + id_docente + "]").val();

        console.log("id uc: " + id_docente);
        console.log("nome uc: " + nome_docente);

        if (confirm('Tem a certeza que pretende remover o docente: ' + nome_docente + '?')) {
            $.ajax({
                type: "POST",
                url: 'manage_database.php',
                data: {
                    'action': 'delete_docente',
                    'id_docente': id_docente
                },
                success: function(html) {
                    // alert(html);
                    location.reload();
                }
            });
        }
    }
    </script>

</body>

</html>