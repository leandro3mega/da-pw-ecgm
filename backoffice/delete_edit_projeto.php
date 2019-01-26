<?php
session_start();

require_once("connectdb.php");

if ($_POST['action'] == 'delete_projeto' && isset($_POST['id_projeto'])) {
    //-- Apaga um projeto
    removeProjeto($connectDB);
} elseif ($_POST['action'] == 'change_titulo' && isset($_POST['id_projeto'])) {
    //-- Muda o TÍTULO de um projeto
    changeTituloProjeto($connectDB);
} elseif ($_POST['action'] == 'change_descricao' && isset($_POST['id_projeto'])) {
    //-- Muda a DESCRICAO de um projeto
    changeDescricaoProjeto($connectDB);
} elseif ($_POST['action'] == 'change_autores' && isset($_POST['id_projeto'])) {
    //-- Muda os AUTORES de um projeto
    changeAutoresProjeto($connectDB);
} elseif ($_POST['action'] == 'change_palavraschave' && isset($_POST['id_projeto'])) {
    //-- Muda as PALAVRAS CHAVE de um projeto
    changePalavrasChave($connectDB);
} elseif ($_POST['action'] == 'change_video' && isset($_POST['id_projeto'])) {
    //-- Muda o VIDEO de um projeto
    changeVideoProjeto($connectDB);
} elseif ($_POST['action'] == 'change_tipo' && isset($_POST['id_projeto'])) {
    //-- Muda o TIPO de um projeto
    changeTipoProjeto($connectDB);
} elseif ($_POST['action'] == 'change_anoletivo' && isset($_POST['id_projeto'])) {
    //-- Muda o TIPO de um projeto
    changeAnoLetivoProjeto($connectDB);
} elseif ($_POST['action'] == 'change_data' && isset($_POST['id_projeto'])) {
    //-- Muda o TIPO de um projeto
    changeDataProjeto($connectDB);
} elseif ($_POST['action'] == 'change_semestre_uc' && isset($_POST['id_projeto'])) {
    //-- Muda o TIPO de um projeto
    changeSemestreUCProjeto($connectDB);
} elseif ($_POST['action'] == 'change_ficheiro' && isset($_POST['form_id_projeto'])) {
    //-- Muda o FICHEIRO de um projeto
    changeFicheiroProjeto($connectDB);
} elseif ($_POST['action'] == 'change_remove_ficheiro' && isset($_POST['id_projeto'])) {
    //-- Remove o FICHEIRO de um projeto
    changeRemoveFicheiroProjeto($connectDB);
} elseif ($_POST['action'] == 'change_remove_video' && isset($_POST['id_projeto'])) {
    //-- Remove o VIDEO de um projeto
    changeRemoveVideoProjeto($connectDB);
} elseif ($_POST['action'] == 'add_imagem_projeto' && isset($_POST['form_add_image_id_projeto'])) {
    //-- Adiciona uma IMAGEM a um projeto
    addImageProjeto($connectDB, $_FILES['new_imagem_projeto']);
} elseif ($_POST['action'] == 'remove_image_projeto' && isset($_POST['id_projeto'])) {
    //-- Elimina a IMAGEM de um projeto
    removeImageProjeto($connectDB, $_POST['nome_imagem_projeto'], $_POST['id_projeto']);
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
            echo "Título mudado com sucesso";
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
            echo "Título mudado com sucesso";
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

    $existe = $query = $idprojeto = "";
    $existe = false;
    $idprojeto = (int)trim($_POST['id_projeto']);
    $video_split = multiexplode(array("?v=", "be/"), trim($_POST['video_projeto']));

    if (verificaSeExisteVideo($connectDB, $idprojeto)) {
        echo("</br>Existe video");
        $existe = true;
        $query = "UPDATE video SET nome=? WHERE fk_idprojeto=?";
    } else {
        echo("</br>Não existe video");
        $existe = false;
        $query = "INSERT INTO video (fk_idprojeto, nome) VALUES (?,?)";
    }

    if ($stmt = $connectDB->prepare($query)) {
        // Bind variables to the prepared statement as parameters
        if ($existe) {
            $stmt->bind_param("si", $param_nome, $param_idprojeto);

            $param_nome = $video_split[1];
            $param_idprojeto = $idprojeto;
        } else {
            $stmt->bind_param("is", $param_idprojeto, $param_nome);

            $param_idprojeto = $idprojeto;
            $param_nome = $video_split[1];
        }

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Video inserido/mudado com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Verifica se um PROJETO possui VIDEO
function verificaSeExisteVideo($connectDB, $idprojeto)
{
    $existe = false;
    // inicializar prepared statement
    if ($stmt = $connectDB->prepare("SELECT nome FROM video WHERE fk_idprojeto=?")) {
        $stmt->bind_param("i", $param_idprojeto);

        $param_idprojeto = $idprojeto;
    
        // executar
        $stmt->execute();
    
        // associar os parametros de output
        $stmt->bind_result($r_nome);
    
        // transfere o resultado da última query : obrigatorio para ter num_rows
        $stmt->store_result();
    
        // iterar / obter resultados
        $stmt->fetch();
    
        if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
            // echo("</br>Encontrado ficheiro");
            $existe = true;
        } else {
            // echo("</br>Não encontrou nenhum ficheiro");
            $existe = false;
        }
    }
    
    $stmt->close();

    return $existe;
}

//-- Remove o ficheiro de um projeto
function changeRemoveVideoProjeto($connectDB)
{
    $idprojeto = (int)trim($_POST['id_projeto']);

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM video WHERE fk_idprojeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("i", $param_projeto);

    $param_projeto = $idprojeto;

    // executar
    if ($stmt->execute()) {
        echo "</br>Video removido com sucesso.";
    } else {
        echo "</br>Ocorreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}


//-- Altera o TIPO de um projeto
function changeTipoProjeto($connectDB)
{
    if (empty($_REQUEST['tipo_projeto'])) {
        return;
    }

    $idprojeto = (int)$_POST['id_projeto'];
    
    $sql = "UPDATE projeto SET tipo=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_tipo, $param_idprojeto);

        // Set parameters
        $param_tipo = $_POST['tipo_projeto'];
        $param_idprojeto = $idprojeto;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Tipo mudado com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Altera o ANO LETIVO de um projeto
function changeAnoLetivoProjeto($connectDB)
{
    if (empty($_REQUEST['anoletivo_projeto'])) {
        return;
    }

    $idprojeto = (int)$_POST['id_projeto'];
    
    $sql = "UPDATE projeto SET ano=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_ano, $param_idprojeto);

        // Set parameters
        $param_ano = $_POST['anoletivo_projeto'];
        $param_idprojeto = $idprojeto;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Tipo mudado com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Altera a DATA de um projeto
function changeDataProjeto($connectDB)
{
    if (empty($_REQUEST['data_projeto'])) {
        return;
    }

    $idprojeto = (int)$_POST['id_projeto'];
    
    $sql = "UPDATE projeto SET data=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $param_data, $param_idprojeto);

        $data = $_REQUEST['data_projeto']; //-- data no formato yyyy-mm-dd
        $data_split = explode("-", $data); //-- faz split retirando o "-" e guarda as strings em array
        $data = $data_split[0] . $data_split[1] . $data_split[2];


        // Set parameters
        $param_data = $data;
        $param_idprojeto = $idprojeto;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Data mudada com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Altera a DATA de um projeto
function changeSemestreUCProjeto($connectDB)
{
    if (empty($_REQUEST['semestre_projeto']) || empty($_REQUEST['uc_projeto'])) {
        return;
    }

    $idprojeto = (int)$_POST['id_projeto'];
    
    $sql = "UPDATE projeto SET semestre=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $param_semestre, $param_idprojeto);

        // Set parameters
        $param_semestre = $_POST['semestre_projeto'];
        $param_idprojeto = $idprojeto;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Semestre mudado com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }

    $sql = "UPDATE projeto SET fk_iduc=? WHERE idprojeto=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $param_iduc, $param_idprojeto);

        // Set parameters
        $param_iduc = $_POST['uc_projeto'];
        $param_idprojeto = $idprojeto;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "UC mudada com sucesso";
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

function changeFicheiroProjeto($connectDB)
{
    $mimeExt = array();
    $mimeExt['application/pdf'] = '.pdf';

    $existe = $query = $idprojeto = $tmpFilePath = "";
    $existe = false;
    $idprojeto = $_POST['form_id_projeto'];

    //Get the temp file path
    $tmpFilePath = $_FILES['ficheiro_projeto']['tmp_name'];

    // echo "</br>tempFilePath: " . $tmpFilePath;

    if (verificaSeExisteFicheiro($connectDB, $idprojeto)) {
        echo("</br>Existe ficheiro");
        $existe = true;
        $query = "UPDATE ficheiro SET nome=? WHERE fk_idprojeto=?";
    } else {
        echo("</br>Não existe ficheiro");
        $existe = false;
        $query = "INSERT INTO ficheiro (fk_idprojeto, nome) VALUES (?,?)";
    }

    echo("</br>Query: " . $query);
    
    if (isset($_FILES["ficheiro_projeto"]) && $tmpFilePath != "") {
        if ($stmt = $connectDB->prepare($query)) {

            $diretorio = "images/projetos/ficheiros/";
            $type = mime_content_type($_FILES['ficheiro_projeto']['tmp_name']);
            $id_ficheiro = $idprojeto . $mimeExt[$_FILES["ficheiro_projeto"]["type"]]; //Get image extension
            $user_foto_dir = $diretorio . $id_ficheiro; //Path file

            // Bind variables to the prepared statement as parameters
            if($existe){
                $stmt->bind_param("ss", $param_nome, $param_idprojeto);

                $param_nome = $id_ficheiro;
                $param_idprojeto = $idprojeto;
            } else{
                $stmt->bind_param("ss", $param_idprojeto, $param_nome);

                $param_idprojeto = $idprojeto;
                $param_nome = $id_ficheiro;        
            }
            
            move_uploaded_file($_FILES["ficheiro_projeto"]["tmp_name"], $user_foto_dir);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                echo "</br>Ficheiro criado/alterado";
                header("location:editar-projeto.php");
                
            } else {
                echo "</br>Something went wrong. Please try again later.";
            }
        } else{
            header("location:editar-projeto.php");
            return;
        }
        // Close statement
        $stmt->close();
    }
}

//-- Verifica se existe ficheiro
function verificaSeExisteFicheiro($connectDB, $idprojeto)
{
    $existe = false;
    // inicializar prepared statement
    if ($stmt = $connectDB->prepare("SELECT nome FROM ficheiro WHERE fk_idprojeto=?")) {
        $stmt->bind_param("i", $param_idprojeto);

        $param_idprojeto = $idprojeto;
    
        // executar
        $stmt->execute();
    
        // associar os parametros de output
        $stmt->bind_result($r_nome);
    
        // transfere o resultado da última query : obrigatorio para ter num_rows
        $stmt->store_result();
    
        // iterar / obter resultados
        $stmt->fetch();
    
        if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
            // echo("</br>Encontrado ficheiro");
            $existe = true;
        } else {
            // echo("</br>Não encontrou nenhum ficheiro");
            $existe = false;
        }
    }
    $stmt->close();

    return $existe;
}

//-- Remove o ficheiro de um projeto
function changeRemoveFicheiroProjeto($connectDB)
{
    $idprojeto = $_POST['id_projeto'];
    $diretorio = "images/projetos/ficheiros/";

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM ficheiro WHERE fk_idprojeto=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("i", $param_projeto);

    $param_projeto = $idprojeto;

    // executar
    if ($stmt->execute()) {
        echo "</br>Ficheiro removido com sucesso.";
        unlink($diretorio . $idprojeto . ".pdf");

    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}

//-- Adiciona uma nova imagem ao projeto
function addImageProjeto($connectDB, $imagem){
    $mimeExt = array();
    $mimeExt['image/jpeg'] = '.jpg';
    $mimeExt['image/pjpeg'] = '.jpg';
    $mimeExt['image/png'] = '.png';

    $existe = $query = $idprojeto = $tmpFilePath = "";
    $idprojeto = $_POST['form_add_image_id_projeto'];

    //Get the temp file path
    $tmpFilePath = $imagem['tmp_name'];

    // echo "</br>tempFilePath: " . $tmpFilePath;

    if (isset($imagem) && $tmpFilePath != "") {
        $query = "INSERT INTO imagem (fk_idprojeto, nome) VALUES (?,?)";
        if ($stmt = $connectDB->prepare($query)) {
            $diretorio = "images/projetos/imagens/";
            $type = mime_content_type($imagem['tmp_name']);
            $id_imagem = $idprojeto . "_" . md5(uniqid(time())) . $mimeExt[$imagem["type"]]; //Get image extension
            $user_foto_dir = $diretorio . $id_imagem; //Path file

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("is", $param_idprojeto, $param_nome);

            $param_idprojeto = $idprojeto;
            $param_nome = $id_imagem;
            
            move_uploaded_file($imagem["tmp_name"], $user_foto_dir);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                echo "</br>Imagens inserida";
                header("location:editar-projeto.php");
            } else {
                echo "</br>Something went wrong. Please try again later.";
            }
        } else {
            header("location:editar-projeto.php");
            return;
        }
        // Close statement
        $stmt->close();
    }

}

//-- Remove Imagem de um projeto
function removeImageProjeto($connectDB, $imagem_nome, $idprojeto){

    $diretorio = "images/projetos/imagens/";

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM imagem WHERE nome=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("s", $param_nome);

    $param_nome = $imagem_nome;

    // executar
    if ($stmt->execute()) {
        echo "</br>Imagem removida com sucesso.";
        unlink($diretorio . $imagem_nome);

    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }

    $stmt->close();
}

//--TODO: Alterar Imagem -> remover a imagem (usar removeImageProjeto) -> adicionar nova imagem (user addImagemProjeto)
function editImagemProjeto($connectDB){
    
}

// function addImageProjeto($connectDB, $imagem){
//     //--TODO: Verificar se atingiu o número máximo de 5 imagens

//     $mimeExt = array();
//     $mimeExt['image/jpeg'] = '.jpg';
//     $mimeExt['image/pjpeg'] = '.jpg';
//     $mimeExt['image/png'] = '.png';

//     $existe = $query = $idprojeto = $tmpFilePath = "";
//     $idprojeto = $_POST['form_add_image_id_projeto'];

//     // echo("ID do Projeto: " . $idprojeto);

//     //Get the temp file path
//     $tmpFilePath = $_FILES['new_imagem_projeto']['tmp_name'];

//     // echo "</br>tempFilePath: " . $tmpFilePath;

//     if (isset($_FILES["new_imagem_projeto"]) && $tmpFilePath != "") {
//         $query = "INSERT INTO imagem (fk_idprojeto, nome) VALUES (?,?)";
//         if ($stmt = $connectDB->prepare($query)) {
//             $diretorio = "images/projetos/imagens/";
//             $type = mime_content_type($_FILES['new_imagem_projeto']['tmp_name']);
//             $id_imagem = $idprojeto . "_" . md5(uniqid(time())) . $mimeExt[$_FILES["new_imagem_projeto"]["type"]]; //Get image extension
//             $user_foto_dir = $diretorio . $id_imagem; //Path file

//             // Bind variables to the prepared statement as parameters
//             $stmt->bind_param("is", $param_idprojeto, $param_nome);

//             $param_idprojeto = $idprojeto;
//             $param_nome = $id_imagem;
            
//             move_uploaded_file($_FILES["new_imagem_projeto"]["tmp_name"], $user_foto_dir);
            
//             // Attempt to execute the prepared statement
//             if ($stmt->execute()) {
//                 echo "</br>Imagens inserida";
//                 header("location:editar-projeto.php");
//             } else {
//                 echo "</br>Something went wrong. Please try again later.";
//             }
//         } else {
//             header("location:editar-projeto.php");
//             return;
//         }
//         // Close statement
//         $stmt->close();
//     }

// }



function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}