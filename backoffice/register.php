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

<?php

require_once("connectdb.php");

// Output variavel
$result;

//-- Prepared Statment: high efficiency; Protection against sql injection
// Prepare an insert statement
$createUserQuery = "INSERT INTO utilizador (username, password, tipo) VALUES (?, ?, ?)";

if ($stmt = mysqli_prepare($connectDB, $createUserQuery)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $tipo);
    
    // Set parameters
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $tipo = $_REQUEST['profissao'];

    //echo ('user: ' . $username . ' | pass: ' . $password);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = 'Conta Criada Com Sucesso.';
    } else {
        $result = "Ocurreu um erro: Não conseguiu executar a query: $createUserQuery. " . mysqli_error($connectDB);
    }
} else {
    $result = "Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery. " . mysqli_error($connectDB);
}
 
// Close statement
mysqli_stmt_close($stmt);
 
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
    <link rel="stylesheet" href="css/stylesheet.css"

  </head>

  <body class="bg-dark">

    <div class="container">
      <div class="card card-register mx-auto mt-5">
        <div class="card-header">Resultado</div>
        <div class="card-body" style="padding-top:0.5rem">
          <form id="formregister" action="register.php" method="post">
            <div class="form-group">
                <div class="text-center">
                    
                    <label class="label-bold" style="margin-top:10px; margin-bottom:30px"> <?php echo $result ?></label>
                
              </div>
            </div>

            <a href="loginpage.php" class="btn btn-lg btn-success btn-block">Iniciar Sessão</a>    

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