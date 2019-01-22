<?php
session_start();

require_once("connectdb.php");

if ($_POST['action'] == 'delete_projeto' && isset($_POST['id_projeto'])) {
    //-- Apaga um projeto
    removeProjeto($connectDB);
} elseif ($_POST['action'] == 'change_titulo' && isset($_POST['id_projeto'])) {
    //-- Muda o titulo de um projeto
    changeTituloProjeto($connectDB);
} elseif ($_POST['action'] == 'change_descricao' && isset($_POST['id_projeto'])) {
    //-- Muda a descricao de um projeto
    changeDescricaoProjeto($connectDB);
} elseif ($_POST['action'] == 'change_autores' && isset($_POST['id_projeto'])) {
    //-- Muda os autores de um projeto
    changeAutoresProjeto($connectDB);
} elseif ($_POST['action'] == 'change_palavraschave' && isset($_POST['id_projeto'])) {
    //-- Muda os autores de um projeto
    changePalavrasChave($connectDB);
} elseif ($_POST['action'] == 'change_video' && isset($_POST['id_projeto'])) {
    //-- Muda os autores de um projeto
    changeVideoProjeto($connectDB);
}


//-- Remove um projeto ao receber o id deste por post
function removeProjeto($connectDB)
{
    $idprojeto = $_POST['id_projeto'];

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM projeto WHERE idprojeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $param_projeto);

    $param_projeto = $idprojeto;

    // executar
    if ($stmt->execute()) {
        echo "</br>Projeto removido com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//-- Altera o título de um projeto
function changeTituloProjeto($connectDB)
{
    $sql = "UPDATE projeto SET titulo=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $param_titulo, $param_idprojeto);

        // Set parameters
        $param_titulo = trim($_POST['titulo_projeto']);
        $param_idprojeto = trim($_POST['id_projeto']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            echo "Título mudado com sucesso";
        // header("location: editar-projeto.php");
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }

    $stmt->close();
}

//-- Altera a descrição de um projeto
function changeDescricaoProjeto($connectDB)
{
    $sql = "UPDATE projeto SET descricao=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $param_descricao, $param_idprojeto);

        // Set parameters
        $param_descricao = trim($_POST['descricao_projeto']);
        $param_idprojeto = trim($_POST['id_projeto']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            echo "Título mudado com sucesso";
        // header("location: editar-projeto.php");
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }

    $stmt->close();
}

//-- Altera os autores de um projeto
function changeAutoresProjeto($connectDB)
{
    if (empty($_REQUEST['autores_projeto'])) {
        return;
    }

    //--remove conections between all users and the project
    removeConectionsAlunoProjeto($connectDB);

    $autores = trim($_POST['autores_projeto']);
    $idprojeto = trim($_POST['id_projeto']);
    $autores_org = "";
    $enc = false;
    

    //-- Separa os autores e guarda em um array
    $autores_split = multiexplode(array("; ", ";", " ;", ",", ".", ", ", ". ", " ,", " ."), $autores);  // usa o metodo para separar a string quando aparecem simbolos
           
    //-- Verifica se o user está na lista de autores
    for ($i=0; $i < sizeof($autores_split); $i++) {
        if ($autores_split[$i] == $_SESSION['nome']) {
            $enc = true;
        }
    }
    
    //-- Organiza a lista de autores
    for ($i=0; $i <= sizeof($autores_split); $i++) {
        if (isset($autores_split[$i]) && !empty($autores_split[$i])) {
            // echo("</br>Autor: " . $autores_split[$i]);
            if (isset($autores_split[$i+1]) && !empty($autores_split[$i+1])) {
                $autores_org .= trim($autores_split[$i]) . "; ";    // se tiver seguinte
            } else {
                $autores_org .= trim($autores_split[$i]) . ".";     // se não tiver seguinte
            }
        }
    }

    $autores = $autores_org;

    //-- Se o user não estiver na lista adiciona-o
    if (!$enc) {
        $temp = $autores;
        $autores = $_SESSION['nome'] . "; ";
        $autores .= $temp;
    }

    echo("</br>Autores: " . $autores);

    if ($stmt = $connectDB->prepare("UPDATE projeto SET autores=? WHERE idprojeto=?")) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $param_autores, $param_idprojeto);

        // Set parameters
        $param_autores = trim($autores);
        $param_idprojeto = trim($_POST['id_projeto']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Autores mudado com sucesso";
            prepareConectionAutorProjeto($connectDB, $idprojeto, $autores);
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }

    $stmt->close();
}

