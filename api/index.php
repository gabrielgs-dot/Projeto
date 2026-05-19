<?php

ini_set('session.save_path', '/tmp');

session_start();

include("conexao.php");

$erro = "";

/* ============================
   LOGIN
============================ */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email = $1";

    $result = pg_query_params(
        $conn,
        $sql,
        array($email)
    );

    if ($result && pg_num_rows($result) == 1) {

        $usuario = pg_fetch_assoc($result);

        // DEBUG
        // print_r($usuario);

        if (password_verify($senha, $usuario["senha"])) {

            $_SESSION["logado"] = true;
            $_SESSION["id"]     = $usuario["id"];
            $_SESSION["nome"]   = $usuario["nome"];
            $_SESSION["tipo"]   = $usuario["tipo"];

            // Admin
            if ($usuario["tipo"] == "admin") {

                $_SESSION["admin"] = $usuario["id"];
            }

            // DEBUG
            // print_r($_SESSION);
            // exit();

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

    <h4 class="mb-4">
        Login do Sistema
    </h4>

    <!-- ERRO -->
    <?php if (!empty($erro)) : ?>

        <div class="alert alert-danger">

            <?= $erro ?>

        </div>

    <?php endif; ?>

    <!-- FORM -->
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

        <button class="btn btn-primary btn-lg"
                type="submit">

            Entrar

        </button>

        <p class="mt-4 text-muted">
            Sistema de Ordem de Serviço © 2026
        </p>

    </form>

</div>

</body>
</html>
