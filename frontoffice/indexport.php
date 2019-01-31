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
    $nome_utilizador;
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
                        $nome_utilizador = $r_nome;
                        $email = $r_email;
                        $_SESSION['nome'] = $nome_utilizador;
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
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Design de Ambientes</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="icon" href="../backoffice/images/website/logotipo_transparente.png">

    <!-- Custom styles for this template -->
    <link href="css/portfolio-item.css" rel="stylesheet" />
    <link href="font/stylesheet.css" rel="stylesheet" />
    <style>
    * {
        box-sizing: border-box
    }

    body {
        font-family: Verdana, sans-serif;
        margin: 0
    }

    .mySlides {
        display: none
    }

    img {
        vertical-align: middle;
    }

    /* Slideshow container */
    .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
    }

    /* Next & previous buttons */
    .prev,
    .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Caption text */
    .text {
        color: #f2f2f2;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active,
    .dot:hover {
        background-color: #717171;
    }

    /* Fading animation */
    .fade {
        -webkit-animation-name: fade;
        -webkit-animation-duration: 1.5s;
        animation-name: fade;
        animation-duration: 1.5s;
    }


    .link-menu {
        color: #000;
        text-decoration: none;
        font-size: 13px;
    }

    .link-menu:hover {
        color: #868686;
        text-decoration: none;

    }

    @-webkit-keyframes fade {
        from {
            opacity: .4
        }

        to {
            opacity: 1
        }
    }

    @keyframes fade {
        from {
            opacity: .4
        }

        to {
            opacity: 1
        }
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {

        .prev,
        .next,
        .text {
            font-size: 11px
        }
    }

    body {
        padding-top: 40px !important;
    }
    </style>
</head>

<body>
    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Row -->
        <div class="row" style="min-height: 456px;">




            <div class='col-md-7' style=" align:center;">
                <?php
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

          $idprojeto = $_GET['idprojeto'];
          $imagens_container = $dots_container = "";
          $conta = 1;

          $result = mysqli_query($connectDB, "select nome from imagem where fk_idprojeto='$idprojeto'");
          if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                  $nome= ($row['nome']);

                  $imagens_container .= "
                    <div class='mySlides fade form-group'>
                    <div class='form-inline' style='height: 100%;'>
                        <img style='max-width:auto; max-height:380px; margin-left:auto; margin-right:auto' class='img-fluid' src='../backoffice/images/projetos/imagens/". $nome ."' style='width:100%' />  
                    </div>
                    </div>
                    ";

                  $dots_container .= "  <span class='dot' onclick='currentSlide(".$conta.")'></span>";
              
                  $conta ++;
              }
              //   echo ("
          // <div class='slideshow-container'>" .
          
          //   $imagens_container .

          //   "<a class='prev' onclick='plusSlides(-1)'>&#10094;</a>" .
          //   "<a class='next' onclick='plusSlides(1)'>&#10095;</a> ".
        
          // "</div>" .
          // "<br>" .
        
          // "<div style='text-align:center'>".
          //   $dots_container .
          // "</div>");
          }
          $result = mysqli_query($connectDB, "select nome from video where fk_idprojeto='$idprojeto'");
          if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                  $nome= ($row['nome']);

                  //       $imagens_container .= "
                  //   <div class='mySlides fade'>
                  //     <iframe width='560' height='315' src='https://www.youtube.com/embed/$nome' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen> </iframe>
                  //   </div>
                  //   ";
                  $imagens_container .= "
                    <div class='mySlides fade embed-responsive embed-responsive-16by9'>
                        <iframe class='embed-responsive-item' src='https://www.youtube.com/embed/$nome' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen> </iframe>  
                    </div>
                    ";

                  $dots_container .= "  <span class='dot' onclick='currentSlide(".$conta.")'></span>";
              }
          }
          echo("
          <div class='slideshow-container' style='height:380px'>" .
          
            $imagens_container .

       
        
          "</div>" .
          "<br>" .
        
          "<div style='text-align:center'>".
            $dots_container .
          "</div>");

            ?>

            </div>

            <div class="col-md-5" style="align:center;">
                <?php
        
              $mesNome = array();
              $mesNome["01"]="Janeiro";
              $mesNome["02"] = "Fevereiro";
              $mesNome["03"] = "Março";
              $mesNome["04"] = "Abril";
              $mesNome["05"] = "Maio";
              $mesNome["06"] = "Junho";
              $mesNome["07"] = "Julho";
              $mesNome["08"] = "Agosto";
              $mesNome["09"] = "Setembro";
              $mesNome["10"] = "Outubro";
              $mesNome["11"] = "Novembro";
              $mesNome["12"] = "Dezembro";

              //$mesString = (string)$mes ;
            //echo(array_search("02", array_keys($mesNome)));

              $result = mysqli_query($connectDB, "SELECT uc.nome, p.autores, p.titulo, p.descricao, p.data, pa.palavra FROM palavra_chave pa, projeto p, unidade_curricular uc WHERE p.idprojeto='$idprojeto' AND p.fk_idUC=uc.idunidade_curricular AND pa.fk_idprojeto='$idprojeto' GROUP BY p.idprojeto");
              if (mysqli_num_rows($result) == 1) {
                  $row = $result->fetch_assoc();
                  $titulo = ($row['titulo']);
                  $descricao = ($row['descricao']);
                  $data = ($row['data']);
                  $nome = ($row['nome']);
                  $autores = ($row['autores']);
                  $palavra= ($row['palavra']);

                  $dia = substr($data, -2);
                  $mes = substr($data, -4, -2);
                  $ano = substr($data, 0, -4);

                  echo "
                    
                      <h3 class='my-3 custom_font' style='font-size:22px;margin-top:0px !important'>" . $titulo . "</h3>
                      <h6 class='my-3 custom_font' style='font-size:14px; line-height: 1.4; min-height:220px'>" . $descricao . "</h6>
                      <h6 class='my-3 custom_font' style='font-size:13px;margin:0px !important;'> <b>Autor/a:</b> " . $autores ." </h6>
                      <h6 class='my-3 custom_font' style='font-size:13px;margin:5px 0 5px 0 !important;'> <b>Unidade Curricular:</b> " . $nome." </h6>
                      <h6 class='my-3 custom_font' style='font-size:13px;margin:5px 0 5px 0 !important;'><b>Data:</b> " . $mesNome[$mes] . " de " . $ano . "</h6>
                      <h6 class='my-3 custom_font' style='font-size:13px;margin:5px 0 15px 0 !important;'><b>Palavras-Chave:</b> " . $palavra . " </h6>
                      
                    ";
              }

              $result = mysqli_query($connectDB, "SELECT DISTINCT f.nome FROM ficheiro f, projeto p WHERE f.fk_idprojeto='$idprojeto'");
              if (mysqli_num_rows($result) == 1) {
                  $row = $result->fetch_assoc();
                  $nome = ($row['nome']);
                
              
                  echo "
                      <a class='link-menu custom-font' href='../backoffice/images/projetos/ficheiros/". $nome ."' download>Clique aqui para fazer o download</a>
                    ";
              }
            ?>
            </div>





        </div>

        <div class="row" style="margin-top:10px; min-height:94px;">
            <div class="col-md-6" style="display: flex;">
                <div style="margin-top:auto; margin-bottom:auto">
                    <form action="index.php">
                        <input type="image" src="img/logo1.png" style="zoom:80%">
                </div>
            </div>


            <div class="col-md-6 custom_font " style="display:flex;">

                <div style="width:fit-content; margin-left:auto; margin-top:auto; margin-bottom:auto; font-size:13px">
                    <?php
                             if ($cargo === "Administrador") {
                                 echo("<a class='link-menu custom-font' href='../backoffice/index.php'>".$username."</a>");
                             } else {
                                 echo("<a class='link-menu custom-font' href='../backoffice/index.php'>".$nome_utilizador."</a>");
                             }
                            ?>
                    | <span id="myText"></span><a id="signout_button" class="link-menu custom-font"
                        href="../backoffice/logout.php"> Logout
                    </a>
                </div>


            </div>


        </div>

        <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            // slides[slideIndex-1].style.display = "block";  
            slides[slideIndex - 1].style.display = "contents";
            dots[slideIndex - 1].className += " active";
        }
        </script>
        <!-- /.container -->

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>