<?php

$data="2019-01-12";

$data_split = explode("-", $data);

// for($i=0; $i < sizeof($data_split); $i++){
//     echo($data_split[$i]);
// }
echo "</br>" . $data_split[0] . $data_split[1] . $data_split[2];

// $password = "luis";
// $param_password = password_hash($password, PASSWORD_DEFAULT);

// echo "</br>" . $param_password;



//-- Multiple checkbox post
/*
foreach ($_POST['cb'] as $check) {
    echo ($check . " | ");
}
 */

/*
//-- Verificar se id é null
$id = null;
if (isset($_POST['iduser'])) $id = $_POST['iduser'];

if ($id) {
    echo ("id não é null");
} else {
    echo ("id é null");

}*/

/*
//-- Obter nome mes atravez do numero do mes
$mesNome = array();
$mesNome['01']="Janeiro";
$mesNome['02'] = "Fevereiro";
$mesNome['03'] = "Março";
$mesNome['04'] = "Abril";
$mesNome['05'] = "Maio";
$mesNome['06'] = "Junho";
$mesNome['07'] = "Julho";
$mesNome['08'] = "Agosto";
$mesNome['09'] = "Setembro";
$mesNome['10'] = "Outubro";
$mesNome['11'] = "Novembro";
$mesNome['12'] = "Dezembro";

echo($mesNome['01']);
*/

?>

<!-- for(int i = 0; i<n; i++){
    for(int j = 0; j<n; j++){
        if(mapa[i][j] == 7){
            mapa[i][j] = 0;
            mapa[i-1][j] = 7;
        }
    }       
} -->