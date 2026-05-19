<?php
session_start();
include("conexao.php");

/* Verifica login */
if(!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];
$nome = $_SESSION["nome"];

/* Buscar clientes */
$sqlClientes = "SELECT * FROM clientes ORDER BY nome";
$resultClientes = $conn->query($sqlClientes);

$sucesso = "";

/* ===========================
   Adicionar Departamento + Impressora
=========================== */
if(isset($_POST["cadastrar"])){

    $cliente_id         = $_POST["cliente_id"];
    $departamento_nome  = $_POST["departamento"];
    $modelo             = $_POST["modelo"];
    $patrimonio         = $_POST["patrimonio"];

    /* 1. Criar Departamento */
    $sqlDep = "INSERT INTO departamentos (cliente_id, nome)
               VALUES (?, ?)";

    $stmt = $conn->prepare($sqlDep);
    $stmt->bind_param("is", $cliente_id, $departamento_nome);
    $stmt->execute();

    $departamento_id = $conn->insert_id;

    /* 2. Criar Impressora */
    $sqlImp = "INSERT INTO impressoras (departamento_id, modelo, patrimonio)
               VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sqlImp);
    $stmt->bind_param("iss", $departamento_id, $modelo, $patrimonio);
    $stmt->execute();

    $sucesso = "Departamento e Impressora adicionados com sucesso!";
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Adicionar Departamento - Sistema OS</title>

  <!-- Bootstrap -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

  <!-- Cover Template -->
  <link rel="stylesheet"
        href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

  <style>

    /* Centralizar menu */
    .nav-masthead {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Dropdown menu escuro */
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
      max-width: 950px;
      margin: auto;
      text-align: left;
      box-shadow: 0px 0px 25px rgba(0,0,0,0.4);
    }

    label {
      font-weight: bold;
    }

    .section-title {
      font-size: 18px;
      font-weight: bold;
      margin-top: 25px;
      margin-bottom: 10px;
      border-bottom: 2px solid #ddd;
      padding-bottom: 5px;
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
        <?php if($tipo == "admin"): ?>

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

          <a class="nav-link dropdown-toggle active"
             href="#"
             data-toggle="dropdown">

            Clientes

          </a>

          <div class="dropdown-menu">

            <a class="dropdown-item"
               href="cadastroCliente.php">

              Cadastrar Cliente

            </a>

            <a class="dropdown-item active"
               href="adicionarDepartamento.php">

              Adicionar departamento

            </a>

          </div>

        </div>

        <!-- Ordem de Serviço -->
        <div class="nav-item dropdown">

          <a class="nav-link dropdown-toggle"
             href="#"
             data-toggle="dropdown">

            Ordem de Serviço

          </a>

          <div class="dropdown-menu">

            <a class="dropdown-item"
               href="cadastroOS.php">

              Cadastrar OS

            </a>

            <a class="dropdown-item"
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
        Adicionar Departamento e Impressora
      </h2>

      <?php if(!empty($sucesso)): ?>

        <div class="alert alert-success text-center">

          <?= $sucesso ?>

        </div>

      <?php endif; ?>

      <form method="POST">

        <!-- Cliente -->
        <div class="section-title">
          Escolha o Cliente
        </div>

        <div class="form-group">

          <label>Cliente:</label>

          <select name="cliente_id"
                  class="form-control"
                  required>

            <option value="">
              Selecione...
            </option>

            <?php while($c = $resultClientes->fetch_assoc()): ?>

              <option value="<?= $c["id"] ?>">

                <?= $c["nome"] ?>

              </option>

            <?php endwhile; ?>

          </select>

        </div>

        <!-- Departamento -->
        <div class="section-title">
          Novo Departamento
        </div>

        <div class="form-group">

          <label>Nome do Departamento:</label>

          <input type="text"
                 name="departamento"
                 class="form-control"
                 required>

        </div>

        <!-- Impressora -->
        <div class="section-title">
          Impressora do Departamento
        </div>

        <div class="row">

          <div class="col-md-6 form-group">

            <label>Modelo:</label>

            <input type="text"
                   name="modelo"
                   class="form-control"
                   required>

          </div>

          <div class="col-md-6 form-group">

            <label>Patrimônio:</label>

            <input type="text"
                   name="patrimonio"
                   class="form-control">

          </div>

        </div>

        <!-- BOTÕES -->
        <div class="text-center mt-4">

          <button type="submit"
                  name="cadastrar"
                  class="btn btn-dark btn-lg px-5">

            Adicionar ao Cliente

          </button>

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