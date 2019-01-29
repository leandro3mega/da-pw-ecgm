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

  <link rel="stylesheet" href="styles.css">

</head>

<body>
  <div style="display: flex;">


    <div class "flex: 0 0 65%;">
      <div class="search">
        <h6 style="margin-left: 20px; margin-top: 70px">Pesquisa</h6>
        <input style="margin-left: 20px" type="text" placeholder="Procurar">

        
      </div>
    </div>
   
    <div class="container">
    <div class="row">

 <?php
  
  require_once("connectdb.php"); // chama o script php de ligação à base de dados

     $idprojeto = 1;

  $sql = "SELECT * FROM projeto "; // query à base de dados. Seleciona todas as noticias e todas as imagens
  $result = $connectDB->query($sql); // executa o query

  //echo $result->num_rows;
  if ($result->num_rows > 0) { // se existirem resultados
      
      while($row = $result->fetch_assoc()) { // percorre o array 

          if($row['idprojeto'] == $idprojeto){


          // cria uma nova imagem no portfolio
          //echo $row["idprojeto"] . " | " . $row["titulo"];
         // echo ($row['idprojeto']  . " | " . $row["titulo"]
         echo "

          
         <div class='col-md-4'>
         <h6 style='margin-left: 400px; margin-top:100px; display: block''><font color='black'><center>"  . $row["titulo"] . "</font></h6>
         <h6 style='margin-left: 400px; margin-top:100px; display: block''><font color='black'><center>"  . $row["descricao"] . "</font></h6>
         <img style='display: block'' src='img/projetos/design.jpg" . "' alt=Lights' style='width:100%; height:80%'>
         
           <div style='height:300px; display: block'>
            </div>
            <div style='display: block;'>
            </div>
             
         </div> ";

        }
      }
  } else {
      echo "Sem Projetos.";
  }
 // $conn->close(); // fecha a conexão à base de dados

?>




</div>
  
</div>








  <div style="flex: 2;  margin-left: 800px; ">

    <form action="index.html">
      <input type="image" src="img/logo.png" style="margin-top: 200px"">
    </form>
  <!-- /.container -->

</div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>