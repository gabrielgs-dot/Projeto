<?php

$host = "localhost";
$usuario = "root";
$senha = "paris1308";
$banco = "sistema_os";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
