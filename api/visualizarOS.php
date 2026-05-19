<?php
session_start();
include("conexao.php");

/* Verifica login */
if (!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

/* Verifica ID */
if (!isset($_GET["id"])) {
    echo "OS não encontrada.";
    exit();
}

$id = intval($_GET["id"]);

/* Buscar OS completa */
$sql = "
SELECT os.*,
       c.nome AS cliente_nome,
       c.telefone,
       c.endereco,
       d.nome AS departamento_nome,
       i.modelo AS impressora_modelo,
       i.patrimonio AS impressora_patrimonio
FROM ordens_servico os
LEFT JOIN clientes c ON os.cliente_id = c.id
LEFT JOIN departamentos d ON os.departamento_id = d.id
LEFT JOIN impressoras i ON os.impressora_id = i.id
WHERE os.id = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    array($id)
);

if (!$result || pg_num_rows($result) == 0) {
    echo "OS não encontrada.";
    exit();
}

$os = pg_fetch_assoc($result);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>OS Nº <?= str_pad($os["id"], 6, "0", STR_PAD_LEFT) ?></title>

  <!-- Bootstrap -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

  <style>
    body {
      background: #f2f2f2;
      font-family: Arial;
    }

    .os-box {
      width: 900px;
      margin: 40px auto;
      background: white;
      padding: 40px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    .top-buttons {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .titulo {
      text-align: center;
      font-size: 55px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .subtitulo {
      text-align: center;
      font-size: 18px;
      margin-bottom: 25px;
    }

    .linha {
      border-top: 2px solid black;
      margin: 25px 0;
    }

    .section-title {
      background: #eee;
      padding: 10px;
      font-weight: bold;
      text-align: center;
      border: 1px solid black;
      margin-top: 25px;
    }

    .dados p {
      margin: 5px 0;
      font-size: 16px;
    }

    .assinaturas {
      display: flex;
      justify-content: space-between;
      margin-top: 70px;
    }

    .assinaturas div {
      width: 40%;
      text-align: center;
      border-top: 1px solid black;
      padding-top: 10px;
      font-size: 14px;
    }

    @media print {

      .top-buttons {
        display: none;
      }

      body {
        background: white;
      }

      .os-box {
        border: none;
        width: 100%;
        margin: 0;
      }
    }
  </style>
</head>

<body>

<div class="os-box">

  <!-- Botões -->
  <div class="top-buttons">

    <a href="consulta.php" class="btn btn-secondary">
      Voltar
    </a>

    <button onclick="window.print()" class="btn btn-primary">
      Imprimir OS
    </button>

  </div>

  <!-- Cabeçalho -->
  <div class="titulo">GGS</div>

  <div class="subtitulo">

    ORDEM DE SERVIÇO <br>

    Nº OS:
    <?= str_pad($os["id"], 6, "0", STR_PAD_LEFT) ?>

  </div>

  <p class="text-center">
    <b>Status:</b>
    <?= htmlspecialchars($os["status"]) ?>
  </p>

  <p class="text-center">
    <b>Data de Abertura:</b>

    <?= date("d/m/Y H:i", strtotime($os["data_abertura"])) ?>
  </p>

  <div class="linha"></div>

  <!-- Cliente -->
  <div class="dados">

    <p>
      <b>Cliente:</b>
      <?= htmlspecialchars($os["cliente_nome"]) ?>
    </p>

    <p>
      <b>Telefone:</b>
      <?= htmlspecialchars($os["telefone"]) ?>
    </p>

    <p>
      <b>Endereço:</b>
      <?= htmlspecialchars($os["endereco"]) ?>
    </p>

  </div>

  <!-- Equipamento -->
  <div class="section-title">
    EQUIPAMENTO / SERVIÇO
  </div>

  <div class="dados mt-3">

    <p>
      <b>Equipamento:</b>
      <?= htmlspecialchars($os["impressora_modelo"]) ?>
    </p>

    <p>
      <b>Patrimônio:</b>
      <?= htmlspecialchars($os["impressora_patrimonio"]) ?>
    </p>

    <p>
      <b>Departamento:</b>
      <?= htmlspecialchars($os["departamento_nome"]) ?>
    </p>

    <p>
      <b>Problema:</b>
      <?= nl2br(htmlspecialchars($os["problema"])) ?>
    </p>

    <p>
      <b>Ocorrência:</b>
      <?= nl2br(htmlspecialchars($os["servico"])) ?>
    </p>

    <p>
      <b>Contato:</b>
      <?= htmlspecialchars($os["telefone"]) ?>
    </p>

    <p>
      <b>Valor:</b>
      R$ <?= number_format($os["valor"], 2, ",", ".") ?>
    </p>

    <?php if (!empty($os["data_fechamento"])): ?>

      <p>
        <b>Data de Fechamento:</b>

        <?= date("d/m/Y H:i", strtotime($os["data_fechamento"])) ?>
      </p>

    <?php endif; ?>

  </div>

  <div class="linha"></div>

  <!-- Assinaturas -->
  <div class="assinaturas">

    <div>
      Visto do Cliente
    </div>

    <div>
      Assinatura Técnico
    </div>

  </div>

</div>

</body>
</html>
