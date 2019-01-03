<?php
session_start();

require_once("connectdb.php");

if ($_GET['action'] == 'get_ucs') {
    $semestre = $_GET['semestre'];
    //echo ($semestre);
    //-- Seleciona as ucs a mostrar na pagina novo-projeto
    selectUCS($connectDB, $semestre);

}

//-- Select do nome das UCs
function selectUCS($connectDB, $semestre)
{
    $rows = array();
    $conta = 0;
    $resultUC = mysqli_query($connectDB, "SELECT idunidade_curricular, nome FROM unidade_curricular WHERE semestre='$semestre' ORDER BY nome");

    if (mysqli_num_rows($resultUC) > 0) {
        while ($row = $resultUC->fetch_assoc()) {
            $rows[] = $row;//$row['nome'];
            $conta += 1;
        }
        print json_encode(($rows));
    }
}


?>