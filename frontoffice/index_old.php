<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Design de Ambientes</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/thumbnail-gallery.css" rel="stylesheet">

  <link rel="stylesheet" href="css/styles.css">

</head>

<body>
  <div style="display: flex;">


    <div class "flex: 0 0 65%;">
      <div class="search">
        <h6 style="margin-left: 20px; ">Pesquisa</h6>
        <input style="margin-left: 20px" type="text" placeholder="Procurar">
        <h6> <br> Unidade Curricular </h6>
        <?php
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

        
       
          $result = mysqli_query($connectDB, "select nome from unidade_curricular");

          if (mysqli_num_rows($result) > 0) {

            while($row = $result->fetch_assoc()) { // percorre o array 
            
              $nome= ($row['nome']);

            echo "
              <li>" . $nome . "</li>
            ";

            }
          }
            ?>
               <br>

          <h6> Ano</h6>
            <?php

            

          require_once("connectdb.php"); // chama o script php de ligação à base de dados



          $result = mysqli_query($connectDB, "select data from projeto GROUP BY data ORDER BY data");

          if (mysqli_num_rows($result) > 0) {

          while($row = $result->fetch_assoc()) { // percorre o array 
          
            $data= ($row['data']);
            $ano = substr($data, 0, -4);

          echo "

            <li>" . $ano . "</li>
          ";

          }
          }
          ?>


          <h6> Semestre </h6>
              <?php

              
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

        
       
          $result = mysqli_query($connectDB, "select semestre from projeto GROUP BY semestre");

          if (mysqli_num_rows($result) > 0) {

            while($row = $result->fetch_assoc()) { // percorre o array 
            
              $semestre= ($row['semestre']);

            echo "
           
              <li>" . $semestre . "</li>
            ";

            }
          }
            ?>

            <h6> Ano Letivo </h6>
              <?php

              
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

        
       
          $result = mysqli_query($connectDB, "select ano from projeto GROUP BY ano");

          if (mysqli_num_rows($result) > 0) {

            while($row = $result->fetch_assoc()) { // percorre o array 
            
              $ano= ($row['ano']);

            echo "
           
              <li>" . $ano . "</li>
            ";

            }
          }
            ?>


            <h6> Tipo </h6>
              <?php

              
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

          $tipotrabalho;
       
          $result = mysqli_query($connectDB, "select tipo from projeto GROUP BY tipo");

          if (mysqli_num_rows($result) > 0) {

            while($row = $result->fetch_assoc()) { // percorre o array 
            
              $tipo= ($row['tipo']);
              if($tipo === 1 ) $tipotrabalho = "Teórico";
              else $tipotrabalho = "Prático";

            echo "
           
             
            ";

            }
          }

          ?>
          
          <li value="1"> Teórico</li>
          <li value="2"> Prático</li>

          <h6> Ferramenta</h6>
              <?php

              
          
          require_once("connectdb.php"); // chama o script php de ligação à base de dados

       
          $result = mysqli_query($connectDB, "select nome from ferramenta ORDER BY nome");

          if (mysqli_num_rows($result) > 0) {

            while($row = $result->fetch_assoc()) { // percorre o array 
            
              $nome= ($row['nome']);

            echo "
            <li>" . $nome . "</li>
             
            ";

            }
          }

          ?>
            
        
      </div>
    </div>


<div class="container">
      <div style="flex: 1; margin-left: 90px; zoom: 80%">

        <div class="row text-center text-lg-left">

    <?php
    
    require_once("connectdb.php"); // chama o script php de ligação à base de dados


$i=0;
$result = mysqli_query($connectDB, "select i.nome, i.idimagem, p.idprojeto from imagem i, projeto p where i.fk_idprojeto=p.idprojeto GROUP BY i.idimagem DESC LIMIT 12");
if (mysqli_num_rows($result) > 0) {


  while($row = $result->fetch_assoc()) { // percorre o array 

    $i++;
   // $row = $result->fetch_assoc();
    $nome= ($row['nome']);
    $idprojeto = ($row['idprojeto']);;
    echo "<div class='col-lg-4 col-md-4 col-xs-6' style='padding-right: 0px; padding-left: 0px; margin-left:-50px;'>
    <a href='indexport.php?idprojeto=". $idprojeto ."'' class='d-block h-100' style='margin-bottom: 0px'>
      <img class='img-fluid img-thumbnail' style='border: 0px; padding: 0px; width:300px; height: 195px;' src='img/projetos/" . $nome ."'> 
    
    </a>
    </div>";

    if($i ==3 || $i==6 || $i==9){
      echo "</div><br>
      <div class='row text-center text-lg-left'>
      ";
     }


  }
}




    ?>
  

        </div>
      </div>

    </div>
    
  </div>



  <div style="flex: 2;  margin-left: 1300px; ">

  <img src="img/logo.png" alt="Italian Trulli">
  <!-- /.container -->

</div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>