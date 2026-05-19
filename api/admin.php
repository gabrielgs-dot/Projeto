<?php

include("conexao.php");

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

$check = $conn->prepare("SELECT id FROM usuarios WHERE email=?");
$check->bind_param("s", $email);
$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    echo "Admin já existe!";

} else {

    /* ===========================
       CRIAR ADMIN
    =========================== */

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $nome,
        $email,
        $senha,
        $tipo
    );

    if ($stmt->execute()) {

        echo "Admin criado com sucesso!<br><br>";

        echo "Email: admin@admin.com <br>";
        echo "Senha: 1234";

    } else {

        echo "Erro ao criar admin.";

    }
}

?>