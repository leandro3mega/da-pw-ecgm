<?php
session_start();

require_once("connectdb.php");

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
  header("location:index.php");
  session_write_close();

  exit();
}

// Output variavel
$result = "";
$encontrado = false;
//$sucessoUserCriado = false;
$username = $_REQUEST['username'];
//$idUtilizador;

//-- 1º: Select users para verificar se já existe
$selectUserQuery = "SELECT * FROM utilizador";

if ($usersResult = mysqli_query($connectDB, $selectUserQuery)) {
  if (mysqli_num_rows($usersResult) > 0) {
    while (!$encontrado && $row = mysqli_fetch_array($usersResult)) {
      if ($username === $row['username']) {
        $encontrado = true;
      } else {
        $encontrado = false;
      }
    }
  } else {
    $result .= "</br>(1) Não Existem users na DB; ";
  }
  //-- Se não existir da DB insere user
  if (!$encontrado) {
    //-- 2º: Insert user in DB
    $result .= "</br>(1) User " . $username . " não existe na DB";
    insertUtilizador($username, $connectDB, $result);

  } else {
    $result .= "</br>(1) User " . $username . " existe na DB";
  }
}

//-- 2º: Insert user in DB (result passed by reference)
function insertUtilizador($username, $connectDB, &$result)
{
  //-- Prepared Statment: high efficiency; Protection against sql injection
  // Prepare an insert statement
  //-- insert into utilizador
  $createUserQuery = "INSERT INTO utilizador (username, password, tipo) VALUES (?, ?, ?)";

  if ($stmt = mysqli_prepare($connectDB, $createUserQuery)) {
      // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $tipo);
      
    // Set parameters
    //$username = $_REQUEST['username'];  // ja recebido no inicio
    $password = $_REQUEST['password'];
    $tipo = $_REQUEST['profissao'];
  
      //echo ('user: ' . $username . ' | pass: ' . $password);
  
      // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result .= "</br>(2)Dados inseridos em Utilizador com sucesso. ";
      //$sucessoUserCriado = true;
      selectUser($username, $connectDB, $result);

    } else {
      $result .= "</br>(2) Ocurreu um erro: Não conseguiu executar a query: $createUserQuery" . mysqli_error($connectDB) . ". ";
      //$sucessoUserCriado = false;
    }
  } else {
    $result .= "</br>(2) Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery" . mysqli_error($connectDB) . ". ";
    //$sucessoUserCriado = false;
  }
   
  // Close statement
  mysqli_stmt_close($stmt);

}

//-- 3: Select user based on username -> id and profissao to insert data
function selectUser($username, $connectDB, &$result)
{
  $id = null;
  $profissao = null;
  $result .= "</br>(3) $username";
  //-- Next
  //-- Select of the inserted user
  //if ($sucessoUserCriado) {
  $selectUserQuery = "SELECT * FROM utilizador where username = '$username'";

  if ($usersResult = mysqli_query($connectDB, $selectUserQuery)) {

    if (mysqli_num_rows($usersResult) > 0) {

      while ($row = mysqli_fetch_array($usersResult)) {
        $id = $row['idutilizador'];
        $profissao = $row['tipo'];
        $result .= "</br>(3) Encontrado com ID: " . $id . ". ";
        $result .= "</br>(3) UsernameDB: " . $row['username'] . ". ";
        $result .= "</br>(3) ProfissaoDB: " . $profissao . ". ";
        insertData($connectDB, $result, $id, $username, $profissao);
      }
    } else {
      $result .= "</br>(3) User não encontrado; ";
    }
  }

  //}
}

