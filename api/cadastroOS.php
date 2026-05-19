<?php

session_start();

require_once __DIR__ . "/conexao.php";

/* Verifica login */
if (!isset($_SESSION["tipo"])) {

    header("Location: index.php");
    exit();
}

$tipo = $_SESSION["tipo"];
$nome = $_SESSION["nome"];

/* ===============================
   BUSCAR CLIENTES
================================ */

$sqlClientes = "
    SELECT *
    FROM clientes
    ORDER BY nome
";

$resultClientes = pg_query(
    $conn,
    $sqlClientes
);

/* ===============================
   BUSCAR DEPARTAMENTOS
================================ */

$departamentos = [];

if (
    isset($_POST["cliente_id"]) &&
    $_POST["cliente_id"] != ""
) {

    $clienteSelecionado = $_POST["cliente_id"];

    $sqlDep = "
        SELECT *
        FROM departamentos
        WHERE cliente_id = $1
        ORDER BY nome ASC
    ";

    $departamentos = pg_query_params(
        $conn,
        $sqlDep,
        array($clienteSelecionado)
    );
}

/* ===============================
   BUSCAR IMPRESSORAS
================================ */

$impressoras = [];

if (
    isset($_POST["departamento_id"]) &&
    $_POST["departamento_id"] != ""
) {

    $depSelecionado = $_POST["departamento_id"];

    $sqlImp = "
        SELECT *
        FROM impressoras
        WHERE departamento_id = $1
        ORDER BY modelo ASC
    ";

    $impressoras = pg_query_params(
        $conn,
        $sqlImp,
        array($depSelecionado)
    );
}

/* ===============================
   CADASTRAR ORDEM DE SERVIÇO
================================ */

if (isset($_POST["abrir_os"])) {

    $cliente_id      = $_POST["cliente_id"];
    $departamento_id = $_POST["departamento_id"];
    $impressora_id   = $_POST["impressora_id"];

    $problema = $_POST["problema"];
    $servico  = $_POST["servico"];
    $valor    = $_POST["valor"];

    $sqlOS = "
        INSERT INTO ordens_servico
        (
            cliente_id,
            departamento_id,
            impressora_id,
            problema,
            servico,
            valor,
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
            'Aberta',
            NOW()
        )
    ";

    $resultOS = pg_query_params(
        $conn,
        $sqlOS,
        array(
            $cliente_id,
            $departamento_id,
            $impressora_id,
            $problema,
            $servico,
            $valor
        )
    );

    if ($resultOS) {

        echo "<script>alert('Ordem de Serviço aberta com sucesso!');</script>";

    } else {

        echo "<script>alert('Erro ao abrir Ordem de Serviço!');</script>";
    }
}
?>

<!doctype html>
<html lang="pt-br">

<head>

<meta charset="utf-8">

<title>
Abrir Ordem de Serviço
</title>

<!-- Bootstrap -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

<!-- Cover Template -->
<link rel="stylesheet"
href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

<style>

body {
  overflow-x: hidden;
}

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
  padding: 30px;
  border-radius: 12px;
  max-width: 950px;
  margin: auto;
  text-align: left;
}

label {
  font-weight: bold;
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

<img src="imagens/logo.png"
alt="Logo Sistema OS">

</a>

<!-- MENU -->
<nav class="nav nav-masthead justify-content-center">

<a class="nav-link"
href="painel.php">

Início

</a>

<?php if ($tipo == "admin"): ?>

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

<!-- CONTEÚDO -->
<main role="main" class="inner cover">

<div class="box-form">

<h2 class="text-center mb-4">
Abrir Ordem de Serviço
</h2>

<form method="POST">

<!-- CLIENTE -->
<label>Empresa:</label>

<select name="cliente_id"
class="form-control mb-3"
onchange="this.form.submit()"
required>

<option value="">
Selecione...
</option>

<?php while ($c = pg_fetch_assoc($resultClientes)): ?>

<option value="<?= $c["id"] ?>"
<?= (isset($_POST["cliente_id"]) && $_POST["cliente_id"] == $c["id"]) ? "selected" : "" ?>>

<?= $c["nome"] ?>

</option>

<?php endwhile; ?>

</select>

<!-- DEPARTAMENTO -->
<label>Departamento:</label>

<select name="departamento_id"
class="form-control mb-3"
onchange="this.form.submit()"
required>

<?php if (!$departamentos): ?>

<option value="">
Selecione a empresa primeiro
</option>

<?php else: ?>

<option value="">
Selecione...
</option>

<?php while ($d = pg_fetch_assoc($departamentos)): ?>

<option value="<?= $d["id"] ?>"
<?= (isset($_POST["departamento_id"]) && $_POST["departamento_id"] == $d["id"]) ? "selected" : "" ?>>

<?= $d["nome"] ?>

</option>

<?php endwhile; ?>

<?php endif; ?>

</select>

<!-- IMPRESSORA -->
<label>Máquina / Impressora:</label>

<select name="impressora_id"
class="form-control mb-3"
required>

<?php if (!$impressoras): ?>

<option value="">
Selecione um departamento primeiro
</option>

<?php else: ?>

<option value="">
Selecione...
</option>

<?php while ($i = pg_fetch_assoc($impressoras)): ?>

<option value="<?= $i["id"] ?>">

<?= $i["modelo"] ?>
(Patrimônio: <?= $i["patrimonio"] ?>)

</option>

<?php endwhile; ?>

<?php endif; ?>

</select>

<!-- PROBLEMA -->
<label>Problema Relatado:</label>

<textarea name="problema"
class="form-control mb-3"
required></textarea>

<!-- SERVIÇO -->
<label>Serviço Executado:</label>

<textarea name="servico"
class="form-control mb-3"></textarea>

<!-- VALOR -->
<label>Valor:</label>

<input type="number"
step="0.01"
name="valor"
class="form-control mb-4">

<!-- BOTÃO -->
<button type="submit"
name="abrir_os"
class="btn btn-dark btn-lg btn-block">

Abrir OS

</button>

<a href="painel.php"
class="btn btn-secondary btn-block mt-3">

Voltar ao Painel

</a>

</form>

</div>

</main>

<!-- RODAPÉ -->
<footer class="mastfoot mt-auto">

<div class="inner">

<p>
Sistema OS © 2026 - GGS
</p>

</div>

</footer>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
