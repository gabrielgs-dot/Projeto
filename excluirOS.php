<?php
session_start();
include("conexao.php");

/* ✅ Permitir admin e funcionário */
if(!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("OS inválida.");
}

$id = intval($_GET["id"]);

$sql = "DELETE FROM ordens_servico WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: consulta.php");
    exit();
} else {
    echo "Erro ao excluir OS.";
}
?>
