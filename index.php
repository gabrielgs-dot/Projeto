<?php
session_start();
require_once __DIR__ . "/conexao.php";

$erro = "";

/* ============================
   LOGIN (ADMIN + FUNCIONÁRIO)
============================ */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Busca usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $usuario = $result->fetch_assoc();

        // Verifica senha
        if (password_verify($senha, $usuario["senha"])) {

            $_SESSION["logado"] = true;
            $_SESSION["id"]     = $usuario["id"];
            $_SESSION["nome"]   = $usuario["nome"];
            $_SESSION["tipo"]   = $usuario["tipo"];

           if ($usuario["tipo"] == "admin") {
    $_SESSION["admin"] = $usuario["id"]; // guarda o ID real do admin
}

            header("Location: painel.php");
            exit();

        } else {
            $erro = "Senha incorreta!";
        }

    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Login - Sistema OS</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Estilo para centralizar e criar quadrado -->
    <style>
        body {
            background: #f2f2f2;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0px 0px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        .login-box img {
            width: 140px;
            margin-bottom: 20px;
        }

        .login-box button {
            width: 100%;
        }
    </style>
</head>

<body>

<div class="login-box">

    <!-- Logo -->
    <img src="imagens/logo.png" alt="Logo">

    <h4 class="mb-4">Login do Sistema</h4>

    <!-- Mensagem de erro -->
    <?php if(!empty($erro)) : ?>
        <div class="alert alert-danger">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <form method="POST">

        <input type="email"
               name="email"
               class="form-control mb-3"
               placeholder="Digite seu email"
               required>

        <input type="password"
               name="senha"
               class="form-control mb-3"
               placeholder="Digite sua senha"
               required>

        <button class="btn btn-primary btn-lg" type="submit">
            Entrar
        </button>

        <p class="mt-4 text-muted">
            Sistema de Ordem de Serviço © 2026
        </p>

    </form>

</div>

</body>
</html>
