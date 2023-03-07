<?php

    session_start();

    // Verifica se o usuário já está logado no site.
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header('location: welcome.php');
        exit;
    }

    // Inclusão do arquivo de configuração.
    require_once 'config.php';

    // Definição das variáveis.
    $user = $password = "";
    $user_err = $password_err = $login_err = "";

    // Processamento dos dados do formulário.
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        // Verifica se o nome do usuário está vazio.
        if (empty(trim($_POST['email']))) {
            $user_err = 'Por favor, insira o seu email.';
        } else {
            $user = trim($_POST['email']);
        }

        // Verifica se a senha está vazia.
        if (empty(trim($_POST['password']))) {
            $password_err = 'Por favor, insira sua senha.';
        } else {
            $password = trim($_POST['password']);
        }

        // Validação das credenciais.
        if (empty($user_err) && empty($password_err)) {
            $sql = "SELECT id, email, password FROM users WHERE email = :email";

            if ($stmt = $pdo -> prepare($sql)) {
                //Vinculação das variáveis a instrução construída.
                $stmt -> bindParam(":email", $param_email, PDO::PARAM_STR);

                // Definição dos parâmetros.
                $param_email = trim($_POST['email']);

                // Tenta executar a declaração construída.
                if($stmt -> execute()) {
                    // Verifica se o nome de usuário existe.
                    if($stmt -> rowCount() == 1) {
                        if($row = $stmt -> fetch()) {
                            $id = $row['id'];
                            $user = $row['email'];
                            $hashed_password = $row['password'];
                            if (password_verify($password, $hashed_password)) {
                                // Se tudo estiver correto, uma nova seção é iniciada.
                                session_start();

                                // Armazena os dados da sessão em variáveis.
                                $_SESSION['loggedin'] = true;
                                $_SESSION['id'] = $id;
                                $_SESSION['email'] = $user;

                                // Redireciona o usuário para a página principal.
                                header('location: welcome.php');
                            } else {
                                // Caso os passos anteriores não dêem certo, uma mensagem de erro é exibida.
                                $login_err = 'Email ou senha inválidos.';
                            }
                        }
                    } else {
                        // Caso o email não existe, exibe uma mensagem de erro.
                        $login_err = 'Email ou senha inválidos.';
                    }
                } else {
                    echo 'Algo deu errado. Por favor, espere um pouco e tente novamente';
                }

                // Fechamento da declaração.
                unset($stmt);
            }
        }

        // Fechamento da conexão.
        unset($pdo);
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <h2>Login</h2>
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
                <div class="forget">
                    <label for=""><input type="checkbox" name="" id=""> Lembre de mim <a href="#">Esqueceu a senha?</a></label>
                </div>
                <input type="submit" value="Login" id="btn">
                <div class="register">
                    <p>Não tem uma conta? <a href="register.php">Registre-se</a></p>
                    <span> <?php echo $login_err ?> </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Ícones Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>