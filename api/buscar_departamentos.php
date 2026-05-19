<?php
require_once __DIR__ . "/conexao.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (!isset($_GET["cliente_id"])) {

    echo json_encode([
        "erro" => "cliente_id não foi enviado"
    ]);

    exit;
}

$cliente_id = $_GET["cliente_id"];

$sql = "
    SELECT
        id,
        nome
    FROM departamentos
    WHERE cliente_id = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    array($cliente_id)
);

if (!$result) {

    echo json_encode([
        "erro" => "Erro na consulta"
    ]);

    exit;
}

$departamentos = [];

while ($row = pg_fetch_assoc($result)) {

    $departamentos[] = $row;
}

echo json_encode($departamentos);
?>
