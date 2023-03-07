<?php

    session_start();

    // Remove todas as variáveis da sessão.
    $_SESSION = array();

    // Destrói a sessão.
    session_destroy();

    // Redireciona para a página principal.
    header('location: index.php');
    exit;

?>