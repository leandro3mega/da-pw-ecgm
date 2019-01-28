<?php
session_start();

require_once("connectdb.php");

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
    header("location:index.php");
    session_write_close();

    exit();
}

// Define variables and initialize with empty values
$email = $password = "";

//echo(password_hash("admin", PASSWORD_DEFAULT));

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Check if username is empty
    if (!empty(trim($_POST["username"]))) {
        $username = trim($_POST["username"]);
        echo("</br>" . $email);
    }
    
    // Check if password is empty
    if (!empty(trim($_POST["password"]))) {
        $password = trim($_POST["password"]);
        echo("</br>" . $password);
    }
    
    // Validate credentials
    // Prepare a select statement
    $sql = "SELECT idutilizador, username, password, tipo FROM utilizador WHERE username = ?";
        
    if ($stmt = $connectDB->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);
            
        // Set parameters
        $param_username = $username;
            
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();
                
            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($r_id, $r_username, $r_hashed_password, $r_tipo);
                if ($stmt->fetch()) {
                    if (password_verify($password, $r_hashed_password)) {
                            
                        // Store data in session variables
                        $_SESSION["id"] = $r_id;
                        $_SESSION["username"] = $r_username;
                        $_SESSION["tipo"] = $r_tipo;

                        //-- Converte int em string para mostrar o cargo do user no menu superior
                        if ($r_tipo == 0) {
                            $_SESSION['cargo'] = "Administrador";
                        } elseif ($r_tipo == 1) {
                            $_SESSION['cargo'] = "Aluno";
                        } else {
                            $_SESSION['cargo'] = "Professor";
                        }
                            
                        // Redirect user to index page
                        header("location: index.php");
                    } else {
                        // Display an error message if password is not valid
                        $msg = "A palavra passe inserida não é valida.";
                        echo '<script language="javascript">';
                        echo 'alert("' . $msg . '");';
                        echo 'window.location.replace("iniciar-sessao.php");';
                        echo '</script>';
                    }
                }
            } else {
                $msg = "Não foi encontrada conta com o email: " . $email;
                echo '<script language="javascript">';
                echo 'alert("' . $msg . '");';
                echo 'window.location.replace("iniciar-sessao.php");';
                echo '</script>';
            }
        } else {
            $msg = "Algo correu mal. Por favor tente de novo.";
            echo '<script language="javascript">';
            echo 'alert("' . $msg . '");';
            echo 'window.location.replace("iniciar-sessao.php");';
            echo '</script>';
        }
    }
        
    // Close statement
    $stmt->close();
}





session_write_close();
// Close connection
$connectDB->close();

exit();