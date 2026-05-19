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

/* Pesquisa */
$busca = $_GET["busca"] ?? "";

/* ===============================
   CONSULTA SQL
================================ */

$sql = "
    SELECT
        os.*,
        c.nome AS cliente_nome,
        d.nome AS departamento_nome
    FROM ordens_servico os
    LEFT JOIN clientes c
        ON os.cliente_id = c.id
    LEFT JOIN departamentos d
        ON os.departamento_id = d.id
";

$params = array();

/* Buscar por ID da OS */
if(!empty($busca)) {

    $sql .= " WHERE os.id = $1";

    $params[] = $busca;
}

$sql .= " ORDER BY os.id DESC";

/* Executa consulta */
if(!empty($params)) {

    $result = pg_query_params(
        $conn,
        $sql,
        $params
    );

} else {

    $result = pg_query(
        $conn,
        $sql
    );
}
?>

<!doctype html>
<html lang="pt-br">

<head>

<meta charset="utf-8">

<title>
Consultar OS - Sistema OS
</title>

<!-- Bootstrap -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

<!-- Cover Template -->
<link rel="stylesheet"
href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

<style>

.cover-container {
  max-width: 100% !important;
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
  padding: 40px;
  border-radius: 15px;
  width: 98%;
  max-width: 1800px;
  margin: auto;
  text-align: left;
  box-shadow: 0px 0px 25px rgba(0,0,0,0.4);
}

table {
  width: 100%;
  table-layout: auto;
}

th,
td {
  font-size: 14px;
  vertical-align: middle !important;
  text-align: center;
  white-space: normal;
}

.acoes {
  width: 240px;
}

.btn-sm {
  width: 75px;
  font-size: 13px;
  padding: 5px;
  margin: 2px;
}

.form-inline {
  gap: 10px;
}

.badge {
  font-size: 13px;
  padding: 8px 12px;
}

</style>

</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">

<!-- TOPO -->
<header class="masthead mb-auto">

<div class="inner">

<!-- LOGO -->
<a href="painel.php"
class="masthead-brand">

<img src="imagens/logo.png"
alt="Logo Sistema OS">

</a>

<!-- MENU -->
<nav class="nav nav-masthead justify-content-center">

<!-- Início -->
<a class="nav-link"
href="painel.php">

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
<main role="main"
class="inner cover">

<div class="box-form">

<h2 class="text-center mb-4">
Consulta de Ordens de Serviço
</h2>

<!-- PESQUISA -->
<form method="GET"
class="form-inline justify-content-center mb-4">

<input type="number"
name="busca"
class="form-control"
placeholder="Digite o número da OS"
value="<?php echo $busca; ?>">

<button class="btn btn-dark"
type="submit">

Pesquisar

</button>

<a href="consulta.php"
class="btn btn-secondary">

Limpar

</a>

</form>

<!-- TABELA -->
<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="thead-dark">

<tr>

<th>ID</th>
<th>Cliente</th>
<th>Departamento</th>
<th>Status</th>
<th>Data</th>
<th class="acoes">Ações</th>

</tr>

</thead>

<tbody>

<?php if(pg_num_rows($result) > 0): ?>

<?php while($os = pg_fetch_assoc($result)): ?>

<tr>

<td>
<?= $os["id"] ?>
</td>

<td>
<?= $os["cliente_nome"] ?>
</td>

<td>
<?= $os["departamento_nome"] ?? "Não informado" ?>
</td>

<!-- STATUS -->
<td>

<?php if($os["status"] == "Aberta"): ?>

<span class="badge badge-success">
Aberta
</span>

<?php elseif($os["status"] == "Fechada"): ?>

<span class="badge badge-danger">
Fechada
</span>

<?php else: ?>

<span class="badge badge-secondary">
<?= $os["status"] ?>
</span>

<?php endif; ?>

</td>

<td>

<?= date(
    "d/m/Y H:i",
    strtotime($os["data_abertura"])
) ?>

</td>

<td>

<a href="visualizarOS.php?id=<?= $os["id"] ?>"
class="btn btn-primary btn-sm">

Ver

</a>

<a href="editarOS.php?id=<?= $os["id"] ?>"
class="btn btn-warning btn-sm">

Editar

</a>

<a href="excluirOS.php?id=<?= $os["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deseja excluir esta OS?');">

Excluir

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6">

Nenhuma OS encontrada.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

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
