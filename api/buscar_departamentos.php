<?php
include("conexao.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (!isset($_GET["cliente_id"])) {
    echo json_encode(["erro" => "cliente_id não foi enviado"]);
    exit;
}

$cliente_id = $_GET["cliente_id"];

$sql = "SELECT id, nome FROM departamentos WHERE cliente_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["erro" => "Erro no prepare: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $cliente_id);
$stmt->execute();

$result = $stmt->get_result();

$departamentos = [];

while ($row = $result->fetch_assoc()) {
    $departamentos[] = $row;
}

echo json_encode($departamentos);
?>
