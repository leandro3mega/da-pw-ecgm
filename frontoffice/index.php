<?php
session_start();

require_once("connectdb.php");

if (!isset($_SESSION['username'])) {
    header("location:../backoffice/iniciar-sessao.php");
    exit();
} else {
    // header("location:meus-projetos.php");

    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $nome;
    $email;
    $tipo = $_SESSION['tipo'];
    $cargo = $_SESSION['cargo'];
    $username = $_SESSION['username'];

    //-- vai buscar o nome do utilizador que corresponde ao id da sessão
    if ($tipo == 1) {
        $sql = "SELECT nome, email, fotografia FROM aluno WHERE fk_idutilizador=?";
    } elseif ($tipo == 2) {
        $sql = "SELECT nome, email, fotografia FROM docente WHERE fk_idutilizador=?";
    }

    if ($tipo == 1 || $tipo == 2) {
        if ($stmt = $connectDB->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_idutilizador);
                
            // Set parameters
            $param_idutilizador = $id;
                
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($r_nome, $r_email, $r_foto);
                    if ($stmt->fetch()) {
                        //-- Atribui variaveis
                        $nome = $r_nome;
                        $email = $r_email;
                        $_SESSION['nome'] = $nome;
                    }
                } else {
                    // echo"Não foi encontrado video ";
                }
            } else {
                echo "Algo correu mal. Por favor tente de novo.";
            }
        }
        $stmt->close();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Design de Ambientes</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/thumbnail-gallery.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="font/stylesheet.css" rel="stylesheet" />

    <link href="../backoffice/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="icon" href="../backoffice/images/website/logotipo_transparente.png">

    <script src="js/index.js"></script>

    <style>
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }

    .link-menu {
        color: #000;
        text-decoration: none;
    }

    .link-menu:hover {
        color: #868686;
        text-decoration: none;

    }

    .img-projeto {
        min-width: 100%;
        min-height: auto;
        max-width: 100%;
        max-height: 100%;
        height: 150px;
        border: none;
        border-radius: 0px;
        padding: 0px;
        overflow: hidden;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <?php
      require_once("connectdb.php");
    ?>
    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class=" col-md-11">
                <div class="row text-center text-lg-left0 " data-grid-projetos></div>

                <div class="row">
                    <div class="col-md-3 ">

                        <a href="index.php"><img src="img/logo.png" alt="Italian Trulli"
                                style="zoom:60%; margin-top:40px"></a>
                    </div>

                    <div class="col-md-3 " style="margin-top:40px">


                        <button class="btn btn-default dropdown-toggle custom_font" type="button"
                            data-toggle="dropdown">Filtros
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <a class="test" tabindex="-1" href="#">Unidade Curricular <span
                                        class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <?php
              require_once("connectdb.php");
              $result = mysqli_query($connectDB, "select unidade_curricular.* from unidade_curricular");
    
              if (mysqli_num_rows($result) > 0) {
                  while ($row = $result->fetch_assoc()) { // percorre o array
                      echo "<li> <input type='checkbox' data-tabela='unidade_curricular' data-tipo='idunidade_curricular' data-valor='".($row['idunidade_curricular'])."'/>".($row['nome'])."</li>";
                  }
              }
            ?>

                                </ul>

                                <a class="test" tabindex="-1" href="#">Ano <span class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <?php
                        
            
            require_once("connectdb.php"); // chama o script php de ligação à base de dados
  
  
  
            $result = mysqli_query($connectDB, "SELECT data, SUBSTRING(data,1,4) as anos from projeto GROUP BY anos ORDER BY anos");
  
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) { // percorre o array
            
                    $ano = ($row['anos']);
                    ;
              
                    echo "
  
              
             <li data-filtro> <input type='checkbox' data-tabela='projeto' data-tipo='data' data-valor='".$ano."'/>".$ano."</li>
            ";
                }
            }
            ?>

                                </ul>


                                <a class="test" tabindex="-1" href="#">Semestre <span class="caret"></span></a>
                                <ul class="dropdown-menu" data-filtro>

                                    <?php
            
                          
                      
            require_once("connectdb.php"); // chama o script php de ligação à base de dados
  
          
         
            $result = mysqli_query($connectDB, "select projeto.semestre from projeto GROUP BY semestre");
  
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) { // percorre o array
              
                    $semestre= ($row['semestre']);
  
                    echo "
             
              <li><input type='checkbox' data-tabela='projeto' data-tipo='semestre' data-valor='".$semestre."'/>".$semestre." Semestre  <br /> </li>
              ";
                }
            }
              ?>

                                </ul>

                                <a class="test" tabindex="-1" href="#">Ano Letivo <span class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <?php
            
                          
                      
            require_once("connectdb.php"); // chama o script php de ligação à base de dados
  
          
         
            $result = mysqli_query($connectDB, "select ano from projeto GROUP BY ano");
  
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) { // percorre o array
              
                    $ano_letivo= ($row['ano']);
  
                    echo "<li> <input type='checkbox' data-tabela='projeto' data-tipo='ano' data-valor='".$ano_letivo."'>".$ano_letivo." Ano  <br/></li>";
                }
            }
              ?>

                                </ul>

                                <a class="test" tabindex="-1" href="#">Tipo <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <input type="checkbox" data-tabela="projeto" data-tipo='tipo' data-valor='1' />
                                    Teórico <br />
                                    <input type="checkbox" data-tabela="projeto" data-tipo='tipo' data-valor='2' />
                                    Prático <br />
                                </ul>

                                <a class="test" tabindex="-1" href="#">Ferramenta <span class="caret"></span></a>
                                <ul class="dropdown-menu">

                                    <?php
            require_once("connectdb.php");
            $result = mysqli_query($connectDB, "select ferramenta.* from ferramenta ORDER BY nome");
  
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) { // percorre o array
                    echo "<li> <input type='checkbox' data-tipo='idferramenta' data-tabela='ferramenta' data-valor='".($row['idferramenta'])."'/>".($row['nome'])."<br/></li>";
                }
            }
            ?>

                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 custom_font" style="margin-top:0px; display:flex;">
                        <!-- <h6>Pesquisa</h6> -->
                        <div class="form-inline">
                            <input type="search" class="form-control" data-tabela="palavra_chave" data-tipo="palavra"
                                data-valor="" data-procura="procura" placeholder="Pesquisar..." style="width:90%">
                            <!-- <input type="search" data-tabela="projeto" data-tipo="titulo" data-valor=""
                                data-procura="procura" placeholder="Procurar por titulo"> -->
                            <!-- <button data-btn-pesquisa
                                style='width:10%; height:100%; background: Transparent no-repeat; border: none; margin-top:auto; margin-bottom:auto'> -->
                            <a data-btn-pesquisa style="margin-top:auto; margin-bottom:auto; width:10%; height:auto;"><i
                                    class='fa fa-search fa-fw' style="font-size: 150%;"></i></a>
                            <!-- </button> -->
                        </div>
                    </div>
                    <div class="col-md-3  custom_font " style="margin-top:0px; display:flex;">
                        <!-- Nome | <span id="myText"></span><a id="signout_button"  href="#"> logout </a> -->
                        <div
                            style="width:fit-content; margin-left:auto; zoom:100%; margin-top:auto; margin-bottom:auto">
                            <?php
                            if ($cargo === "Administrador") {
                                echo("<a class='link-menu custom-font' href='../backoffice/index.php'>".$username."</a>");
                            } else {
                                echo("<a class='link-menu custom-font'  href='../backoffice/index.php'>".$nome."</a>");
                            }
                            ?>
                            | <span id="myText"></span><a id="signout_button" class="link-menu custom-font"
                                href="../backoffice/logout.php"> Logout
                            </a>
                        </div>
                        <div>
                            <!-- <button type="button" data-pagina-baixo> Baixo</button> -->
                            <!-- <button type="button" data-pagina-baixo
                                style='background: Transparent no-repeat; border: none;'>
                                <a><i class='fa fa-chevron-up fa-fw' style="font-size: 200%;"></i></a>
                            </button>
                            <button type="button" data-pagina-cima
                                style='background: Transparent no-repeat; border: none;'>
                                <a><i class='fa fa-chevron-down fa-fw' style="font-size: 200%;"></i></a>
                            </button> -->
                            <!-- Botão com icon para pesquisa -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <!-- <div class="row">
                    <button type="button" data-pagina-baixo style='background: Transparent no-repeat; border: none;'>
                        <a><i class='fa fa-chevron-up fa-fw' style="font-size: 200%;"></i></a>
                    </button>
                </div>
                <div class="row" style="margin-top:400px;">
                    <button type="button" data-pagina-cima style='background: Transparent no-repeat; border: none;'>
                        <a><i class='fa fa-chevron-down fa-fw' style="font-size: 200%;"></i></a>
                    </button>
                </div> -->
                <div class="row" style="display: inline-block; height: 100%;">

                    <div class="align-top" style="display: flex; height: 75%; align-items: start;">
                        <!-- <button type="button" data-pagina-baixo -->
                        <!-- style='background: Transparent no-repeat; border: none;'> -->
                        <a data-pagina-baixo><i class='fa fa-chevron-up fa-fw' style="font-size: 200%;"></i></a>
                        <!-- </button> -->
                    </div>
                    <div class="align-bottom" style="display: flex; height: 25%;">
                        <!-- <button type="button" data-pagina-cima style='background: Transparent no-repeat; border: none;'> -->
                        <a data-pagina-cima><i class='fa fa-chevron-down fa-fw' style="font-size: 200%;"></i></a>
                        <!-- </button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>