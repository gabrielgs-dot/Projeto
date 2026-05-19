<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cliente_id       = $_POST["cliente_id"];
    $departamento_id  = $_POST["departamento_id"];
    $equipamento      = $_POST["equipamento"];
    $problema         = $_POST["problema"];
    $servico          = $_POST["servico"];

    $status = "Aberta";

    $sql = "INSERT INTO ordens_servico
            (cliente_id, departamento_id, equipamento, problema, servico, status, data_abertura)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iissss",
        $cliente_id,
        $departamento_id,
        $equipamento,
        $problema,
        $servico,
        $status
    );

    if ($stmt->execute()) {
        header("Location: consulta.php");
        exit();
    } else {
        echo "❌ Erro ao salvar OS: " . $conn->error;
    }
}
?>
