<?php
require_once __DIR__ . "/conexao.php";

$cliente_id = $_GET["cliente_id"];

$sql = "
SELECT 
    impressoras.id,
    impressoras.modelo,
    departamentos.nome AS departamento
FROM impressoras
JOIN departamentos 
    ON impressoras.departamento_id = departamentos.id
WHERE departamentos.cliente_id = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    array($cliente_id)
);

$impressoras = [];

while($row = pg_fetch_assoc($result)){

    $impressoras[] = $row;
}

echo json_encode($impressoras);
?>
