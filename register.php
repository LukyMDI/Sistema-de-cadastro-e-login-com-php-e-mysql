<?php

    require_once 'config.php';

    $user = $password = $confirm_password = "";
    $user_err = $password_err = $confirm_password_err = "";

    // Processa os dados do formulário após o envio.
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // Valida o nome de usuário.
        if (empty(trim($_POST['email']))) {
            $user_err = 'Por favor, insira seu email';
        } else {
            $sql = "SELECT id FROM users WHERE email = :email";

            if ($stmt = $pdo -> prepare($sql)) {
                // Vinculação das variáveis.
                $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);

                // Definir os parâmetros.
                $param_email = trim($_POST['email']);

                // Tenta executar a declaração.
                if ($stmt -> execute()) {
                    if ($stmt -> rowCount() == 1) {
                        $user_err = 'Este email já está em uso.';
                    } else {
                        $user = trim($_POST['email']);
                    }
                } else {
                    echo "Algo deu errado. Por favor, espere um pouco e tente novamente";
                }

                unset($stmt);
            }
        }

        // Valida a senha.
        if (empty(trim($_POST['password']))) {
            $password_err = 'Por favor insira uma senha.';
        } else if (strlen(trim($_POST['password'])) < 6) {
            $password_err = 'A senha deve ter pelo menos 6 caracteres';
        } else {
            $password = trim($_POST['password']);
        }

        // Validar e confirmar a senha.
        if (empty(trim($_POST['confirm_password']))) {
            $confirm_password_err = 'Por favor, confirme a senha';
        } else {
            $confirm_password = trim($_POST['confirm_password']);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "A senha não confere.";
            }
        }

        // Verifica os erros de entrada antes de inserir no banco de dados
        if (empty($user_err) && empty($password_err) && empty($confirm_password_err)) {

            // Prepara uma declaração de inserção de dados.
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";

            if ($stmt = $pdo -> prepare($sql)) {
                
                $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);
                $stmt -> bindParam(":password", $param_password, PDO::PARAM_STR);

                // Definição dos parâmetros.
                $param_email = $user;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Cria um password hash

                // Tenta executar a declaração construída.
                if ($stmt -> execute()) {
                    // Redireciona para a página de login.
                    header("location: index.php");
                } else {
                    echo 'Algo deu errado. Por favor, espere um pouco e tente novamente';
                }

                // Fecha a declaração
                unset($stmt);
            }
        }

        unset($pdo);
    
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registro</title>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <form action="" method="POST">
                <h2>Cadastro</h2>
                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" required>
                    <label for="">Email</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password" id="" required>
                    <label for="">Senha</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="confirm_password" id="" required>
                    <label for="">Confirmar senha</label>
                </div>
                <input type="submit" value="Criar Conta" id="btn">
                <div class="register">
                    <p>Já tem uma conta? <a href="index.php">Fazer login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>