//-- 4º: Insert user data in Aluno/Docente
function insertData($connectDB, &$result, $id, $username, $profissao)
{
  $result .= "</br>(4) ID: " . $id;
  $result .= "</br>(4) Username: " . $username;
  $result .= "</br>(4) Profissao: " . $profissao;
  //-- First select user to obtain id
  
  //-- If there is no existent aluno with the same user id, insert data
  //-- Next
  //-- Insert into Aluno/Docente
  //-- é aluno
  if ($profissao == 1) {
    if (!selectAlunos($connectDB, $result, $id, $username)) {

      $createUserQuery = "INSERT INTO aluno (fk_idutilizador, nome, email, fotografia) VALUES (?, ?, ?, ?)";

      if ($stmt2 = mysqli_prepare($connectDB, $createUserQuery)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt2, "ssss", $fk_idutilizador, $nome, $email, $fotografia);
  
        // Set parameters
        $fk_idutilizador = $id;
        $nome = $_REQUEST['nome'];
        $email = $_REQUEST['email'];
        $fotografia = 'logotipo_white.png';

        echo ("Imagem: " . $fotografia);

        $result .= '</br>(4) Imagem: ' . $fotografia;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt2)) {
          $result .= '</br>(4) Dados inseridos em Aluno com sucesso. ';
        } else {
          $result .= "</br>(4) Ocurreu um erro: Não conseguiu executar a query: $createUserQuery" . mysqli_error($connectDB) . ". ";
          deleteUser($connectDB, $result, $username);
        }
      } else {
        $result .= "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery" . mysqli_error($connectDB) . " . ";
        deleteUser($connectDB, $result, $username);
      }

      // Close statement
      mysqli_stmt_close($stmt2);
    } else {
      //-- Delete utilizador
      deleteUser($connectDB, $result, $username);

    }
  } else if ($profissao == 2) {
    //-- é professor
    if (!selectDocentes($connectDB, $result, $id, $username)) {

      $createUserQuery = "INSERT INTO docente (fk_idutilizador, nome, email, fotografia) VALUES (?, ?, ?, ?)";

      if ($stmt2 = mysqli_prepare($connectDB, $createUserQuery)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt2, "ssss", $fk_idutilizador, $nome, $email, $fotografia);
  
        // Set parameters
        $fk_idutilizador = $id;
        $nome = $_REQUEST['nome'];
        $email = $_REQUEST['email'];
        $fotografia = 'logotipo_white.png';

        echo ("Imagem: " . $fotografia);

        $result .= '</br>(4) Imagem: ' . $fotografia;
      
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt2)) {
          $result .= '</br>(4) Dados inseridos em Docente com sucesso. ';
        } else {
          $result .= "</br>(4) Ocurreu um erro: Não conseguiu executar a query: $createUserQuery" . mysqli_error($connectDB) . ". ";
          deleteUser($connectDB, $result, $username);
        }
      } else {
        $result .= "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery" . mysqli_error($connectDB) . " . ";
        deleteUser($connectDB, $result, $username);
      }

      // Close statement
      mysqli_stmt_close($stmt2);
    } else {
      //-- Delete utilizador
      deleteUser($connectDB, $result, $username);
    }
  }
}

//-- 5º: Select of alunos -> if idUser exists return true so we dont insert data into alunos
function selectAlunos($connectDB, &$result, $id, $username)
{
  $encontrado = false;
  $selectUserQuery = "SELECT * FROM aluno";

  if ($usersResult = mysqli_query($connectDB, $selectUserQuery)) {
    if (mysqli_num_rows($usersResult) > 0) {
      while (!$encontrado && $row = mysqli_fetch_array($usersResult)) {
        if ($id === $row['fk_idutilizador']) {
          $result .= "</br>(5) Aluno com " . $username . " existe na DB. Não pode inserir data";
          $encontrado = true;
        } else {
          $result .= "</br>(5) Aluno com " . $username . " não existe na DB. Pode inserir data";
          $encontrado = false;
        }
      }
    } else {
      $result .= "</br>(5) Não Existem Alunos na DB; ";
      $encontrado = false;
    }
  }

  //-- Se existe ou não user com mesmo id em Alunos
  if ($encontrado) return true;
  else return false;
}

//-- 5º: Select of docentes -> if idUser exists return true so we dont insert data into docente
function selectDocentes($connectDB, &$result, $id, $username)
{
  $encontrado = false;
  $selectUserQuery = "SELECT * FROM docente";

  if ($usersResult = mysqli_query($connectDB, $selectUserQuery)) {
    if (mysqli_num_rows($usersResult) > 0) {
      while (!$encontrado && $row = mysqli_fetch_array($usersResult)) {
        if ($id === $row['fk_idutilizador']) {
          $result .= "</br>(5) Docente com " . $username . " existe na DB. Não pode inserir data";
          $encontrado = true;
        } else {
          $result .= "</br>(5) Docente com " . $username . " não existe na DB. Pode inserir data";
          $encontrado = false;
        }
      }
    } else {
      $result .= "</br>(5) Não Existem Docentes na DB; ";
      $encontrado = false;
    }
  }

  //-- Se existe ou não user com mesmo id em Alunos
  if ($encontrado) return true;
  else return false;
}

//-- 6º: delete new user data if failed to insert data into Aluno/Docente
function deleteUser($connectDB, &$result, $username)
{
  $deleteUserQuery = "DELETE FROM utilizador WHERE username = '$username'";
  if (mysqli_query($connectDB, $deleteUserQuery)) {
    $result .= '</br>(6) Dados do utilizador eliminados com sucesso. ';
        //echo "Records were deleted successfully.";
  } else {
    $result .= "Ocurreu um erro: Não conseguiu executar a query: $deleteUserQuery. " . mysqli_error($connectDB);
        //echo "Ocurreu um erro: Não conseguiu executar a query: $deleteUserQuery. " . mysqli_error($connectDB);
  }
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