//-- Faz a ligação entre o aluno e os autores
function prepareConectionAutorProjeto($connectDB, $idprojeto, $autores)
{
    //-- retira os nomes dos autores da string
    $autores_array = multiexplode(array(";", ",", ".", "; ", ", ", ". ", " ;", " ,", " ."), $autores);  // usa o metodo para separar a string quando aparecem simbolos
    
    for ($i=0; $i < sizeof($autores_array); $i++) {
        if ($autores_array[$i]) {
            // echo("</br>" . $autores_split[$i]);
            echo("</br>" . $autores_array[$i]);
            insertConectionAutorProjeto($connectDB, $autores_array[$i], $idprojeto);
        }
    }
}

//-- Verifica se o autor está registado
//-- Se estiver insere coneção entre o aluno e o projeto
function insertConectionAutorProjeto($connectDB, $autor, $idprojeto)
{
    $encontrado = false;
    $idutilizador;

    // inicializar prepared statement
    $stmt = $connectDB->prepare("SELECT fk_idutilizador FROM aluno WHERE nome=?");
    
    $stmt->bind_param("s", $param_autor);
    
    $param_autor = trim($autor);

    // executar
    $stmt->execute();
    
    // associar os parametros de output
    $stmt->bind_result($r_idutilizador);
    
    // transfere o resultado da última query : obrigatorio para ter num_rows
    $stmt->store_result();
    
    // iterar / obter resultados
    $stmt->fetch();
    
    if ($stmt->num_rows == 1) { // seleciona o resultado da base de dados
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

//-- Remove as ligações entre aluno e o projeto
function removeConectionsAlunoProjeto($connectDB)
{
    $idprojeto = $_POST['id_projeto'];

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM aluno_projeto WHERE fk_projeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $param_projeto);

    $param_projeto = $idprojeto;

    // executar
    if ($stmt->execute()) {
        echo "</br>Ligações removidas com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

function changePalavrasChave($connectDB)
{
    if (empty($_REQUEST['palavraschave_projeto'])) {
        return;
    }

    $palavras_chave_org ="";
    $idprojeto = (int)$_POST['id_projeto'];

    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "UPDATE palavra_chave SET palavra=? WHERE fk_idprojeto=?")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_palavras_chave, $param_idprojeto);

        $param_idprojeto = $idprojeto;

        $palavras_chave_split = multiexplode(array("; ", ";", " ;", ",", ".", ", ", ". ", " ,", " ."), $_REQUEST['palavraschave_projeto']);  // usa o metodo para separar a string quando aparecem simbolos

        for ($i=0; $i < sizeof($palavras_chave_split); $i++) {
            if (isset($palavras_chave_split[$i]) && !empty($palavras_chave_split[$i])) {
                //echo("</br>(insertPalavrasChave) Palavra-chave: " . $palavras_chave_split[$i]);

                if (isset($palavras_chave_split[$i+1]) && !empty($palavras_chave_split[$i+1])) {
                    $palavras_chave_org .= trim($palavras_chave_split[$i]) . "; ";    // se tiver seguinte
                } else {
                    $palavras_chave_org .= trim($palavras_chave_split[$i]) . ".";     // se não tiver seguinte
                }
            }
        }

        echo("Palavras-chave" . $palavras_chave_org);

        $param_palavras_chave = $palavras_chave_org;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Palavras chave mudadas com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    } else {
        echo "</br>(4) Ocurreu um erro: Não conseguiu preparar a query query:" . mysqli_error($connectDB) . " . ";
    }
        
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- Altera o video de um projeto
function changeVideoProjeto($connectDB)
{
    if (empty($_REQUEST['video_projeto'])) {
        return;
    }

    $idprojeto = (int)$_POST['id_projeto'];
    $video_split = multiexplode(array("?v=", "be/"), trim($_POST['video_projeto']));

    $sql = "UPDATE video SET nome=? WHERE fk_idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_nome, $param_idprojeto);

        // Set parameters
        $param_nome = $video_split[1];
        $param_idprojeto = trim($_POST['id_projeto']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            echo "Video mudado com sucesso";
        // header("location: editar-projeto.php");
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }

    $stmt->close();
}













function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}