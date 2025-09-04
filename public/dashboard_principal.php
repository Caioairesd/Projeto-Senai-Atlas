<?php
session_start();
include '../assets/sidebar.php';
require_once '../config/conexao.php';

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
    2 => [
        "Cadastrar" => [],
        "Editar" => [],
        "Excluir" => [],
        "Visualizar" => ["visualizar_cliente.php", "visualizar_fornecedor.php", "visualizar_produto.php"],
        "Detalhes" => ["detalhes_cliente.php", "detalhes_fornecedor.php", "detalhes_produto.php"]
    ],
    3 => [
        "Cadastrar" => ["criar_fornecedor.php", "criar_produto.php"],
        "Editar" => ["editar_fornecedor.php", "editar_produto.php"],
        "Excluir" => ["excluir_produto.php"],
        "Visualizar" => ["visualizar_fornecedor.php","visualizar_produto.php"],
        "Detalhes" => ["detalhes_fornecedor.php", "detalhes_produto.php"]
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
    <link rel="stylesheet" href="../assets/style.css"/>
</head>

<body>
    <header>
        <div class="saudacao">
            <h2 align="center">Bem-vindo, <?= $_SESSION["usuario"]; ?>! Perfil: <?= $nome_perfil; ?></h2>
        </div>
    </header>
</body>
</html>