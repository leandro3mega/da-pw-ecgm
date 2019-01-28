<?php
session_start();

require_once("connectdb.php");

// echo"Funciona";

if (isset($_GET['action']) && $_GET['action'] == 'get_ucs') {
    $semestre = $_GET['semestre'];
    //-- Seleciona as ucs a mostrar na pagina novo-projeto
    selectUCS($connectDB, $semestre);
} elseif (isset($_POST['action']) && $_POST['action'] == 'fetch_users') {
    selectAllUsernames($connectDB);
} elseif (isset($_POST['action']) && $_POST['action'] == 'fetch_emails') {
    selectAllEmails($connectDB);
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

// selectAllUsers($connectDB);

function selectAllUsernames($connectDB)
{
    $input = $_POST['input_utilizador'];
    $encontrou = false;

    if ($stmt = $connectDB->prepare("SELECT username FROM utilizador WHERE username like ?")) {
        $stmt->bind_param("s", $param_input);

        $param_input = $input;
    
        // executar
        $stmt->execute();
    
        // associar os parametros de output
        $stmt->bind_result($r_username);
    
        // transfere o resultado da última query : obrigatorio para ter num_rows
        $stmt->store_result();
    
        // iterar / obter resultados
        $stmt->fetch();
    
        if ($stmt->num_rows > 0) { // seleciona o resultado da base de dados
            // echo("</br>Encontrado ficheiro");
            echo "True";
        } else {
            // echo("</br>Não encontrou nenhum ficheiro");
            // $existe = false;
            echo "False";
        }
    }
    $stmt->close();
}

function selectAllEmails($connectDB)
{
    $input = $_POST['input_email'];
    $encontrou = false;

    if ($stmt = $connectDB->prepare("SELECT fk_idutilizador
                                    FROM aluno
                                     WHERE email LIKE ?")) {
        $stmt->bind_param("s", $param_input);

        $param_input = $input;
    
        // executar
        $stmt->execute();
        // associar os parametros de output
        $stmt->bind_result($r_id);
        // transfere o resultado da última query : obrigatorio para ter num_rows
        $stmt->store_result();
        // iterar / obter resultados
        $stmt->fetch();
    
        if ($stmt->num_rows > 0) {
            $encontrou = true;
        } else {
            $encontrou = false;
        }
    }

    if (!$encontrou) {
        if ($stmt = $connectDB->prepare("SELECT fk_idutilizador
                                        FROM docente d
                                         WHERE email LIKE ?")) {
            $stmt->bind_param("s", $param_input);
    
            $param_input = $input;
        
            // executar
            $stmt->execute();
            // associar os parametros de output
            $stmt->bind_result($r_id);
            // transfere o resultado da última query : obrigatorio para ter num_rows
            $stmt->store_result();
            // iterar / obter resultados
            $stmt->fetch();
        
            if ($stmt->num_rows > 0) {
                $encontrou = true;
            } else {
                $encontrou = false;
            }
        }
    }

    if ($encontrou) {
        echo "True";
    } else {
        echo "False";
    }


    $stmt->close();
}