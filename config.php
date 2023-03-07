<?php

    define('DB_SERVER', 'nome do servidor');
    define('DB_USERNAME', 'nome do usuário');
    define('DB_PASSWORD', 'senha do banco de dados');
    define('DB_NAME', 'nome do banco de dados');

    try {
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $err) {
        die("Error: Não foi possível conectar." . $err -> getMessage());
    }

?>