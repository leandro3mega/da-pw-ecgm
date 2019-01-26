<?php
session_start();

require_once("connectdb.php");

if ($_POST['action'] == 'delete_uc') {
    //-- Apaga a UC da DB
    deleteUC($connectDB);
} elseif ($_POST['action'] == 'add_uc') {
    //-- Insere uma UC na DB
    insertUC($connectDB);
} elseif ($_POST['action'] == 'edit_uc') {
    //-- Edita a UC da DB
    updatetUC($connectDB);
} elseif ($_POST['action'] == 'delete_aluno') {
    //-- Apaga o aluno da DB
    deleteAluno($connectDB);
} elseif ($_POST['action'] == 'delete_docente') {
    //-- Apaga o docente da DB
    deleteDocente($connectDB);
} elseif ($_POST['action'] == 'set_docente_to_uc') {
    //-- Insere Ligação docente-uc
    setDocenteToUC($connectDB);
} elseif ($_POST['action'] == 'remove_docente_uc_conn') {
    //-- remove Ligação docente-uc
    removeDocenteUcConn($connectDB);
}

//-- Apaga uma UC da DB
function deleteUC($connectDB)
{
    $idprojeto = $_POST['id_projeto'];

    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM unidade_curricular WHERE idunidade_curricular=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("i", $param_iduc);

    $param_iduc = (int)trim($_POST['id_uc']);

    // executar
    if ($stmt->execute()) {
        echo "</br>UC removida com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}

//-- Insere uma nova UC na DB
function insertUC($connectDB)
{
    $nome_uc = trim($_POST['add_nome_uc']);
    // echo ("ID: " . (int)(trim($_POST['edit_uc_id'])) . " | Nome: " . trim($_POST['edit_nome_uc']));

    if (verificaExisteNomeUC($connectDB, $nome_uc)) {
        $msg = "A unidade curricular "  . $nome_uc . " já existe!";
        echo '<script language="javascript">';
        echo 'alert("' . $msg . '");';
        echo 'window.location.replace("gerir-ucs.php");';
        echo '</script>';

        exit;
    }

    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO unidade_curricular (nome, ano_curricular, semestre, descricao) VALUES (?, ?, ?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "siis", $param_nome, $param_ano, $param_semestre, $param_descricao);

        $param_nome = $nome_uc;
        $param_ano = (int)trim($_POST['add_ano_uc']);
        $param_semestre = (int)trim($_POST['add_semestre_uc']);
        $param_descricao = trim($_POST['add_descricao_uc']);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>Unidade Curricular inserida com sucesso.";
            header("location:gerir-ucs.php");
        } else {
            echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
            header("location:gerir-ucs.php");
        }
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- Altera os Dados de uma UC
function updatetUC($connectDB)
{
    if (empty($_REQUEST['edit_uc_id'])) {
        return;
    }

    $nome_uc = trim($_POST['edit_nome_uc']);
    $id_uc = trim($_POST['edit_uc_id']);
    // echo("</br>ID: " . (int)(trim($_POST['edit_uc_id'])) . " | Nome: " . trim($_POST['edit_nome_uc']));

    if (verificaExisteNomeUC($connectDB, $nome_uc)) {
        $msg = "A unidade curricular "  . $nome_uc . " já existe!";
        echo '<script language="javascript">';
        echo 'alert("' . $msg . '");';
        echo 'window.location.replace("gerir-ucs.php");';
        echo '</script>';

        exit;
    }

    $sql = "UPDATE unidade_curricular SET nome=?, ano_curricular=?, semestre=?, descricao=? WHERE idunidade_curricular=?";

    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("siisi", $param_nome, $param_ano, $param_semestre, $param_descricao, $param_iduc);

        // Set parameters
        $param_nome = $nome_uc;
        $param_ano = (int)trim($_POST['edit_ano_uc']);
        $param_semestre = (int)trim($_POST['edit_semestre_uc']);
        $param_descricao = trim($_POST['edit_descricao_uc']);
        $param_iduc = (int)$id_uc;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "UC mudada com sucesso";
            header("location:gerir-ucs.php");
        } else {
            echo "</br>Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
}

//-- Verifica se já existe uma uc com um determinado nome na DB
function verificaExisteNomeUC($connectDB, $nome)
{
    // query à base de dados
    $sql = "SELECT idunidade_curricular FROM unidade_curricular WHERE nome=?";
    
    // inicializar prepared statement
    $stmt = $connectDB->prepare($sql);
    
    // md5 para desincriptar a password
    //$password = md5($pass_old);
    $stmt->bind_param("s", $param_nome);
    
    $param_nome = $nome;
    
    // executar
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            while ($row = $result->fetch_assoc()) {
                if (isset($_POST['edit_uc_id']) && (int)$_POST['edit_uc_id'] == $row['idunidade_curricular']) {
                    $stmt->close();
                    // echo("</br>O nome é o mesmo da uc a alterar!");
                    return false;
                } else {
                    $stmt->close();
                    // echo("</br>Já se encontra uma uc com o mesmo nome no site.");
                    return true;
                }
            }
        } else {
            $stmt->close();
            // echo("</br>Não se encontra um projeto igual no site");
            return false;
        }
    }
    
    
    

    // if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
    //     $stmt->close();
    //     if (isset($_POST['edit_uc_id']) && (int)$_POST['edit_uc_id'] == $r_id) {
    //         return false;
    //         echo("O nome é o mesmo da uc a alterar!");
    //     } else {
    //         echo("Já se encontra uma uc com o mesmo nome no site.");
    //         return true;
    //     }
    // } else {
    //     $stmt->close();
    //     echo("Não se encontra um projeto igual no site");
    //     return false;
    // }
}

