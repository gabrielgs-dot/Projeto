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

$id = $_GET["id"];

/* Buscar OS completa */
$sql = "SELECT os.*,
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
        WHERE os.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "OS não encontrada.";
    exit();
}

$os = $result->fetch_assoc();
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
    Nº OS: <?= str_pad($os["id"], 6, "0", STR_PAD_LEFT) ?>
  </div>

  <p class="text-center">
    <b>Status:</b> <?= $os["status"] ?>
  </p>

  <p class="text-center">
    <b>Data de Abertura:</b>
    <?= date("d/m/Y H:i", strtotime($os["data_abertura"])) ?>
  </p>

  <div class="linha"></div>

  <!-- Cliente -->
  <div class="dados">
    <p><b>Cliente:</b> <?= $os["cliente_nome"] ?></p>
    <p><b>Telefone:</b> <?= $os["telefone"] ?></p>
    <p><b>Endereço:</b> <?= $os["endereco"] ?></p>
  </div>

  <!-- Equipamento -->
  <div class="section-title">
    EQUIPAMENTO / SERVIÇO
  </div>

  <div class="dados mt-3">
    <p><b>Equipamento:</b>
      <?= $os["impressora_modelo"] ?>
    </p>

    <p><b>Departamento:</b> <?= $os["departamento_nome"] ?></p>

    <p><b>Problema:</b> <?= $os["problema"] ?></p>

    <p><b>Ocorrência:</b> <?= $os["servico"] ?></p>

    <p><b>Contato:</b> <?= $os["telefone"] ?></p>
  </div>

  <div class="linha"></div>

  <!-- Assinatura -->
  <div class="assinaturas">
    <div>Visto do Cliente</div>
    <div>Assinatura</div>
  </div>

</div>

</body>
</html>
