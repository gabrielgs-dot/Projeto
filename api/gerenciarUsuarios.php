<?php
session_start();
include("conexao.php");

/* ============================
   ✅ SOMENTE ADMIN
============================ */
if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit();
}

/* ============================
   ✅ EXCLUIR USUÁRIO
============================ */
if (isset($_GET["excluir"])) {

    $idExcluir = intval($_GET["excluir"]);

    // Admin não pode excluir ele mesmo
    if ($idExcluir != $_SESSION["admin"]) {

        $sql = "DELETE FROM usuarios WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idExcluir);
        $stmt->execute();
    }

    header("Location: gerenciarUsuarios.php");
    exit();
}

/* ============================
   ✅ EDITAR USUÁRIO
============================ */
if (isset($_POST["salvar"])) {

    $id    = intval($_POST["id"]);
    $nome  = $_POST["nome"];
    $email = $_POST["email"];
    $tipo  = $_POST["tipo"];

    $sql = "UPDATE usuarios 
            SET nome=?, email=?, tipo=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $email, $tipo, $id);
    $stmt->execute();

    header("Location: gerenciarUsuarios.php");
    exit();
}

/* ============================
   ✅ LISTAR USUÁRIOS
============================ */
$result = $conn->query("SELECT * FROM usuarios ORDER BY id DESC");
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Gerenciar Usuários</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

    <!-- Cover -->
    <link rel="stylesheet"
          href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

    <style>

        /* Corrige largura do cover */
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

        /* Caixa principal */
        .box {
            background: white;
            color: black;
            padding: 35px;
            border-radius: 15px;

            width: 98%;
            max-width: 1700px;

            margin: auto;

            box-shadow: 0px 0px 25px rgba(0,0,0,0.4);
        }

        /* Título */
        .title {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 35px;
        }

        /* Tabela */
        table {
            width: 100%;
            background: white;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
            text-align: center;
            white-space: nowrap;
        }

        /* Inputs */
        .form-control {
            min-width: 140px;
        }

        /* Select */
        select.form-control {
            min-width: 120px;
        }

        /* Botões */
        .btn {
            white-space: nowrap;
        }

        /* Responsividade */
        .table-responsive {
            overflow-x: auto;
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

                        <a class="dropdown-item"
                           href="cadastrarUsuario.php">

                            Cadastrar Usuário

                        </a>

                        <a class="dropdown-item active"
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

    <!-- CONTEÚDO -->
    <main role="main" class="inner cover">

        <div class="box">

            <div class="title">
                Gerenciar Usuários
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-dark">

                    <tr>

                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Salvar</th>
                        <th>Excluir</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php while ($u = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= $u["id"] ?>
                            </td>

                            <!-- FORM -->
                            <form method="POST">

                                <input type="hidden"
                                       name="id"
                                       value="<?= $u["id"] ?>">

                                <!-- Nome -->
                                <td>

                                    <input type="text"
                                           name="nome"
                                           value="<?= $u["nome"] ?>"
                                           class="form-control"
                                           required>

                                </td>

                                <!-- Email -->
                                <td>

                                    <input type="email"
                                           name="email"
                                           value="<?= $u["email"] ?>"
                                           class="form-control"
                                           required>

                                </td>

                                <!-- Tipo -->
                                <td>

                                    <select name="tipo"
                                            class="form-control">

                                        <option value="usuario"
                                            <?= ($u["tipo"]=="usuario") ? "selected" : "" ?>>

                                            Funcionário

                                        </option>

                                        <option value="admin"
                                            <?= ($u["tipo"]=="admin") ? "selected" : "" ?>>

                                            Admin

                                        </option>

                                    </select>

                                </td>

                                <!-- Salvar -->
                                <td>

                                    <button type="submit"
                                            name="salvar"
                                            class="btn btn-success">

                                        Salvar

                                    </button>

                                </td>

                            </form>

                            <!-- Excluir -->
                            <td>

                                <?php if ($u["id"] != $_SESSION["admin"]): ?>

                                    <a href="gerenciarUsuarios.php?excluir=<?= $u["id"] ?>"
                                       class="btn btn-danger"
                                       onclick="return confirm('Deseja excluir este usuário?')">

                                        Excluir

                                    </a>

                                <?php else: ?>

                                    <span class="font-weight-bold text-muted">

                                        Você

                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

            <!-- VOLTAR -->
            <div class="text-center mt-4">

                <a href="painel.php"
                   class="btn btn-secondary btn-lg px-5">

                    Voltar ao Painel

                </a>

            </div>

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