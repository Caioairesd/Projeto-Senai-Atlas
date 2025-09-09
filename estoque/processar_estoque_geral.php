<?php
require_once '../config/conexao.php'; // Inclui arquivo de configuração de conexão com o banco de dados
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema

// Inicializa filtros da busca recebidos via GET com sanitização básica
$filtro     = isset($_GET['busca'])      ? trim($_GET['busca'])      : ''; // Filtro de texto para nome do produto
$plataforma = isset($_GET['plataforma']) ? trim($_GET['plataforma']) : ''; // Filtro por plataforma
$tipo       = isset($_GET['tipo'])       ? trim($_GET['tipo'])       : ''; // Filtro por tipo/categoria
$status     = isset($_GET['status'])     ? trim($_GET['status'])     : ''; // Filtro por status de estoque

// Consulta principal com filtros dinâmicos
$sql = "SELECT * FROM vw_estoque_geral WHERE 1=1"; // 1=1 para facilitar concatenação de condições
$params = []; // Array para armazenar parâmetros da consulta

// Adiciona condições de filtro conforme preenchimento
if ($filtro !== '') {
    $sql .= " AND nome_produto LIKE ?"; // Filtra por nome do produto
    $params[] = "%{$filtro}%"; // Adiciona parâmetro com wildcards
}
if ($plataforma !== '') {
    $sql .= " AND plataforma_produto = ?"; // Filtra por plataforma
    $params[] = $plataforma; // Adiciona parâmetro
}
if ($tipo !== '') {
    $sql .= " AND tipo_produto = ?"; // Filtra por tipo/categoria
    $params[] = $tipo; // Adiciona parâmetro
}
if ($status !== '') {
    $sql .= " AND status_estoque = ?"; // Filtra por status de estoque
    $params[] = $status; // Adiciona parâmetro
}

// Prepara e executa a consulta com os parâmetros
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém resultados como array associativo

// Busca dados distintos para preencher os selects de filtro
$plataformas = $pdo->query("SELECT DISTINCT plataforma_produto FROM vw_estoque_geral WHERE plataforma_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Plataformas distintas
$tipos       = $pdo->query("SELECT DISTINCT tipo_produto FROM vw_estoque_geral WHERE tipo_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Tipos distintos
$statuses    = $pdo->query("SELECT DISTINCT status_estoque FROM vw_estoque_geral WHERE status_estoque IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Status distintos
?>