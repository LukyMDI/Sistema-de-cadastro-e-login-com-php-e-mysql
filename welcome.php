<?php

    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('location: index.php');
        exit;
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem vindo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
        .well { display: flex; flex-direction: column; align-items: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="well">
            <h1>Bem vindo ao meu site</h1>
            <p>
                <a href="reset-password.php" class="btn btn-primary">Redefina sua senha</a>
                <a href="logout.php" class="btn btn-danger ml-3">Sair da conta</a>
            </p>
        </div>
    </div>
</body>
</html>