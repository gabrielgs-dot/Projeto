<?php
include("conexao.php");

$cliente_id = $_GET["cliente_id"];

$sql = "
SELECT 
    impressoras.id,
    impressoras.modelo,
    departamentos.nome AS departamento
FROM impressoras
JOIN departamentos 
    ON impressoras.departamento_id = departamentos.id
WHERE departamentos.cliente_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();

$result = $stmt->get_result();

$impressoras = [];

while($row = $result->fetch_assoc()){
    $impressoras[] = $row;
}

echo json_encode($impressoras);
?>
