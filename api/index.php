<?php
session_start();
include("conexao.php");

/* Mostrar erros */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Se já estiver logado */
if (isset($_SESSION["logado"])) {
    header("Location: painel.php");
    exit();
}

$erro = "";

/* LOGIN */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        die("Erro no execute: " . $stmt->error);
    }

    $result = $stmt->get_result();

    /* Usuário encontrado */
    if ($result->num_rows > 0) {

        $usuario = $result->fetch_assoc();

        /* Verifica senha */
        if (password_verify($senha, $usuario["senha"])) {

            $_SESSION["logado"] = true;
            $_SESSION["id"]     = $usuario["id"];
            $_SESSION["nome"]   = $usuario["nome"];
            $_SESSION["tipo"]   = $usuario["tipo"];

            /* Se admin */
            if ($usuario["tipo"] == "admin") {
                $_SESSION["admin"] = $usuario["id"];
            }

            /* DEBUG */
            /*
            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";
            exit();
            */

            header("Location: painel.php");
            exit();

        } else {

            $erro = "Senha incorreta.";

        }

    } else {

        $erro = "Usuário não encontrado.";

    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">

    <title>Login - Sistema OS</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

    <style>

        body {
            background: #343a40;
        }

        .login-box {

            background: white;

            padding: 40px;

            border-radius: 15px;

            margin-top: 100px;

            box-shadow: 0px 0px 20px rgba(0,0,0,0.4);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 150px;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="login-box">

                <!-- Logo -->
                <div class="logo">

                    <img src="imagens/logo.png" alt="Logo">

                </div>

                <h3 class="text-center mb-4">

                    Sistema OS

                </h3>

                <!-- Erro -->
                <?php if (!empty($erro)): ?>

                    <div class="alert alert-danger">

                        <?= $erro ?>

                    </div>

                <?php endif; ?>

                <!-- Form -->
                <form method="POST">

                    <div class="form-group">

                        <label>Email</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               required>

                    </div>

                    <div class="form-group">

                        <label>Senha</label>

                        <input type="password"
                               name="senha"
                               class="form-control"
                               required>

                    </div>

                    <button type="submit"
                            class="btn btn-dark btn-block btn-lg">

                        Entrar

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
