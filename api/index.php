<?php
session_start();

include("conexao.php");

$erro = "";

/* LOGIN */
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

        echo "<pre>";
        print_r($usuario);
        echo "</pre>";

        if (password_verify($senha, $usuario["senha"])) {

            $_SESSION["logado"] = true;
            $_SESSION["id"]     = $usuario["id"];
            $_SESSION["nome"]   = $usuario["nome"];
            $_SESSION["tipo"]   = $usuario["tipo"];

            if ($usuario["tipo"] == "admin") {
                $_SESSION["admin"] = $usuario["id"];
            }

            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";

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
    <title>Login</title>
</head>

<body>

<form method="POST">

    <input type="email"
           name="email"
           placeholder="Email"
           required>

    <br><br>

    <input type="password"
           name="senha"
           placeholder="Senha"
           required>

    <br><br>

    <button type="submit">
        Entrar
    </button>

</form>

<?php if(!empty($erro)): ?>

    <p><?= $erro ?></p>

<?php endif; ?>

</body>
</html>
