<?php
     
require_once("connectdb.php"); // chama o script php de ligação à base de dados

   selectProjetos($connectDB);
    function selectProjetos($connectDB){
        //$minIndex = $_GET['min_index'];
        //$maxIndex = $_GET['max_index'];
        $minIndex = 0;
        $maxIndex = 12;

        $result = mysqli_query($connectDB, "SELECT projeto.*, imagem.nome as img, ferramenta.*, unidade_curricular.* FROM projeto 
        LEFT JOIN unidade_curricular ON unidade_curricular.idunidade_curricular = projeto.fk_idUC
        LEFT JOIN imagem ON imagem.fk_idprojeto = projeto.idprojeto
        LEFT JOIN ferramenta_projeto ON ferramenta_projeto.fk_projeto = projeto.idprojeto
        LEFT JOIN ferramenta ON ferramenta.idferramenta = ferramenta_projeto.fk_ferramenta
        ".$condicao." GROUP BY projeto.idprojeto ORDER BY projeto.data DESC LIMIT $minIndex, $maxIndex");
        
        $rows = array();
        if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) { // percorre o array 
                $rows[] = $row;
            
            }

            print json_encode(($rows));
          }

    }






?>