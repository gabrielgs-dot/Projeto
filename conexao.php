<?php
$host = "localhost";
$user = "root";
$pass = "paris1308";
$db   = "sistema_os";

// Corrigido: mysqli em vez de mysql
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>