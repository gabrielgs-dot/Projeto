<?php

require_once __DIR__ . "/conexao.php";

/* ===========================
   DADOS DO ADMIN
=========================== */

$nome  = "Administrador";
$email = "admin@admin.com";
$senha = password_hash("1234", PASSWORD_DEFAULT);
$tipo  = "admin";

/* ===========================
   VERIFICA SE JÁ EXISTE
=========================== */

$check = pg_query_params(
    $conn,
    "SELECT id FROM usuarios WHERE email = $1",
    array($email)
);

if ($check && pg_num_rows($check) > 0) {

    echo "Admin já existe!";

} else {

    /* ===========================
       CRIAR ADMIN
    =========================== */

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
            VALUES ($1, $2, $3, $4)";

    $insert = pg_query_params(
        $conn,
        $sql,
        array($nome, $email, $senha, $tipo)
    );

    if ($insert) {

        echo "Admin criado com sucesso!<br><br>";

        echo "Email: admin@admin.com <br>";
        echo "Senha: 1234";

    } else {

        echo "Erro ao criar admin.";
    }
}
?>
