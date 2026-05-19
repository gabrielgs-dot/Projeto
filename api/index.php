<?php
session_start();
include("conexao.php");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email = $1";

    $result = pg_query_params($conn, $sql, array($email));

    if ($result && pg_num_rows($result) > 0) {

        $usuario = pg_fetch_assoc($result);

        if (password_verify($senha, $usuario["senha"])) {

            $_SESSION["logado"] = true;
            $_SESSION["id"] = $usuario["id"];
            $_SESSION["nome"] = $usuario["nome"];
            $_SESSION["tipo"] = $usuario["tipo"];

            if ($usuario["tipo"] == "admin") {
                $_SESSION["admin"] = $usuario["id"];
            }

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
    <title>Login</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-dark">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <h3 class="text-center mb-4">
                        Login
                    </h3>

                    <?php if ($erro != ""): ?>

                        <div class="alert alert-danger">
                            <?= $erro ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <label>Email</label>

                        <input type="email"
                               name="email"
                               class="form-control mb-3"
                               required>

                        <label>Senha</label>

                        <input type="password"
                               name="senha"
                               class="form-control mb-4"
                               required>

                        <button type="submit"
                                class="btn btn-dark btn-block">

                            Entrar

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
