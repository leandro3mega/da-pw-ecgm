<?php
session_start();

require_once("connectdb.php");

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
    header("location:index.php");
    session_write_close();

    exit();
}

// Define variables and initialize with empty values
$password = $confirm_password = $username = $tipo = $resultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //-- 1º Verifica se já existe um utilizador com o mesmo username ou email
    // Validate username
    if (!empty(trim($_POST["email"])) && !empty(trim($_POST["username"]))) {

        // Prepare a select statement
        $sql = "SELECT idutilizador FROM utilizador WHERE username = ?";

        if ($stmt = $connectDB->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    echo "</br>Já se encontra um utilizador registado com o username";
                } else {
                    //-- Se não existir guarda informação nas variaveis
                    $username = trim($_POST["username"]);
                    $tipo = trim($_POST["profissao"]);

                    // echo "</br>Não existe utilizador com o email";
                }
            } else {
                echo "</br>Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    } else {
        header("location: iniciar-sessao.php");
    }

    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirmPassword"]);

    // se na verificação anterior não existia user com o mesmo email na DB, continua no script
    if (!empty($username) && !empty($password) && !empty($confirm_password) && ($password === $confirm_password)) {

    // Prepare an insert statement
        $sql = "INSERT INTO utilizador (username, password, tipo) VALUES (?,?,?)";

        if ($stmt = $connectDB->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssi", $param_username, $param_password, $param_tipo);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_tipo = $tipo;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $stmt->close();
                // echo "</br>Conta de utilizador criada";
                insertUtilizadorData($connectDB, $username, $tipo, $resultado);
            } else {
                $stmt->close();
                echo "</br>Something went wrong. Please try again later.";
            }
        }
    } else {
        $msg = "As palavras passe não são iguais!";
        echo '<script language="javascript">';
        echo 'alert("' . $msg . '");';
        echo 'window.location.replace("gerir-ucs.php");';
        echo '</script>';
    }
}

function insertUtilizadorData($connectDB, $username, $tipo, &$resultado)
{
    $idutilizador = $email = $nome = $fotografia = "";
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $fotografia = "logotipo_white.png";

    //-- 1º Vai buscar o id do utilizador inserido
    if ($stmt = $connectDB->prepare("SELECT idutilizador FROM utilizador WHERE username = ?")) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $stmt->bind_result($r_idutilizador);
            $stmt->store_result();
            $stmt->fetch();

            if ($stmt->num_rows == 1) {
                $idutilizador = $r_idutilizador;
            // echo "</br>Utilizador Encontrado.";
            } else {
                echo "</br>Não encontrou utilizador";
            }
        } else {
            echo "</br>Oops! Something went wrong. Please try again later.";
        }
    }
    // Close statement
    $stmt->close();

    //-- 2º Insere info do utilizador em aluno/docente
    if (!empty($idutilizador)) {
        echo("tipo: " . $tipo);
        if ($tipo == 1) {
            $sql = "INSERT INTO aluno (fk_idutilizador, nome, email, fotografia) VALUES (?,?,?,?)";
        } elseif ($tipo == 2) {
            $sql = "INSERT INTO docente (fk_idutilizador, nome, email, fotografia) VALUES (?,?,?,?)";
        }
  
        if ($stmt = $connectDB->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_idutilizador, $param_nome, $param_email, $param_fotografia);

            $param_idutilizador = $idutilizador;
            $param_nome = $nome;
            $param_email = $email;
            $param_fotografia = $fotografia;
            

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // echo "</br>Informação inseridda!";
                $resultado = "Conta criada com sucesso.";
            } else {
                echo "</br>Something went wrong. Please try again later.";
                removerUtilizador($connectDB, $idutilizador);
                $resultado = "Ocorreu um erro ao criar a conta.";
            }
        }
    } else {
        removerUtilizador($connectDB, $idutilizador);
        $resultado = "Ocorreu um erro ao criar a conta.";
    }
    $stmt->close();
}

function removerUtilizador($connectDB, $idutilizador)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM utilizador WHERE idutilizador=?");
    
    if (false === $stmt) {
        echo "</br>Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idutilizador);

    // executar
    if ($stmt->execute()) {
        // echo "</br>Utilizador removidos com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

// Close connection
mysqli_close($connectDB);

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
    <link rel="stylesheet" href="css/stylesheet.css" </head> <body class="bg-dark">

    <div class="container">
        <div class="card card-register mx-auto mt-5">
            <div class="card-header"><?php echo($resultado); ?></div>
            <div class="card-body" style="padding-top:0.5rem">
                <form id="formregister" action="register.php" method="post">
                    <div class="form-group">
                        <div class="text-center">

                        </div>
                    </div>

                    <a href="iniciar-sessao.php" class="btn btn-lg btn-success btn-block">Iniciar Sessão</a>

                </form>
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