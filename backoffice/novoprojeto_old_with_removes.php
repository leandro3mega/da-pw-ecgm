<?php
session_start();

require_once("connectdb.php");

//--Variaveis
/*
$image = $_FILES["img1"]['tmp_name'];
$ficheiro = $_FILES["ficheiro"]['tmp_name'];
$video = $_POST['video'];

*/


if (isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['autores']) && isset($_POST['tipo']) &&
    isset($_POST['semestre']) && isset($_POST['selectUC']) && isset($_POST['data'])) {

    // $titulo = $_POST['titulo'];
    // $descricao = $_POST['descricao'];
    // $autores = $_POST['autores'];
    // $data = $_POST['data']; //-- data no formato yyyy-mm-dd
    // $data_split = explode("-", $data); //-- faz split retirando o "-" e guarda as strings em array
    // $data = $data_split[0] . $data_split[1] . $data_split[2];
 
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
    
    
    insertProjeto($connectDB, $sucesso, $idprojeto, $fk_iduc, $titulo, $descricao, $autores, $data, $ano, $semestre, $tipo);

    if ($sucesso) {
        obterIdProjeto($connectDB, $sucesso, $idprojeto, $titulo, $descricao, $autores, $data);
    }
    if ($sucesso) {
        getAluno($connectDB, $sucesso, $idprojeto, $autores);
    }
    if ($sucesso) {
        //$idprojeto = 34;
        getFerramentas($connectDB, $sucesso, $idprojeto);
    }
    if ($sucesso) {
        getDocente($connectDB, $sucesso, $idprojeto, $fk_iduc);
    }
    if ($sucesso) {
        insertPalavrasChave($connectDB, $sucesso, $idprojeto);
    }
    if ($sucesso) {
        /// Variveis teste | Only for testing purposes ///
        // $idprojeto = 25;
        // $videoid = "adAD95DASasd9";
        insertVideo($connectDB, $sucesso, $idprojeto);
    }
    if ($sucesso) {
        insertImagens($connectDB, $sucesso, $idprojeto);
    }
    if ($sucesso) {
        insertFicheiro($connectDB, $sucesso, $idprojeto);
    }
}

//-- Insere um projeto com determinadas informações
function insertProjeto($connectDB, &$sucesso, &$idprojeto, $fk_iduc, $titulo, $descricao, &$autores, $data, $ano, $semestre, $tipo)
{
    $createUserQuery = "INSERT INTO projeto (fk_iduc, titulo, descricao, autores, data, ano, semestre, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $autores_org = "";

    $autores_split = multiexplode(array(";", ",", ".", "; ", ", ", ". ", " ;", " ,", " ."), $autores);  // usa o metodo para separar a string quando aparecem simbolos
            
    for ($i=0; $i < sizeof($autores_split); $i++) {
        if (isset($autores_split[$i]) && $autores_split[$i] != ".") {
            echo("Autor: " . $autores_split[$i]);
            if (isset($autores_split[$i+1])) {
                $autores_org .= $autores_split[$i] . "; ";    // se tiver seguinte
            } else {
                $autores_org .= $autores_split[$i] . ".";     // se não tiver seguinte
            }
        }
    }
    $autores = $autores_org;

    //-- Se existir projeto com o mesmo nome, não insere
    if (!seExisteProjeto($connectDB, $sucesso, $titulo, $descricao, $autores, $data)) {
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
function seExisteProjeto($connectDB, &$sucesso, $titulo, $descricao, $autores, $data)
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
        $stmt->close();
        echo("(Select Projetos) Já se encontra um projeto igual no site: " . $r_idprojeto);
        $sucesso = false;
        return true;
    } else {
        $stmt->close();
        echo("(Select Projetos) Não se encontra um projeto igual no site: ");
        $sucesso = true;
        return false;
    }
}

//-- Select do projeto inserido para obter o id do mesmo
function obterIdProjeto($connectDB, &$sucesso, &$idprojeto, $titulo, $descricao, $autores, $data)
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
        $sucesso = true;
    } else {
        echo "</br>(obterIdProjeto) Não encontrou projeto.";
        $sucesso = false;
    }
    $stmt->close();
}

