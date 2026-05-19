<?php
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cliente_id       = $_POST["cliente_id"];
    $departamento_id  = $_POST["departamento_id"];
    $equipamento      = $_POST["equipamento"];
    $problema         = $_POST["problema"];
    $servico          = $_POST["servico"];

    $status = "Aberta";

    $sql = "
        INSERT INTO ordens_servico
        (
            cliente_id,
            departamento_id,
            equipamento,
            problema,
            servico,
            status,
            data_abertura
        )
        VALUES
        (
            $1,
            $2,
            $3,
            $4,
            $5,
            $6,
            NOW()
        )
    ";

    $result = pg_query_params(
        $conn,
        $sql,
        array(
            $cliente_id,
            $departamento_id,
            $equipamento,
            $problema,
            $servico,
            $status
        )
    );

    if ($result) {

        header("Location: consulta.php");
        exit();

    } else {

        echo "❌ Erro ao salvar OS.";
    }
}
?>
