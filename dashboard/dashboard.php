<?php
session_start();
require_once '../cruds/conexao.php';

// Verifica se o usuário está logado e se o perfil está definido
if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil'])) {
    header('Location: ../Login/login.php');
    exit();
}

$id_perfil = $_SESSION['perfil'];

// Busca o nome do perfil no banco
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o perfil
$nome_perfil = $perfil && isset($perfil['nome_perfil']) ? $perfil['nome_perfil'] : 'Perfil não encontrado';

// Definição das permissões por perfil com base nos arquivos da imagem
$permissoes = [
    1 => [
        "Cadastrar" => ["criar_cliente.php", "criar_fornecedor.php", "criar_funcionario.php", "criar_produto.php"],
        "Editar" => ["editar_cliente.php", "editar_fornecedor.php", "editar_funcionario.php", "editar_produto.php"],
        "Excluir" => ["excluir_cliente.php", "excluir_fornecedor.php", "excluir_funcionario.php", "excluir_produto.php"],
        "Visualizar" => ["visualizar_cliente.php", "visualizar_fornecedor.php", "visualizar_funcionario.php", "visualizar_produto.php"],
        "Detalhes" => ["detalhes_cliente.php", "detalhes_fornecedor.php", "detalhes_produto.php"]
    ],
    // ADICIONAR PERMISSÕES 2 E 3 CONFORME NECESSÁRIO
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Atlas</title>
    <link rel="stylesheet" href="../public/assets/css/style.css" />
</head>

<body>
    <header>
        <div class="saudacao">
            <h2 align="center">Bem-vindo, <?= $_SESSION["usuario"]; ?>! Perfil: <?= $nome_perfil; ?></h2>
        </div>
        <form action="../Login/logout.php" method="post" style="margin: 0;">
            <button type="submit">Logout</button>
        </form>
        </div>
    </header>

    <nav class="sidebar">
        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= $categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?></a>
                            </li>

                        <?php endforeach; ?>

                    </ul>
                </li>
            <?php endforeach; ?>
            <li class="logout">
                <form action="../Login/logout.php" method="post" style="margin: 0;">
                    <button type="submit">Logout</button>
                </form>
            </li>
            </form>
        </ul>
    </nav>
</body>

</html>