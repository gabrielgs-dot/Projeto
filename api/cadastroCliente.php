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

$sucesso = "";

/* ============================
   CADASTRAR CLIENTE COMPLETO
============================ */
if(isset($_POST["cadastrar"])){

    /* Cliente */
    $nomeCliente = $_POST["nome"];
    $telefone    = $_POST["telefone"];
    $endereco    = $_POST["endereco"];
    $email       = $_POST["email"];

    $sqlCliente = "INSERT INTO clientes (nome, telefone, endereco, email)
                   VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sqlCliente);
    $stmt->bind_param("ssss", $nomeCliente, $telefone, $endereco, $email);
    $stmt->execute();

    $cliente_id = $conn->insert_id;


    /* Departamento */
    $departamento_nome = $_POST["departamento"];

    $sqlDep = "INSERT INTO departamentos (cliente_id, nome)
               VALUES (?, ?)";

    $stmt = $conn->prepare($sqlDep);
    $stmt->bind_param("is", $cliente_id, $departamento_nome);
    $stmt->execute();

    $departamento_id = $conn->insert_id;


    /* Impressora */
    $modelo     = $_POST["modelo"];
    $patrimonio = $_POST["patrimonio"];

    $sqlImp = "INSERT INTO impressoras (departamento_id, modelo, patrimonio)
               VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sqlImp);
    $stmt->bind_param("iss", $departamento_id, $modelo, $patrimonio);
    $stmt->execute();

    $sucesso = "Cliente, Departamento e Impressora cadastrados com sucesso!";
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastrar Cliente - Sistema OS</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

  <!-- Cover Template -->
  <link rel="stylesheet"
        href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

  <style>
    body {
      background-color: #333;
    }

    /* Caixa principal mais larga */
    .box-form {
      background: white;
      color: black;
      padding: 35px;
      border-radius: 15px;
      max-width: 1200px;
      margin: auto;
      margin-top: 50px;
      text-align: left;
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

    /* Logo igual painel */
    .masthead-brand img {
      height: 90px;
      margin-bottom: 15px;
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

    .nav-link {
      margin: 0 10px;
    }
  </style>
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">

  <!-- TOPO IGUAL PAINEL -->
  <header class="masthead mb-auto">
    <div class="inner">

      <!-- LOGO -->
      <a href="painel.php" class="masthead-brand">
        <img src="imagens/logo.png" alt="Logo Sistema OS">
      </a>

      <!-- MENU -->
      <nav class="nav nav-masthead justify-content-center">

        <a class="nav-link" href="painel.php">Início</a>

        <?php if($tipo == "admin"): ?>
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
              Usuários
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="cadastrarUsuario.php">Cadastrar Usuário</a>
              <a class="dropdown-item" href="gerenciarUsuarios.php">Gerenciar Usuários</a>
            </div>
          </div>
        <?php endif; ?>

        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown">
            Clientes
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item active" href="cadastroCliente.php">
              Cadastrar Cliente
            </a>
            <a class="dropdown-item" href="adicionarDepartamento.php">
              Adicionar departamento
            </a>
          </div>
        </div>

        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
            Ordem de Serviço
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="cadastroOS.php">Cadastrar OS</a>
            <a class="dropdown-item" href="consulta.php">Consultar OS</a>
          </div>
        </div>

        <a class="nav-link text-danger" href="logout.php">Sair</a>

      </nav>
    </div>
  </header>


  <!-- FORMULÁRIO -->
  <main role="main" class="inner cover">

    <div class="box-form">

      <h2 class="text-center mb-4">
        Cadastro Completo de Cliente
      </h2>

      <?php if(!empty($sucesso)): ?>
        <div class="alert alert-success text-center">
          <?= $sucesso ?>
        </div>
      <?php endif; ?>

      <form method="POST">

        <!-- CLIENTE -->
        <div class="section-title">Dados do Cliente</div>

        <div class="row">
          <div class="col-md-6 form-group">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
          </div>

          <div class="col-md-6 form-group">
            <label>Telefone:</label>
            <input type="text" name="telefone" class="form-control">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 form-group">
            <label>Endereço:</label>
            <input type="text" name="endereco" class="form-control">
          </div>

          <div class="col-md-6 form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control">
          </div>
        </div>


        <!-- DEPARTAMENTO -->
        <div class="section-title">Departamento</div>

        <div class="row">
          <div class="col-md-12 form-group">
            <label>Nome do Departamento:</label>
            <input type="text" name="departamento" class="form-control" required>
          </div>
        </div>


        <!-- IMPRESSORA -->
        <div class="section-title">Impressora</div>

        <div class="row">
          <div class="col-md-6 form-group">
            <label>Modelo:</label>
            <input type="text" name="modelo" class="form-control" required>
          </div>

          <div class="col-md-6 form-group">
            <label>Patrimônio:</label>
            <input type="text" name="patrimonio" class="form-control">
          </div>
        </div>


        <!-- BOTÃO -->
        <div class="text-center mt-4">
          <button type="submit" name="cadastrar"
                  class="btn btn-dark btn-lg px-5">
            Cadastrar Tudo
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
