<?php

session_start();
include("conexao.php");

/* ✅ Só admin pode entrar */
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];

/* ===========================
   Cadastrar usuário
=========================== */
if (isset($_POST["cadastrar"])) {

    $nome  = $_POST["nome"];
    $email = $_POST["email"];
    $tipoUser  = $_POST["tipo"];

    /* senha segura */
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipoUser);
    $stmt->execute();

    echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastrar Usuário</title>

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

    /* Espaçamento dos links */
    .nav-link {
      margin: 0 10px;
    }

    /* Logo */
    .masthead-brand img {
      height: 110px;
      margin-bottom: 15px;
    }

    /* Caixa do formulário */
    .box-form {
      background: white;
      color: black;
      padding: 35px;
      border-radius: 15px;
      max-width: 700px;
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
      width: 240px;
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
        <div class="nav-item dropdown">

          <a class="nav-link dropdown-toggle active"
             href="#"
             data-toggle="dropdown">
            Usuários
          </a>

          <div class="dropdown-menu">

            <a class="dropdown-item active"
               href="cadastrarUsuario.php">
              Cadastrar Usuário
            </a>

            <a class="dropdown-item"
               href="gerenciarUsuarios.php">
              Gerenciar Usuários
            </a>

          </div>
        </div>

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

  <!-- CONTEÚDO CENTRAL -->
  <main role="main" class="inner cover">

    <div class="box-form">

      <h2 class="text-center mb-4">
        Cadastro de Usuário
      </h2>

      <form method="POST">

        <label>Nome:</label>

        <input type="text"
               name="nome"
               class="form-control mb-3"
               required>

        <label>Email:</label>

        <input type="email"
               name="email"
               class="form-control mb-3"
               required>

        <label>Senha:</label>

        <input type="password"
               name="senha"
               class="form-control mb-3"
               required>

        <label>Tipo de Usuário:</label>

        <select name="tipo"
                class="form-control mb-4"
                required>

          <option value="usuario">
            Funcionário
          </option>

          <option value="admin">
            Administrador
          </option>

        </select>

        <!-- BOTÕES -->
        <div class="btn-center">

          <button type="submit"
                  name="cadastrar"
                  class="btn btn-dark btn-lg">

            Cadastrar

          </button>

          <a href="painel.php"
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