//-- Remove um projeto com um determinado id
function removeProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM projeto WHERE idprojeto=?");
    
    if (false === $stmt) {
        echo "</br>(removeProjeto) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removeProjeto) Projeto removido com sucesso.";
    } else {
        echo "</br>(removeProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//-- Faz a ligação entre o aluno e os autores
function getAluno($connectDB, &$sucesso, $idprojeto, $autores)
{
    //$autores = "Leandro Magalhães; Arlete, Maria.";
    //-- retira os nomes dos autores da frase
    //$autores_split = explode("; ", $autores); //-- faz split retirando o "-" e guarda as strings em array
    //$autores = $autores_split[0] . $autores_split[1] . $autores_split[2];
    $autores_split = multiexplode(array(";", ",", ".", "; ", ", ", ". ", " ;", " ,", " ."), $autores);  // usa o metodo para separar a string quando aparecem simbolos
    $encontrou_user = false;
    
    for ($i=0; $i < sizeof($autores_split); $i++) {
        if ($autores_split[$i]) {
            echo("</br>" . $autores_split[$i]);
            //-- Verifica se o nome do user está na lista de autores
            if ($autores_split[$i] === $_SESSION['nome']) {
                $encontrou_user = true;
            }
            insertAlunoProjeto($connectDB, $sucesso, $autores_split[$i], $idprojeto);
        }
    }

    //--TODO: Se não encontrar user, adiciona-o!
    if (!$encontrou_user) {
        insertUserIntoAutorProjeto($connectDB, $sucesso, $idprojeto);
    }
}

//-- Verifica se o autor está registado
//-- Se estiver inser coneção entre o aluno e o projeto
function insertAlunoProjeto($connectDB, &$sucesso, $autor, $idprojeto)
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
                echo "</br>(insertUserIntoAutorProjeto) Dados inseridos em aluno_projeto com sucesso. -> Aluno: " . $autor;
                $sucesso = true;
            } else {
                echo "</br>(insertUserIntoAutorProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
                $sucesso = false;
            }
        } else {
            echo "</br>(insertUserIntoAutorProjeto) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
            $sucesso = false;
        }
    
        // Close statement
        mysqli_stmt_close($stmt);
    }
}

