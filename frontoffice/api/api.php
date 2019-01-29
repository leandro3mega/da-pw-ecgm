<?php
    $condicao = $_POST['condicoes'];
    $minIndex = 0;
    $maxIndex = 12;

    if(isset($_POST['minIndex'])){
        $minIndex = $_POST['minIndex'];
        $maxIndex = $_POST['maxIndex'];
    }

    require_once("../connectdb.php"); // chama o script php de ligação à base de dados
    
    $result = mysqli_query($connectDB, "SELECT projeto.*, imagem.nome as img, palavra_chave.*, ferramenta.*, unidade_curricular.* FROM projeto 
    LEFT JOIN unidade_curricular ON unidade_curricular.idunidade_curricular = projeto.fk_idUC
    LEFT JOIN palavra_chave ON palavra_chave.fk_idprojeto = projeto.idprojeto
    LEFT JOIN imagem ON imagem.fk_idprojeto = projeto.idprojeto
    LEFT JOIN ferramenta_projeto ON ferramenta_projeto.fk_projeto = projeto.idprojeto
    LEFT JOIN ferramenta ON ferramenta.idferramenta = ferramenta_projeto.fk_ferramenta
    ".$condicao." GROUP BY projeto.idprojeto ORDER BY projeto.data DESC LIMIT $minIndex, $maxIndex");

    if (mysqli_num_rows($result) > 0) {
        foreach ($result as $row) {
            $output[] = $row;
        }
        echo json_encode($output);
    }else{
        echo '0';
    }
?>