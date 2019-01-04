<?php
session_start();

require_once("connectdb.php");

//--Variaveis
/*
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$autores = $_POST['autores'];
$tipo = $_POST['tipo'];
$semestre = $_POST['semestre'];
$uc = $_POST['selectUC'];
$image = $_FILES["img1"]['tmp_name'];
$data = $_POST['data'];
$ficheiro = $_FILES["ficheiro"]['tmp_name'];
$video = $_POST['video'];
$palavras_chave = $_POST['palavras-chave'];
//$categorias = $_POST['categorias'];
$ferramentas = $_POST['ferramentas'];
*/

$resultString;
$idprojeto;

if(isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['autores']) && isset($_POST['tipo']) &&
    isset($_POST['semestre']) && isset($_POST['selectUC']) && isset($_POST['data'])){

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $autores = $_POST['autores'];
    $data = $_POST['data']; //-- data no formato yyyy-mm-dd
    $data_split = explode("-", $data); //-- faz split retirando o "-" e guarda as strings em array
    $data = $data_split[0] . $data_split[1] . $data_split[2];
    
    //obterIdProjeto($connectDB, $resultString, $idprojeto, $titulo, $descricao, $autores, $data);
    seExisteProjeto($connectDB, $resultString, $idprojeto, $titulo, $descricao, $autores, $data);
}
//-- Inserir Projeto
//-- Inserir FerramentaProjeto
//-- Inserir alunoProjeto
//-- Inserir docenteProjeto
//-- Inserir Palavras_Chave
//-- Inserir Imagem
//-- Inserir Video
//-- Inserir Ficheiro

function insertProjeto($connectDB, &$resultString, &$idprojeto){
    $createUserQuery = "INSERT INTO projeto (fk_iduc, titulo, descricao, autores, data, ano, semestre, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($connectDB, $createUserQuery)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $fk_iduc, $titulo, $descricao, $autores, $data, $ano, $semestre, $tipo);
        
        $fk_iduc = $_POST['selectUC'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $autores = $_POST['autores'];
        $data = $_POST['data']; //-- data no formato yyyy-mm-dd
        $data_split = explode("-", $data);  //-- faz split retirando o "-" e guarda as strings em array
        $data = $data_split[0] . $data_split[1] . $data_split[2];
        $ano = $_POST['selectAnoLetivo'];
        $semestre = $_POST['semestre'];
        $tipo = $_POST['tipo'];

        //echo "Data_split: " . $data;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $resultString .= '</br>(4) Dados inseridos em Docente com sucesso. ';

            selectProjeto($connectDB, $resultString, $acaoProjeto, $idprojeto);
        } else {
            $resultString .= "</br>(4) Ocurreu um erro: Não conseguiu executar a query: $createUserQuery" . mysqli_error($connectDB) . ". ";
            //deleteProjeto($connectDB, $resultString, $username);
            //selectProjeto($titulo, $descricao);
        }
    } else {
        $resultString .= "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery" . mysqli_error($connectDB) . " . ";
        //deleteProjeto($connectDB, $resultString, $username);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    echo($resultString);

}

//-- Select do projeto inserido para obter o id do mesmo
function obterIdProjeto($connectDB, &$resultString, &$idprojeto, $titulo, $descricao, $autores, $data){
    // query à base de dados
    $sqlSelect = "SELECT idprojeto FROM projeto WHERE titulo=? AND descricao=? AND autores=? AND data=?";

    // inicializar prepared statement
    $stmt = $connectDB->prepare($sqlSelect);

    // md5 para desincriptar a password
    //$password = md5($pass_old);
    $stmt->bind_param("ssss", $titulo, $descricao, $autores, $data);

    // executar
    $stmt->execute();

    // associar os parametros de output
    $stmt->bind_result($r_idprojeto);

    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();

    // iterar / obter resultados
    $stmt->fetch();

    if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
        $idprojeto = $r_idprojeto;
        echo("(Select Projetos) ID do projeto encontrado: " . $idprojeto);
    }
    $stmt->close();
}

//-- Seleciona os titulo, descricao, autores e data para descobrir se o projeto ja existe
function seExisteProjeto($connectDB, &$resultString, &$idprojeto, $titulo, $descricao, $autores, $data){
    // query à base de dados
    $sqlSelect = "SELECT idprojeto FROM projeto WHERE titulo=? AND descricao=? AND autores=? AND data=?";

    // inicializar prepared statement
    $stmt = $connectDB->prepare($sqlSelect);

    // md5 para desincriptar a password
    //$password = md5($pass_old);
    $stmt->bind_param("ssss", $titulo, $descricao, $autores, $data);

    // executar
    $stmt->execute();

    // associar os parametros de output
    $stmt->bind_result($r_idprojeto);

    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();

    // iterar / obter resultados
    $stmt->fetch();

    if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
        echo("(Select Projetos) Já se encontra um projeto igual no site: " . $idprojeto);
        //--Volta a pagina de novo projeto com alert
        $idprojeto = $r_idprojeto;
        removeProjeto($connectDB, $resultString, $idprojeto);
    } else {
        echo("(Select Projetos) Não se encontra um projeto igual no site: " . $idprojeto);
        return true;
    }
    $stmt->close();
}

function removeProjeto($connectDB, &$resultString, $idprojeto){
    // query à base de dados
    //$sqlSelect = "DELETE FROM utilizador WHERE idprojeto=?";

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM projeto WHERE idprojeto=?");

    if ( false === $stmt ) {
        error_log('mysqli prepare() failed: ');
        //error_log( print_r( htmlspecialchars($stmt->error), true ) );

        // Since all the following operations need a valid/ready statement object
        // it doesn't make sense to go on
        exit();
    }

    // md5 para desincriptar a password
    $stmt->bind_param("s", $idprojeto);

    // executar
    $stmt->execute();

    $stmt->close();
}

function insertFerramentaProjeto($connectDB){

}

function insertAlunoProjeto($connectDB){

}

function insertDocenteProjeto($connectDB){

}

function insertPalavrasChave($connectDB){

}

function insertImagens($connectDB){

}

function insertVideo($connectDB){

}

function insertFicheiro($connectDB){

}



echo ("Titulo: " . $_POST['titulo'] . " | ");
echo ("Descricao: " . $_POST['descricao'] . " | ");
echo ("Autores: " . $_POST['autores'] . " | ");
echo ("Tipo: " . $_POST['tipo'] . " | ");
echo ("Semestre: " . $_POST['semestre'] . " | ");
echo ("UC: " . $_POST['selectUC'] . " | ");
echo ("Fotografia: " . $_FILES["img1"]['tmp_name'] . " | ");
echo ("Data: " . $_POST['data'] . " | ");
echo ("PDF: " . $_FILES["ficheiro"]['tmp_name'] . " | ");
echo ("Video: " . $_POST['video'] . " | ");
echo ("Palavras chave: " . $_POST['palavras-chave'] . " | ");
//echo ("Categorias: " . $_POST['categorias'] . " | ");
echo ("Ferramentas: " . $_POST['ferramentas'] . " | ");


?>