//-- Se o user não estiver na lista de autores é adicionado
function insertUserIntoAutorProjeto($connectDB, &$sucesso, $idprojeto)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO aluno_projeto (fk_aluno, fk_projeto) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'], $idprojeto);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>(insertUserIntoAutorProjeto) Dados inseridos em aluno_projeto com sucesso. -> (USER)Aluno: " . $_SESSION['nome'];
            $sucesso = true;
        } else {
            echo "</br>(insertUserIntoAutorProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            $sucesso = false;

            /* When Fail: Remove previous content */
            removeProjeto($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(insertUserIntoAutorProjeto) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
        $sucesso = false;

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- Remove as ligações que possuem um determinado fk_idprojeto
function removeAlunoProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM aluno_projeto WHERE fk_projeto=?");
    
    if (false === $stmt) {
        echo "(removeAlunoProjeto) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removeAlunoProjeto) Ligações em aluno_projeto removidas com sucesso.";
    } else {
        echo "</br>(removeAlunoProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}


//-- Obtem as ferramentas com check no formulário
function getFerramentas($connectDB, &$sucesso, $idprojeto)
{
    //-- Se receber ferramentas -> ferramentas aqui é um array
    if (!empty($_REQUEST['cb'])) {
        //-- Percorre o array e insere ligação com projeto
        foreach ($_REQUEST['cb'] as $check) {
            echo "</br>(getFerramentas) Ferramenta id: " . $check;
            insertFerramentaProjeto($connectDB, $sucesso, $check, $idprojeto);
        }
    }
}

//-- Insere ligação entre ferramenta & projeto -> ferramenta_projeto
function insertFerramentaProjeto($connectDB, &$sucesso, $idferramenta, $idprojeto)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO ferramenta_projeto (fk_ferramenta, fk_projeto) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $idferramenta, $idprojeto);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>(insertFerramentaProjeto) Dados inseridos em ferramenta_projeto com sucesso. -> Ferramenta: " . $idferramenta;
            $sucesso = true;
        } else {
            echo "</br>(insertFerramentaProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            $sucesso = false;

            /* When Fail: Remove previous content */
            removeProjeto($connectDB, $idprojeto);
            removeAlunoProjeto($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(insertFerramentaProjeto) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
        $sucesso = false;

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
        removeAlunoProjeto($connectDB, $idprojeto);
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- Remove as ligações que possuem um determinado fk_idprojeto
function removeFerramentas($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM ferramenta_projeto WHERE fk_projeto=?");
    
    if (false === $stmt) {
        echo "(removeFerramentas) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removeFerramentas) Ligações em ferramenta_projeto removidas com sucesso.";
    } else {
        echo "</br>(removeFerramentas) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//-- Obtem o id de um docente que leciona uma determinada UC
function getDocente($connectDB, &$sucesso, $idprojeto, $iduc)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("SELECT fk_idutilizador FROM unidade_curricular WHERE idunidade_curricular=?");

    $stmt->bind_param("s", $iduc);

    // executar
    $stmt->execute();

    // associar os parametros de output
    $stmt->bind_result($r_idutilizador);

    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();

    // iterar / obter resultados
    $stmt->fetch();

    if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
        echo("</br>(getDocente) Encontrado docente: " . $r_idutilizador);
        $stmt->close();
        insertDocenteProjeto($connectDB, $sucesso, $r_idutilizador, $idprojeto);
    } else {
        echo("</br>(getDocente) Não encontrou nenhum docente");
        $stmt->close();

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
        removeAlunoProjeto($connectDB, $idprojeto);
        removeFerramentas($connectDB, $idprojeto);
    }
}

//-- Insere ligação entre o docente e um Projeto
function insertDocenteProjeto($connectDB, &$sucesso, $iddocente, $idprojeto)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO docente_projeto (fk_docente, fk_projeto) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $iddocente, $idprojeto);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>(insertDocenteProjeto) Dados inseridos em docente_projeto com sucesso. -> Docente: " . $iddocente;
            $sucesso = true;
        } else {
            echo "</br>(insertDocenteProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            $sucesso = false;

            /* When Fail: Remove previous content */
            removeProjeto($connectDB, $idprojeto);
            removeAlunoProjeto($connectDB, $idprojeto);
            removeFerramentas($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(insertDocenteProjeto) Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
        $sucesso = false;

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
        removeAlunoProjeto($connectDB, $idprojeto);
        removeFerramentas($connectDB, $idprojeto);
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- remove ligações em docente_projeto com o id do projeto
function removeDocenteProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM docente_projeto WHERE fk_projeto=?");
    
    if (false === $stmt) {
        echo "</br>(removeDocenteProjeto) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removeDocenteProjeto) Ligações em ferramenta_projeto removidas com sucesso.";
    } else {
        echo "</br>(removeDocenteProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}


//-- Insere palavras chave para um determinado id de projeto
function insertPalavrasChave($connectDB, &$sucesso, $idprojeto)
{
    if (empty($_REQUEST['palavras-chave'])) {
        return;
    }

    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO palavra_chave (fk_idprojeto, palavra) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $idprojeto, $palavras_chave_org);

        $palavras_chave = $_REQUEST['palavras-chave'];


        $palavras_chave_split = multiexplode(array(";", ",", "; ", ", ", "."), $palavras_chave);  // usa o metodo para separar a string quando aparecem simbolos

        for ($i=0; $i < sizeof($palavras_chave_split); $i++) {
            if ($palavras_chave_split[$i]) {
                //echo("</br>(insertPalavrasChave) Palavra-chave: " . $palavras_chave_split[$i]);

                if (isset($palavras_chave_split[$i+1])) {
                    $palavras_chave_org .= $palavras_chave_split[$i] . "; ";    // se tiver seguinte
                } else {
                    $palavras_chave_org .= $palavras_chave_split[$i] . ".";     // se não tiver seguinte
                }
            }
        }

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo '</br>(insertPalavrasChave) Dados inseridos em palavra_chave com sucesso. String: ' . $palavras_chave_org;
            $sucesso = true;
        } else {
            echo "</br>(insertPalavrasChave) Ocurreu um erro: Não conseguiu executar a query:" . mysqli_error($connectDB) . ". ";
            $sucesso = false;

            /* When Fail: Remove previous content */
            removeProjeto($connectDB, $idprojeto);
            removeAlunoProjeto($connectDB, $idprojeto);
            removeFerramentas($connectDB, $idprojeto);
            removeDocenteProjeto($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query:" . mysqli_error($connectDB) . " . ";
        $sucesso = false;

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
        removeAlunoProjeto($connectDB, $idprojeto);
        removeFerramentas($connectDB, $idprojeto);
        removeDocenteProjeto($connectDB, $idprojeto);
    }
        
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- remove as palavras chave com ligação ao id do projeto
function removePalavraChaveProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM palavra_chave WHERE fk_idprojeto=?");
    
    if (false === $stmt) {
        echo "</br>(removePalavraChaveProjeto) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removePalavraChaveProjeto) Palavras-chave removidas com sucesso.";
    } else {
        echo "</br>(removePalavraChaveProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}


//-- insere youtube video id com ligação ao projeto
function insertVideo($connectDB, &$sucesso, $idprojeto)
{
    if (empty($_REQUEST['video'])) {
        return;
    }
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO video (fk_idprojeto, nome) VALUES (?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $idprojeto, $video);

        $video = $_REQUEST['video'];
        //$video_split = explode("v=", $video);
        $video_split = multiexplode(array("?v=", "be/"), $video);
        ;
        $video = $video_split[1];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo '</br>(insertVideo) Dados inseridos em video com sucesso. Video Youtube ID: ' . $video;
            $sucesso = true;
        } else {
            echo "</br>(insertVideo) Ocurreu um erro: Não conseguiu executar a query:" . mysqli_error($connectDB) . ". ";
            $sucesso = false;

            /* When Fail: Remove previous content */
            removeProjeto($connectDB, $idprojeto);
            removeAlunoProjeto($connectDB, $idprojeto);
            removeFerramentas($connectDB, $idprojeto);
            removeDocenteProjeto($connectDB, $idprojeto);
            removePalavraChaveProjeto($connectDB, $idprojeto);
        }
    } else {
        echo "</br>(insertVideo) Ocurreu um erro: Não conseguiu preparar a query query:" . mysqli_error($connectDB) . " . ";
        $sucesso = false;

        /* When Fail: Remove previous content */
        removeProjeto($connectDB, $idprojeto);
        removeAlunoProjeto($connectDB, $idprojeto);
        removeFerramentas($connectDB, $idprojeto);
        removeDocenteProjeto($connectDB, $idprojeto);
        removePalavraChaveProjeto($connectDB, $idprojeto);
    }
        
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- remove o video com ligação ao id do projeto
function removeVideoProjeto($connectDB, $idprojeto)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM video WHERE fk_idprojeto=?");
    
    if (false === $stmt) {
        echo "</br>(removeVideoProjeto) Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $idprojeto);

    // executar
    if ($stmt->execute()) {
        echo "</br>(removeVideoProjeto) Video removido com sucesso.";
    } else {
        echo "</br>(removeVideoProjeto) Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}


//--TODO: Inserir Imagem
function insertImagens($connectDB, &$sucesso, $idprojeto)
{
    if (count($_FILES["image"]['name']) > 0) {
        echo("</br> insertImagens não desenvolvida!");
    }
}

//--TODO: Inserir Ficheiro
function insertFicheiro($connectDB, &$sucesso, $idprojeto)
{
    if (isset($_FILES["ficheiro"])) {
        echo("</br> insertFicheiro não desenvolvida!");
    }
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
foreach ($_REQUEST['cb'] as $check) {
    echo("</br>Ferramentas: " . $check . " | ");
}

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}