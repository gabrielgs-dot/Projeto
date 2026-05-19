<?php
session_start();

/* ============================
   VERIFICA LOGIN
============================ */
if (!isset($_SESSION["logado"])) {

    header("Location: index.php");
    exit();
}

/* Conexão */
include("conexao.php");

/* Dados usuário */
$nome = $_SESSION["nome"];
$tipo = $_SESSION["tipo"];
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">

    <title>Painel - Sistema OS</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

    <!-- Cover -->
    <link rel="stylesheet"
          href="https://getbootstrap.com/docs/4.0/examples/cover/cover.css">

    <style>

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

        /* Caixa central */
        .box {
            background: white;
            color: black;
            padding: 40px;
            border-radius: 15px;
            max-width: 800px;
            margin: auto;
            text-align: center;

            box-shadow: 0px 0px 25px rgba(0,0,0,0.4);
        }

        .box h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .box p {
            font-size: 18px;
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
                <a class="nav-link active" href="painel.php">

                    Início

                </a>

                <!-- Usuários -->
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

                <!-- Ordem Serviço -->
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

                <!-- Logout -->
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

            <h1>

                Bem-vindo, <?= $nome ?>

            </h1>

            <p>

                Tipo de usuário:
                <b><?= ucfirst($tipo) ?></b>

            </p>

            <hr>

            <p>

                Sistema de Ordem de Serviço funcionando com PostgreSQL.

            </p>

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