//-- Apaga Aluno da DB
function deleteAluno($connectDB)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM utilizador WHERE idutilizador=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("i", $param_iduser);

    $param_iduser = (int)trim($_POST['id_aluno']);

    // executar
    if ($stmt->execute()) {
        echo "</br>Aluno removido com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}

//-- Apaga Docente da DB
function deleteDocente($connectDB)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM utilizador WHERE idutilizador=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("i", $param_iduser);

    $param_iduser = (int)trim($_POST['id_docente']);

    // executar
    if ($stmt->execute()) {
        echo "</br>Docente removido com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}

//-- Faz ligação entre Docente e UC para o atual ano letivo
function setDocenteToUC($connectDB)
{
    // -- If statement is prepared
    if ($stmt = mysqli_prepare($connectDB, "INSERT INTO docente_uc (fk_iddocente, fk_iduc, ano_letivo) VALUES (?, ?, ?)")) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $param_docente, $param_uc, $param_ano);

        $param_docente = (int)trim($_POST['set_id_docente']);
        $param_uc = (int)trim($_POST['set_id_uc']);
        $param_ano = (int)trim($_POST['set_ano_letivo']);
            
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "</br>Ligação UC - Docente inserida com sucesso.";
        } else {
            echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
        }
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu preparar a query: " . mysqli_error($connectDB) . " . ";
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
}

//-- Remove ligação entre Docente e UC para o atual ano letivo
function removeDocenteUcConn($connectDB)
{
    // inicializar prepared statement
    $stmt = $connectDB->prepare("DELETE FROM docente_uc WHERE fk_iddocente=? AND fk_iduc=? AND ano_letivo=?");
    
    if (false === $stmt) {
        echo "Não conseguiu preparar a query";
    }
    
    $stmt->bind_param("iii", $param_iddocente, $param_iduc, $param_ano);

    $param_iddocente = (int)trim($_POST['remove_id_docente']);
    $param_iduc = (int)trim($_POST['remove_id_uc']);
    $param_ano = (int)trim($_POST['remove_ano_letivo']);

    // executar
    if ($stmt->execute()) {
        echo "</br>Ligação Docente - UC removida com sucesso.";
    } else {
        echo "</br>Ocurreu um erro: Não conseguiu executar a query: " . mysqli_error($connectDB) . ". ";
    }
    $stmt->close();
}