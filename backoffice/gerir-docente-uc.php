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

    // $data_atual = getdate();
    // echo date('Y-m-d');
    $ano_atual = date('Y');
    $mes_atual = date('m');
    // $mes_atual = "09";
    $ano_letivo = $ano_letivo_format = "";

    // echo($ano_atual);
    // echo("</br>" . $mes_atual);

    if ($mes_atual <= "08") {
        $ano_letivo_format = ($ano_atual -1) . "/" . $ano_atual;
        $ano_letivo = ($ano_atual -1) . $ano_atual;
    // echo "</br>é menor";
    } else {
        $ano_letivo_format = $ano_atual . "/" . ($ano_atual + 1);
        $ano_letivo = $ano_atual . ($ano_atual + 1);
        // echo "</br>é maior";
    }
    // echo "</br>Ano Letivo Format: " . $ano_letivo_format;
    // echo "</br>Ano Letivo: " . $ano_letivo;
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

    <title>Docente - UC</title>

    <!-- Icon image -->
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
                <a class="navbar-brand" href="index.php">Area de Utilizador</a>
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
                        <h1 class="page-header" style="margin-bottom:0px !important;">Unidade Curricular - Docente</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h3 style="margin-bottom:15px !important;">
                            <?php echo("Ano Letivo: " . $ano_letivo_format); ?>
                        </h3>
                        <input type="hidden" name="conn_ano_letivo" value="<?php echo($ano_letivo); ?>">
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <!--  -->
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover"
                                    id="dataTables-example">
                                    <?php

                                    echo "
                                    <thead>
                                        <tr>
                                            <th style='max-width:40%; width:40%; word-wrap: break-word; text-align: center;'>Unidade Curricular</th>
                                            <th style='max-width:60%; width:60%; word-wrap: break-word; text-align: center;'>Docente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    ";

                                    if ($stmt = $connectDB->prepare("SELECT idunidade_curricular, nome, ano_curricular, semestre, descricao FROM unidade_curricular ORDER BY ano_curricular ASC, semestre ASC, nome ASC")) {
                                        
                                        // Attempt to execute the prepared statement
                                        if ($stmt->execute()) {
                                            $result = $stmt->get_result();
                                            if ($result->num_rows !== 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $uc_id = $row['idunidade_curricular'];
                                                    $uc_nome = $row['nome'];     //-- NOME
                                                    $uc_descricao = $row['descricao'];   //-- ANO
                                                    if ($row['ano_curricular'] == 1) {
                                                        $uc_ano = "1º Ano";
                                                    } elseif ($row['ano_curricular'] == 2) {
                                                        $uc_ano = "2º Ano";
                                                    } elseif ($row['ano_curricular'] == 3) {
                                                        $uc_ano = "3º Ano";
                                                    }
                                                    if ($row['semestre'] == 1) {
                                                        $uc_semestre = "1º Semestre";
                                                    } elseif ($row['semestre'] == 2) {
                                                        $uc_semestre = "2º Semestre";
                                                    }

                                                    //-- Vai buscar ID do o docente a ligação docente_uc
                                                    $id_docente = $nome_docente = $fotografia_docente = $imageURL = "";
                                                    $imageURL = "images/utilizadores/";
                                                    $id_docente = getDocenteIDFromLigacao($connectDB, $uc_id, $ano_letivo);
                                                    
                                                    if (!empty($id_docente)) {
                                                        //-- Vai buscar nome e fotografia do docente
                                                        getDocenteData($connectDB, $id_docente, $nome_docente, $fotografia_docente);
                                                    }
                                                    
                                                    echo "
                                                    <tr class='odd gradeX'>
                                                    <td style='vertical-align: middle'>" . $uc_nome. "</td>
                                                    ";
                                                    
                                                    if (empty($id_docente)) {
                                                        echo "
                                                        <td style='vertical-align: middle'>
                                                            <div class='form-inline'>
                                                                <div style ='margin-left:auto; margin-right:auto; margin-top:5px; display: flex;'>
                                                                    <button id='" . $uc_id . "' class='btn btn-default btn-backoffice-size' onclick='setIdUc(this.id)' data-toggle='modal' data-target='#modalSelecionarDocente' style='margin-left:auto; margin-right:auto'>Atribuir Docente</button>
                                                                </div>
                                                            </div>
                                                        </td>";
                                                    } else {
                                                        echo "
                                                        <td style='vertical-align: middle'>
                                                            <div style='display:flex'>
                                                                <div style='width:20%; height:70px; padding:5px; border-right: 1px solid rgba(46, 207, 207, 0.322);'>
                                                                    <div style=' display:flex; justify-content: center;'>
                                                                        <div style='display: flex; flex-direction: column; justify-content: center;'>
                                                                            <img class='img-fluid img-thumbnail' src='" . $imageURL . $fotografia_docente . "' alt='' style='width:auto; max-height:70px;'>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div style='width:70%; height:70px; overflow: hidden; padding:10px; display:flex; align-items: center; border-right: 1px solid rgba(46, 207, 207, 0.322);'>
                                                                    <p style='margin:0px'>" . $nome_docente . "</p>
                                                                </div>
                                                                <div style='width:10%; height:70px; overflow: hidden; display:flex; align-items: center;'>
                                                                    <div style ='margin-left:auto; margin-right:auto; margin-top:5px; display: flex;'>
                                                                        <input type='hidden' name='row_uc_id_". $uc_id ."' value='". $id_docente ."'>
                                                                        <input type='hidden' name='row_uc_nome_". $uc_id ."' value='". $uc_nome ."'>
                                                                        <input type='hidden' name='row_docente_nome_". $uc_id ."' value='". $nome_docente ."'>
                                                                        <button class='icon-meus-projetos' id='" . $uc_id . "' onclick='RemoveDocentUcConn(this.id)' style='background: Transparent no-repeat; border: none; width:100%; margin-right:auto; margin-left: auto;'>
                                                                            <i class='fa fa-trash-o fa-fw' style='color: rgb(179, 45, 45); font-size: 120%; padding:10px; width: auto;'></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>";
                                                    }
                                                        
                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                    }
                                    echo "</tbody>";
                                    
                                    $stmt->close();

                                    //-- docente_uc -> return id do docente associado à UC
                                    function getDocenteIDFromLigacao($connectDB, $uc_id, $ano_letivo)
                                    {
                                        $docente_id = "";

                                        // query à base de dados
                                        $sql = "SELECT fk_iddocente, ano_letivo FROM docente_uc WHERE fk_iduc=?";

                                        // inicializar prepared statement
                                        $stmt = $connectDB->prepare($sql);

                                        // md5 para desincriptar a password
                                        //$password = md5($pass_old);
                                        $stmt->bind_param("i", $param_iduc);

                                        $param_iduc = $uc_id;

                                        // executar
                                        $stmt->execute();

                                        // associar os parametros de output
                                        $stmt->bind_result($r_iddocente, $r_ano_letivo);

                                        // transfere o resultado da última query : obrigatorio para ter num_rows
                                        $stmt->store_result();

                                        // iterar / obter resultados
                                        $stmt->fetch();

                                        if ($stmt->num_rows == 1 && $r_ano_letivo == $ano_letivo) { // seleciona o resultado da base de dados
                                            $docente_id = $r_iddocente;
                                        // echo("</br>Encontrou Docente ligado à unidade curricular.");
                                        } else {
                                            // echo("</br>Não encontrou Docente ligado à unidade curricular.");
                                        }
                                        
                                        $stmt->close();
                                        return $docente_id;
                                    }
                                    
                                    //-- docente_uc -> return id do docente associado à UC
                                    function getDocenteData($connectDB, $id_docente, &$nome_docente, &$fotografia_docente)
                                    {
                                        // query à base de dados
                                        $sql = "SELECT nome, fotografia FROM docente WHERE fk_idutilizador=?";

                                        // inicializar prepared statement
                                        $stmt = $connectDB->prepare($sql);

                                        // md5 para desincriptar a password
                                        //$password = md5($pass_old);
                                        $stmt->bind_param("i", $param_iduser);

                                        $param_iduser = (int)$id_docente;

                                        // executar
                                        $stmt->execute();

                                        // associar os parametros de output
                                        $stmt->bind_result($r_nome_docente, $r_foto_docente);

                                        // transfere o resultado da última query : obrigatorio para ter num_rows
                                        $stmt->store_result();

                                        // iterar / obter resultados
                                        $stmt->fetch();

                                        if ($stmt->num_rows == 1) { // seleciona o resultado da base de dados
                                            $nome_docente = $r_nome_docente;
                                            $fotografia_docente = $r_foto_docente;
                                        // echo("</br>Encontrou Docente.");
                                        } else {
                                            echo("</br>Não encontrou Docente.");
                                        }
                                        
                                        $stmt->close();
                                    }

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

    <!-- Modal -> Selecionar Docente -->
    <div class="modal fade" id="modalSelecionarDocente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Selecione o docente</label>
                </div>
                <!-- <form role="form" action="manage_database.php" method="post" enctype="multipart/form-data"> -->
                <div class="modal-body">
                    <!-- Tabela com docentes -->
                    <div class=" form-group">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <table width="100%" class="table table-striped table-bordered table-hover"
                                    id="dataTables-example-2">
                                    <?php
                                    $imageURL = "images/utilizadores/";

                                    echo "
                                    <thead>
                                        <tr>
                                            <th style='width:50%; max-width:50%'>Nome</th>
                                            <th style='width:50%; max-width:50%'>Email</th>
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

                                                    echo "
                                                    <tr class='odd gradeX' id='" . $docente_id . "' onclick='setIdDocente(this.id)'>
                                                        <td style='vertical-align: middle'>" . $docente_nome. "</td>
                                                        <td style='vertical-align: middle'>" . $docente_email . "</td>
                                                    </tr>
                                                    ";
                                                }
                                            }
                                        }
                                    }
                                    $stmt->close();
                                    echo "</tbody>";
                                    ?>
                                </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>

                    <!-- atributos hidden para enviar no submit do form -->
                    <!-- <input type='hidden' value="add_connection_docente_uc" name='action'> -->
                    <!-- <input type='hidden' value="" name='add_conn_uc_id'> -->
                </div>
                <!-- </form> -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> Selecionar Docente -->

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
        $('#dataTables-example-2').DataTable({
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

    var temp_id_uc;
    var temp_id_docente;
    var ano_letivo = $("input[name=conn_ano_letivo]").val();

    function setIdUc(id_uc) {
        temp_id_uc = id_uc;
        console.log("ID UC: " + temp_id_uc);
    }

    // //-- Ajax to submite 
    // function parseUCValuesToModal(id_uc) {
    //     $("input[name=add_conn_uc_id]").val(id_uc);


    //     // var pesquisa = $("input[name=uc_nome_" + uc_id + "]").val();
    //     console.log("ID UC: " + id_uc);
    //     // console.log("Pesquisa: " + pesquisa);


    // }

    function setIdDocente(id_docente) {
        temp_id_docente = id_docente;

        console.log("ID Docente: " + temp_id_docente);
        setDocentToUc();
    }


    //-- Ajax to submite 
    function setDocentToUc() {
        console.log("ID UC: " + temp_id_uc);
        console.log("ID Docente: " + temp_id_docente);
        console.log("Ano Letivo: " + ano_letivo);

        $.ajax({
            type: "POST",
            url: 'manage_database.php',
            data: {
                'action': 'set_docente_to_uc',
                'set_id_uc': temp_id_uc,
                'set_id_docente': temp_id_docente,
                'set_ano_letivo': ano_letivo
            },
            success: function(html) {
                // alert(html);
                location.reload();
            }
        });
    }

    //-- Ajax to submite 
    function RemoveDocentUcConn(id_uc) {
        var id_docente = $("input[name=row_uc_id_" + id_uc + "]").val();
        var nome_uc = $("input[name=row_uc_nome_" + id_uc + "]").val();
        var nome_docente = $("input[name=row_docente_nome_" + id_uc + "]").val();
        console.log("ID Docente: " + id_docente);
        console.log("ID UC: " + id_uc);

        if (confirm('Tem a certeza que pretende remover o docente ' + nome_docente + ' da unidade curricular ' +
                nome_uc + '?')) {
            $.ajax({
                type: "POST",
                url: 'manage_database.php',
                data: {
                    'action': 'remove_docente_uc_conn',
                    'remove_id_uc': id_uc,
                    'remove_id_docente': id_docente,
                    'remove_ano_letivo': ano_letivo
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