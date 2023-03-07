<?php

    session_start();

    // Verifica se o usuário está logado.
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('location: index.php');
        exit;
    }

    // Inclui o arquivo de configuração.
    require_once 'config.php';

    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";

    // Processa os dados do formulário após envio.
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        // Validação da nova senha.
        if (empty(trim($_POST['new_password']))) {
            $new_password_err = 'Por favor insira a nova senha';
        } else if (strlen(trim($_POST['new_password'])) < 6) {
            $new_password_err = 'A senha deve ter pelo menos 6 caracteres';
        } else {
            $new_password = trim($_POST['new_password']);
        }

        // Valida e confirma a senha.
        if (empty(trim($_POST['confirm_password']))) {
            $confirm_password_err = 'Por favor, confirme a senha';
        } else {
            $confirm_password = trim($_POST['confirm_password']);
            if (empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_password_err = 'A senha não confere';
            }
        }

        // Verifica os erros de entrada antes de os enviar para o banco de dados.
        if(empty($new_password_err) && empty($confirm_password_err)) {
            // Prepara uma declaração de atualização.
            $sql = "UPDATE users SET password = :password WHERE id = :id";

            if ($stmt = $pdo -> prepare($sql)) {
                $stmt -> bindParam(":password", $param_password, PDO::PARAM_STR);
                $stmt -> bindParam(":id", $param_id, PDO::PARAM_INT);

                // Define os parâmetros
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION['id'];

                // Tenta executar a declaração preparada.
                if ($stmt -> execute()) {
                    // Senha atualizada. A sessão é destruída.
                    session_destroy();
                    header('location: index.php');
                    exit();
                } else {
                    echo "Algo deu errado. Por favor, espere um pouco e tente novamente";
                }

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
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <h2>Redefinir senha</h2>
            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="new_password" value="<?php echo $new_password; ?>" required>
                <label class=" <?php echo (!empty($new_password_err)) ? 'text-danger' : '' ?> "> <?php echo (!empty($new_password_err)) ? "$new_password_err" : 'Nova senha' ?> </label>
            </div>
            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="confirm_password" required>
                <label class=" <?php echo (!empty($confirm_password_err)) ? 'text-danger' : ''; ?> "> <?php echo (!empty($confirm_password_err)) ? "$confirm_password_err" : 'Confirme a senha';  ?> </label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-danger" value="Redefinir">
                <a class="btn btn-link text-white ml-2" href="welcome.php">Cancelar</a>
            </div>
        </form>
        </div>
    </div>
</body>
</html>