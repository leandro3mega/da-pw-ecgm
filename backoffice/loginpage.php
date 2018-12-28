<?php

session_start();

// verifica se existe login/sessão
if (isset($_SESSION['username'])) {
    header("location:index.php");
    session_write_close();

    exit();
}

// se não existir continua no codido

?>

<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Design de Ambientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    <div>
        <form id="formlogin" action="login.php" method="post">
            <input type="text" name="username" id="iusername" placeholder="Username...">
            <br>
            <input type="text" name="password" id="ipassword" placeholder="Password...">
            <br>
            
            <input type="submit" value="Iniciar Sessão">
        </form>
    </div>
</body>

<script>

</script>

</html>