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


if (isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['autores']) && isset($_POST['tipo']) &&
    isset($_POST['semestre']) && isset($_POST['selectUC']) && isset($_POST['data'])) {

    // $titulo = $_POST['titulo'];
    // $descricao = $_POST['descricao'];
    // $autores = $_POST['autores'];
    // $data = $_POST['data']; //-- data no formato yyyy-mm-dd
    // $data_split = explode("-", $data); //-- faz split retirando o "-" e guarda as strings em array
    // $data = $data_split[0] . $data_split[1] . $data_split[2];
    
    $resultString = "";
    $idprojeto;
    $sucesso = true;    // para verificar a cada metodo se foi exetucado com sucesso

    $fk_iduc = $_REQUEST['selectUC'];
    $titulo = $_REQUEST['titulo'];
    $descricao = $_REQUEST['descricao'];
    $autores = $_REQUEST['autores'];
    $data = $_REQUEST['data']; //-- data no formato yyyy-mm-dd
    $data_split = explode("-", $data); //-- faz split retirando o "-" e guarda as strings em array
    $data = $data_split[0] . $data_split[1] . $data_split[2];
    $ano = $_REQUEST['selectAnoLetivo'];
    $semestre = $_REQUEST['semestre'];
    $tipo = $_REQUEST['tipo'];
    /*
    insertProjeto($connectDB, $sucesso, $idprojeto, $fk_iduc, $titulo, $descricao, $autores, $data, $ano, $semestre, $tipo);

    if ($sucesso) {
        obterIdProjeto($connectDB, $idprojeto, $titulo, $descricao, $autores, $data);
    }
    if ($sucesso) {
        //removeProjeto($connectDB, $idprojeto);
        getAluno($connectDB, $idprojeto, $autores);
    }
    if ($sucesso) {
        getFerramentas($connectDB, $idprojeto);
    }*/
    if ($sucesso) {
        $idprojeto = 24;    //-- Only for testing purposes
    }

    //seExisteProjeto($connectDB, $idprojeto, $titulo, $descricao, $autores, $data);
}

//-- Insere um projeto com determinadas informações
function insertProjeto($connectDB, &$sucesso, &$idprojeto, $fk_iduc, $titulo, $descricao, $autores, $data, $ano, $semestre, $tipo)
{
    $createUserQuery = "INSERT INTO projeto (fk_iduc, titulo, descricao, autores, data, ano, semestre, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    //-- Se existir projeto com o mesmo nome, não insere
    if (!seExisteProjeto($connectDB, $titulo, $descricao, $autores, $data)) {
        // -- If statement is prepared
        if ($stmt = mysqli_prepare($connectDB, $createUserQuery)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $fk_iduc, $titulo, $descricao, $autores, $data, $ano, $semestre, $tipo);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo '</br>(4) Dados inseridos em Projeto com sucesso. ';
                $sucesso = true;
            } else {
                echo "</br>(4) Ocurreu um erro: Não conseguiu executar a query: $createUserQuery" . mysqli_error($connectDB) . ". ";
                $sucesso = false;
            }
        } else {
            echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query: $createUserQuery" . mysqli_error($connectDB) . " . ";
            $sucesso = false;
            //deleteProjeto($connectDB, $username);
        }
    
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

//-- Seleciona os titulo, descricao, autores e data para descobrir se o projeto ja existe
function seExisteProjeto($connectDB, $titulo, $descricao, $autores, $data)
{
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
        echo("(Select Projetos) Já se encontra um projeto igual no site: " . $r_idprojeto);
        
        $stmt->close();
        return true;
    } else {
        echo("(Select Projetos) Não se encontra um projeto igual no site: ");
        $stmt->close();
        return false;
    }
}

//-- Select do projeto inserido para obter o id do mesmo
function obterIdProjeto($connectDB, &$idprojeto, $titulo, $descricao, $autores, $data)
{
    // query à base de dados
    $sqlSelect = "SELECT idprojeto FROM projeto WHERE titulo=? AND descricao=? AND autores=? AND data=?";

    // inicializar prepared statement
    $stmt = $connectDB->prepare($sqlSelect);

    $stmt->bind_param("ssss", $titulo, $descricao, $autores, $data);

    // executar
    $stmt->execute();

    // associar os parametros de output
    $stmt->bind_result($r_idprojeto);

    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();

    // iterar / obter resultados
    $stmt->fetch();

    if ($stmt->num_rows == 1) {
        $idprojeto = $r_idprojeto;
        echo "</br>(obterIdProjeto) ID do projeto encontrado: " . $idprojeto;
    } else {
        echo "</br>(obterIdProjeto) Não encontrou projeto.";
    }
    $stmt->close();
}

