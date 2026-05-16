<?php
session_start();
include("conexao.php");

/* ✅ Permitir admin e funcionário */
if (!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];

/* ✅ Verifica ID */
if (!isset($_GET["id"])) {
    die("OS não encontrada.");
}

$id = intval($_GET["id"]);

/* ===========================
   Buscar OS completa
=========================== */
$sql = "
SELECT os.*, c.nome AS cliente_nome, d.nome AS departamento_nome
FROM ordens_servico os
JOIN clientes c ON os.cliente_id = c.id
JOIN departamentos d ON os.departamento_id = d.id
WHERE os.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$os = $stmt->get_result()->fetch_assoc();

if (!$os) {
    die("OS inválida.");
}

/* ===========================
   Atualizar OS
=========================== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $problema = $_POST["problema"];
    $servico  = $_POST["servico"];
    $status   = $_POST["status"];
    $valor    = floatval($_POST["valor"]);

    /* ✅ Data fechamento */
    if ($status == "Fechada") {

        $data_fechamento = date("Y-m-d H:i:s");

    } else {

        $data_fechamento = null;

    }

    $update = "
        UPDATE ordens_servico 
        SET problema=?,
            servico=?,
            status=?,
            valor=?,
            data_fechamento=?
        WHERE id=?
    ";

    $stmt = $conn->prepare($update);

    $stmt->bind_param(
        "sssdsi",
        $problema,
        $servico,
        $status,
        $valor,
        $data_fechamento,
        $id
    );

    if ($stmt->execute()) {

        header("Location: consulta.php");
        exit();

    } else {

        echo "Erro ao atualizar OS.";

    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Editar OS</title>

  <!-- Bootstrap -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

  <!-- Cover igual painel -->
  <link rel="stylesheet"
        href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

  <style>

    /* Corrige largura */
    .cover-container {
      max-width: 100% !important;
    }

    /* Centralizar menu */
    .nav-masthead {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Dropdown escuro */
    .dropdown-menu {
      background-color: #222;
      border: 1px solid #444;
      text-align: center;
    }

    .dropdown-item {
      color: white;
    }

    .dropdown-item:hover {
      background-color: #444;
      color: white;
    }

    /* Espaçamento links */
    .nav-link {
      margin: 0 10px;
    }

    /* Logo */
    .masthead-brand img {
      height: 110px;
      margin-bottom: 15px;
    }

    /* Caixa formulário */
    .box-form {
      background: white;
      color: black;
      padding: 35px;
      border-radius: 15px;
      max-width: 900px;
      margin: auto;
      text-align: left;
      box-shadow: 0px 0px 25px rgba(0,0,0,0.4);
    }

    label {
      font-weight: bold;
    }

    .btn-center {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 25px;
      flex-wrap: wrap;
    }

    .btn-center button,
    .btn-center a {
      width: 220px;
    }

  </style>
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">

  <!-- TOPO -->
  <header class="masthead mb-auto">
    <div class="inner">

      <!-- LOGO -->
      <a href="painel.php" class="masthead-brand">
        <img src="imagens/logo.png" alt="Logo Sistema OS">
      </a>

      <!-- MENU -->
      <nav class="nav nav-masthead justify-content-center">

        <!-- Início -->
        <a class="nav-link" href="painel.php">
          Início
        </a>

        <!-- Usuários -->
        <?php if ($tipo == "admin") : ?>

          <div class="nav-item dropdown">

            <a class="nav-link dropdown-toggle"
               href="#"
               data-toggle="dropdown">

              Usuários

            </a>

            <div class="dropdown-menu">

              <a class="dropdown-item"
                 href="cadastrarUsuario.php">

                Cadastrar Usuário

              </a>

              <a class="dropdown-item"
                 href="gerenciarUsuarios.php">

                Gerenciar Usuários

              </a>

            </div>

          </div>

        <?php endif; ?>

        <!-- Clientes -->
        <div class="nav-item dropdown">

          <a class="nav-link dropdown-toggle"
             href="#"
             data-toggle="dropdown">

            Clientes

          </a>

          <div class="dropdown-menu">

            <a class="dropdown-item"
               href="cadastroCliente.php">

              Cadastrar Cliente

            </a>

            <a class="dropdown-item"
               href="adicionarDepartamento.php">

              Adicionar departamento

            </a>

          </div>

        </div>

        <!-- Ordem de Serviço -->
        <div class="nav-item dropdown">

          <a class="nav-link dropdown-toggle active"
             href="#"
             data-toggle="dropdown">

            Ordem de Serviço

          </a>

          <div class="dropdown-menu">

            <a class="dropdown-item"
               href="cadastroOS.php">

              Cadastrar OS

            </a>

            <a class="dropdown-item active"
               href="consulta.php">

              Consultar OS

            </a>

          </div>

        </div>

        <!-- Sair -->
        <a class="nav-link text-danger"
           href="logout.php">

          Sair

        </a>

      </nav>

    </div>
  </header>

  <!-- CONTEÚDO -->
  <main role="main" class="inner cover">

    <div class="box-form">

      <h2 class="text-center mb-4">
        Editar Ordem de Serviço
      </h2>

      <!-- Cliente -->
      <p>
        <b>Cliente:</b>
        <?= $os["cliente_nome"]; ?>
      </p>

      <!-- Departamento -->
      <p>
        <b>Departamento:</b>
        <?= $os["departamento_nome"]; ?>
      </p>

      <hr>

      <form method="POST">

        <!-- Problema -->
        <label>Problema Relatado:</label>

        <textarea name="problema"
                  class="form-control mb-3"
                  required><?= $os["problema"]; ?></textarea>

        <!-- Serviço -->
        <label>Serviço Executado:</label>

        <textarea name="servico"
                  class="form-control mb-3"><?= $os["servico"]; ?></textarea>

        <!-- Valor -->
        <label>Valor:</label>

        <input type="number"
               step="0.01"
               name="valor"
               class="form-control mb-3"
               value="<?= $os["valor"]; ?>">

        <!-- Status -->
        <label>Status:</label>

        <select name="status"
                class="form-control mb-4">

          <option value="Aberta"
            <?= ($os["status"]=="Aberta") ? "selected" : ""; ?>>

            Aberta

          </option>

          <option value="Fechada"
            <?= ($os["status"]=="Fechada") ? "selected" : ""; ?>>

            Fechada

          </option>

        </select>

        <!-- BOTÕES -->
        <div class="btn-center">

          <button type="submit"
                  class="btn btn-success btn-lg">

            Salvar

          </button>

          <a href="consulta.php"
             class="btn btn-secondary btn-lg">

            Voltar

          </a>

        </div>

      </form>

    </div>

  </main>

  <!-- RODAPÉ -->
  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>Sistema OS © 2026</p>
    </div>
  </footer>

</div>

<!-- Scripts Bootstrap -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>