<?php
session_start();

require_once __DIR__ . "/conexao.php";

/* Verifica login */
if(!isset($_SESSION["tipo"])) {
    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];
$nome = $_SESSION["nome"];

/* ===========================
   BUSCAR CLIENTES
=========================== */

$sqlClientes = "SELECT * FROM clientes ORDER BY nome";

$resultClientes = pg_query($conn, $sqlClientes);

$sucesso = "";

/* ===========================
   ADICIONAR DEPARTAMENTO + IMPRESSORA
=========================== */

if(isset($_POST["cadastrar"])){

    $cliente_id         = $_POST["cliente_id"];
    $departamento_nome  = $_POST["departamento"];
    $modelo             = $_POST["modelo"];
    $patrimonio         = $_POST["patrimonio"];

    /* ===========================
       1. CRIAR DEPARTAMENTO
    =========================== */

    $sqlDep = "
        INSERT INTO departamentos (cliente_id, nome)
        VALUES ($1, $2)
        RETURNING id
    ";

    $resultDep = pg_query_params(
        $conn,
        $sqlDep,
        array($cliente_id, $departamento_nome)
    );

    if($resultDep){

        $departamento = pg_fetch_assoc($resultDep);

        $departamento_id = $departamento["id"];

        /* ===========================
           2. CRIAR IMPRESSORA
        =========================== */

        $sqlImp = "
            INSERT INTO impressoras
            (departamento_id, modelo, patrimonio)
            VALUES ($1, $2, $3)
        ";

        $resultImp = pg_query_params(
            $conn,
            $sqlImp,
            array(
                $departamento_id,
                $modelo,
                $patrimonio
            )
        );

        if($resultImp){

            $sucesso = "Departamento e Impressora adicionados com sucesso!";

        } else {

            $sucesso = "Erro ao adicionar impressora.";
        }

    } else {

        $sucesso = "Erro ao criar departamento.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">

  <title>
    Adicionar Departamento - Sistema OS
  </title>

  <!-- Bootstrap -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

  <!-- Cover -->
  <link rel="stylesheet"
        href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

  <style>

    .nav-masthead {
      display: flex;
      justify-content: center;
      align-items: center;
    }

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

    .masthead-brand img {
      height: 110px;
      margin-bottom: 15px;
    }

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

<header class="masthead mb-auto">

<div class="inner">

<a href="painel.php" class="masthead-brand">
<img src="imagens/logo.png" alt="Logo Sistema OS">
</a>

<nav class="nav nav-masthead justify-content-center">

<a class="nav-link" href="painel.php">
Início
</a>

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

<a class="nav-link text-danger"
href="logout.php">

Sair

</a>

</nav>

</div>

</header>

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

<?php while($c = pg_fetch_assoc($resultClientes)): ?>

<option value="<?= $c["id"] ?>">

<?= $c["nome"] ?>

</option>

<?php endwhile; ?>

</select>

</div>

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

<footer class="mastfoot mt-auto">
<div class="inner">
<p>Sistema OS © 2026</p>
</div>
</footer>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