//-- Remove um projeto com um determinado id
function removeProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM projeto WHERE idprojeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>Projeto removido com sucesso.";
    } else {
        $result .= "</br>(2) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//-- Faz a ligação entre o aluno e os autores
function getAluno($connectDB, $idprojeto, $autores)
{
    $autores = "Leandro Magalhães; Arlete, Maria.";
    //-- retira os nomes dos autores da frase
    //$autores_split = explode("; ", $autores); //-- faz split retirando o "-" e guarda as strings em array
    //$autores = $autores_split[0] . $autores_split[1] . $autores_split[2];
    $autores_split = multiexplode(array(";", ",", "; ", ", ", "."), $autores);  // usa o metodo para separar a string quando aparecem simbolos
    $encontrou_user = false;
    
    for ($i=0; $i < sizeof($autores_split); $i++) {
        if ($autores_split[$i]) {
            echo("</br>" . $autores_split[$i]);
            //-- Verifica se o nome do user está na lista de autores
            if ($autores_split[$i] === $_SESSION['nome']) {
                $encontrou_user = true;
            }
            insertAlunoProjeto($connectDB, $autores_split[$i], $idprojeto);
        }
    }

    //--TODO: Se não encontrar user, adiciona-o!
    insertUserIntoAutorProjeto($connectDB, $idprojeto);
}

//-- Verifica se o autor está registado
//-- Se estiver inser coneção entre o aluno e o projeto
function insertAlunoProjeto($connectDB, $autor, $idprojeto)
{
    $encontrado = false;
    $idutilizador;

    // inicializar prepared statement
    $stmt = $connectDB->prepare("SELECT fk_idutilizador FROM aluno WHERE nome=?");
    
    $stmt->bind_param("s", $autor);
    
    // executar
    $stmt->execute();
    
    // associar os parametros de output
    $stmt->bind_result($r_idutilizador);
    
    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();
    
    // iterar / obter resultados
    $stmt->fetch();
    
    if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
        $idutilizador = $r_idutilizador;
        $encontrado = true;
        echo "</br>(Select Aluno) ID do aluno encontrado: " . $r_idutilizador;
    } else {
        echo "</br>(Select Aluno) Aluno não encontrado.";
        $encontrado = false;
    }
    $stmt->close();

    //-- Se existe ou não user com mesmo id em Alunos
    if ($encontrado) {

        // -- If statement is prepared
        if ($stmt = mysqli_prepare($connectDB, "INSERT INTO aluno_projeto (fk_aluno, fk_projeto) VALUES (?, ?)")) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $idutilizador, $idprojeto);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "</br>(4) Dados inseridos em aluno_projeto com sucesso. -> Aluno: " . $autor;
                $sucesso = true;
            } else {
                echo "</br>(4) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
                $sucesso = false;
            }
        } else {
            echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
            $sucesso = false;
        }
    
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

//-- Se o user não estiver na lista de autores é adicionado
function insertUserIntoAutorProjeto($connectDB, $idprojeto)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO aluno_projeto (fk_aluno, fk_projeto) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'], $idprojeto);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>(4) Dados inseridos em aluno_projeto com sucesso. -> (USER)Aluno: " . $_SESSION['nome'];
            $sucesso = true;
        } else {
            echo "</br>(4) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            $sucesso = false;
            removeProjeto($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
        $sucesso = false;
        removeProjeto($connectDB, $idprojeto);
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//--TODO: fazer metodo que remove ligações em aluno_projeto -> removeAutores()


//-- Obtem as ferramentas com check no formulário
function getFerramentas($connectDB, $idprojeto)
{
    //-- Se receber ferramentas -> ferramentas aqui é um array
    if (!empty($_REQUEST['ferramentas'])) {
        //-- Percorre o array e insere ligação com projeto
        foreach ($_REQUEST['ferramentas'] as $check) {
            echo $check;
            insertFerramentaProjeto($connectDB, $check, $idprojeto);
        }
    }
}

//-- Insere ligação entre ferramenta & projeto -> ferramenta_projeto
function insertFerramentaProjeto($connectDB, $idferramenta, $idprojeto)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO ferramenta_projeto (fk_ferramenta, fk_projeto) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $idferramenta, $idprojeto);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>(4) Dados inseridos em ferramenta_projeto com sucesso. -> Ferramenta: " . $idferramenta;
            $sucesso = true;
        } else {
            echo "</br>(4) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            $sucesso = false;
            removeFerramentas($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
        $sucesso = false;
        removeFerramentas($connectDB, $idprojeto);
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//--TODO: fazer metodo que remove ligações em ferramentas_projeto -> removeFerramentas()
//-- Remove as ligações que possuem um determinado fk_idprojeto
function removeFerramentas($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM ferramenta_projeto WHERE fk_projeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>Ligações em ferramenta_projeto removidas com sucesso.";
    } else {
        $result .= "</br>(2) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//--TODO: Inserir docenteProjeto
function insertDocenteProjeto($connectDB)
{
}

//--TODO: Inserir Palavras_Chave
function insertPalavrasChave($connectDB)
{
}

//--TODO: Inserir Imagem
function insertImagens($connectDB)
{
}

//--TODO: Inserir Video
function insertVideo($connectDB)
{
}

//--TODO: Inserir Ficheiro
function insertFicheiro($connectDB)
{
}


echo("</br>Titulo: " . $_POST['titulo'] . " | ");
echo("</br>Descricao: " . $_POST['descricao'] . " | ");
echo("</br>Autores: " . $_POST['autores'] . " | ");
echo("</br>Tipo: " . $_POST['tipo'] . " | ");
echo("</br>Semestre: " . $_POST['semestre'] . " | ");
echo("</br>UC: " . $_POST['selectUC'] . " | ");
//echo("</br>Fotografia: " . $_FILES["img1"]['tmp_name'] . " | ");
echo("</br>Data: " . $_POST['data'] . " | ");
//echo("</br>PDF: " . $_FILES["ficheiro"]['tmp_name'] . " | ");
echo("</br>Video: " . $_POST['video'] . " | ");
echo("</br>Palavras chave: " . $_POST['palavras-chave'] . " | ");
//echo ("</br>Categorias: " . $_POST['categorias'] . " | ");
// foreach ($_REQUEST['ferramentas'] as $check) {
//     echo("</br>Ferramentas: " . $check . " | ");
// }

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}