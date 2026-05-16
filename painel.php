<?php
session_start();

/* Verifica login */
if(!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];
$nome = $_SESSION["nome"];
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Painel - Sistema OS</title>

  <!-- Bootstrap 4 -->
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
  height: 110px;   /* Logo maior */
  margin-bottom: 15px;
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
        <a class="nav-link active" href="painel.php">Início</a>

        <!-- Usuários (somente admin) -->
        <?php if($tipo == "admin"): ?>
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="#"
               id="usuariosDropdown"
               role="button"
               data-toggle="dropdown">
              Usuários
            </a>

            <div class="dropdown-menu">
              <a class="dropdown-item" href="cadastrarUsuario.php">
                Cadastrar Usuário
              </a>

              <a class="dropdown-item" href="gerenciarUsuarios.php">
                Gerenciar Usuários
              </a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Clientes -->
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
             href="#"
             id="clientesDropdown"
             role="button"
             data-toggle="dropdown">
            Clientes
          </a>

          <div class="dropdown-menu">
            <a class="dropdown-item" href="cadastroCliente.php">
              Cadastrar Cliente
            </a>

            <a class="dropdown-item" href="adicionarDepartamento.php">
              Adicionar departamento
            </a>
          </div>
        </div>

        <!-- Ordem de Serviço -->
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
             href="#"
             id="osDropdown"
             role="button"
             data-toggle="dropdown">
            Ordem de Serviço
          </a>

          <div class="dropdown-menu">
            <a class="dropdown-item" href="cadastroOS.php">
              Cadastrar OS
            </a>

            <a class="dropdown-item" href="consulta.php">
              Consultar OS
            </a>
          </div>
        </div>

        <!-- Sair -->
        <a class="nav-link text-danger" href="logout.php">Sair</a>

      </nav>
    </div>
  </header>


  <!-- CONTEÚDO CENTRAL -->
  <main role="main" class="inner cover">

    <h1 class="cover-heading">Bem-vindo, <?php echo $nome; ?></h1>

    <?php if($tipo == "admin"): ?>
      <p class="lead">
        Você está logado como Administrador.
      </p>
    <?php else: ?>
      <p class="lead">
        Você está logado como Funcionário.
      </p>
    <?php endif; ?>

    <p class="lead">
      Use o menu acima para acessar as funções do sistema.
    </p>

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
