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
    <link rel="stylesheet" href="css/stylesheet.css" type="text/css">

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "sidemenu.php"; ?>


        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" style="min-height:500px">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Unidades Curriculares</h1>
                    </div>
                </div>

                <div class="row" style="display: flex; flex-wrap: wrap; margin:0px;">
                    <div class="col-lg-12">
                        <div class="form-inline" style="margin-left:auto; margin-bottom:15px; display:flex">
                            <button class="btn btn-default btn-backoffice-size" data-toggle="modal"
                                data-target="#modalAddUC" style="margin-left:auto">
                                Nova Unidade Curricular
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <!-- Unidades Curriculares -->
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table width="100%" class="table table-striped table-bordered table-hover"
                                    id="dataTables-example">
                                    <?php

                                    echo "
                                    <thead>
                                        <tr>
                                            <th style='max-width:35%; width:35%; word-wrap: break-word;'>Nome</th>
                                            <th style='max-width:20%; width:20%; word-wrap: break-word;'>Ano Curricular</th>
                                            <th style='max-width:15%; width:15%; word-wrap: break-word;'>Semestre</th>
                                            <th style='max-width:20%; width:20%; word-wrap: break-word; overflow: hidden; whitespace: nowrap;'>Descricao</th>
                                            <th style='max-width:10%; width:10%'></th>
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

                                                    echo "
                                                    <tr class='odd gradeX'>
                                                        <td style='vertical-align: middle'>" . $uc_nome. "</td>
                                                        <td style='vertical-align: middle'>" . $uc_ano . "</td>
                                                        <td style='vertical-align: middle'>" . $uc_semestre . "</td>
                                                        <td style='vertical-align: middle; word-wrap: break-word; overflow: hidden; whitespace: nowrap;'>" . $uc_descricao. "</td>
                                                        <td style='vertical-align: middle'>
                                                            <div class='form-inline'>
                                                                <div style ='margin-left:auto; margin-right:auto; margin-top:5px; display: flex;'>
                                                                    <button class='icon-meus-projetos' id='" . $uc_id . "' onclick='changeModalUCValues(this.id)' data-toggle='modal' data-target='#modalChangeUC' style='background: Transparent no-repeat; border: none; width:60%; margin-right:auto; margin-left: auto;'>
                                                                        <i class='fa fa-edit fa-fw' style='color: rgb(45, 179, 96); padding:10px; width: auto;'></i>
                                                                    </button>
                                                                    <input type='hidden' name='uc_nome_". $uc_id ."' value='". $uc_nome ."'>
                                                                    <input type='hidden' name='uc_ano_". $uc_id ."' value='". $row['ano_curricular'] ."'>
                                                                    <input type='hidden' name='uc_semestre_". $uc_id ."' value='". $row['semestre'] ."'>
                                                                    <input type='hidden' name='uc_descricao_". $uc_id ."' value='". $uc_descricao ."'>
                                                                    <button class='icon-meus-projetos' id='" . $uc_id . "' onclick='deleteUC(this.id)' style='background: Transparent no-repeat; border: none; width:60%; margin-right:auto; margin-left: auto;'>
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

    <!-- Modal -> Adiciona UC -->
    <div class="modal fade" id="modalAddUC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Nova Unidade Curricular</label>
                </div>
                <form role="form" action="manage_database.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Nome -->
                        <div class=" form-group">
                            <label>Nome </label>
                            <input type="text" class="form-control" name="add_nome_uc" maxlength="50" required
                                placeholder="Nome da UC...">
                        </div>
                        <!-- Ano Curricular -->
                        <div class="form-group">
                            <label>Ano Curricular</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" name="add_ano_uc" id="ano1" value="1">1º Ano
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="add_ano_uc" id="ano2" value="2">2º Ano
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="add_ano_uc" id="ano3" value="3">3º Ano
                                </label>
                            </div>
                        </div>
                        <!-- Semestre -->
                        <div class="form-group">
                            <label>Semestre</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" name="add_semestre_uc" id="semestre1" value="1">1º Semestre
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="add_semestre_uc" id="semestre2" value="2">2º Semestre
                                </label>
                            </div>
                        </div>
                        <!-- Descrição -->
                        <div class="form-group">
                            <label>Descrição (opcional) </label>
                            <input type="text" class="form-control" name="add_descricao_uc" maxlength="50"
                                placeholder="Descrição da UC...">
                        </div>
                        <!-- atributos hidden para enviar no submit do form -->
                        <input type='hidden' value="add_uc" name='action'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="changeTitulo()">Alterar</button> -->
                        <input type="submit" class="btn btn-primary" value="Inserir">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> Adiciona UC -->

    <!-- Modal -> Edita UC -->
    <div class="modal fade" id="modalChangeUC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding-bottom:5px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <label>Editar Unidade Curricular</label>
                </div>
                <form role="form" action="manage_database.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Nome -->
                        <div class=" form-group">
                            <label>Nome </label>
                            <input type="text" class="form-control" name="edit_nome_uc" maxlength="50" required
                                placeholder="Nome da UC...">
                        </div>
                        <!-- Ano Curricular -->
                        <div class="form-group">
                            <label>Ano Curricular</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" name="edit_ano_uc" id="ano1" value="1">1º Ano
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="edit_ano_uc" id="ano2" value="2">2º Ano
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="edit_ano_uc" id="ano3" value="3">3º Ano
                                </label>
                            </div>
                        </div>
                        <!-- Semestre -->
                        <div class="form-group">
                            <label>Semestre</label>
                            <div style="display: block;">
                                <label class="radio-inline">
                                    <input type="radio" name="edit_semestre_uc" id="semestre1" value="1">1º Semestre
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="edit_semestre_uc" id="semestre2" value="2">2º Semestre
                                </label>
                            </div>
                        </div>
                        <!-- Descrição -->
                        <div class="form-group">
                            <label>Descrição (opcional) </label>
                            <input type="text" class="form-control" name="edit_descricao_uc" maxlength="50"
                                placeholder="Descrição da UC...">
                        </div>
                        <!-- atributos hidden para enviar no submit do form -->
                        <input type='hidden' value="edit_uc" name='action'>
                        <input type='hidden' value="" name='edit_uc_id'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <!-- <button type="button" class="btn btn-primary" onclick="changeTitulo()">Alterar</button> -->
                        <input type="submit" class="btn btn-primary" value="Alterar">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -> Edita UC -->

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

    //-- Ajax to submite delete of UC
    function deleteUC(iduc) {
        var nomeuc = $("input[name=uc_nome_" + iduc + "]").val();

        // console.log("id uc: " + iduc);
        // console.log("nome uc: " + nomeuc);

        if (confirm('Tem a certeza que pretende remover a Unidade Curricular: ' + nomeuc + '?')) {
            $.ajax({
                type: "POST",
                url: 'manage_database.php',
                data: {
                    'action': 'delete_uc',
                    'id_uc': iduc
                },
                success: function(html) {
                    // alert(html);
                    location.reload();
                }
            });
        }
    }

    //-- Com base na UC clicada altera os valores dos inputs da janela modal
    function changeModalUCValues(uc_id) {
        var uc_nome = $("input[name=uc_nome_" + uc_id + "]").val();
        var uc_ano = $("input[name=uc_ano_" + uc_id + "]").val();
        var uc_semestre = $("input[name=uc_semestre_" + uc_id + "]").val();
        var uc_descricao = $("input[name=uc_descricao_" + uc_id + "]").val();

        // console.log("UC ID: " + uc_id);
        // console.log("UC Nome: " + uc_nome);
        // console.log("UC Ano: " + uc_ano);
        // console.log("UC Semestre: " + uc_semestre);
        // console.log("UC Descrição: " + uc_descricao);

        // Modal inputs
        $("input[name=edit_nome_uc]").val(uc_nome);
        $("input[name=edit_ano_uc][value=" + uc_ano + "]").prop('checked', true);
        $("input[name=edit_semestre_uc][value=" + uc_semestre + "]").prop('checked', true);
        $("input[name=edit_descricao_uc]").val(uc_descricao);
        $("input[name=edit_uc_id]").val(uc_id);
    }
    </script>

</body>

</html>