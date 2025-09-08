<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Filtros recebidos via GET
$filtro     = isset($_GET['busca'])      ? trim($_GET['busca'])      : '';
$plataforma = isset($_GET['plataforma']) ? trim($_GET['plataforma']) : '';
$tipo       = isset($_GET['tipo'])       ? trim($_GET['tipo'])       : '';
$status     = isset($_GET['status'])     ? trim($_GET['status'])     : '';

// Consulta principal com filtros
$sql = "SELECT * FROM vw_estoque_geral WHERE 1=1";
$params = [];

if ($filtro !== '') {
    $sql .= " AND nome_produto LIKE ?";
    $params[] = "%{$filtro}%";
}
if ($plataforma !== '') {
    $sql .= " AND plataforma_produto = ?";
    $params[] = $plataforma;
}
if ($tipo !== '') {
    $sql .= " AND tipo_produto = ?";
    $params[] = $tipo;
}
if ($status !== '') {
    $sql .= " AND status_estoque = ?";
    $params[] = $status;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filtros dinâmicos para os selects
$plataformas = $pdo->query("SELECT DISTINCT plataforma_produto FROM vw_estoque_geral WHERE plataforma_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$tipos       = $pdo->query("SELECT DISTINCT tipo_produto FROM vw_estoque_geral WHERE tipo_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$statuses    = $pdo->query("SELECT DISTINCT status_estoque FROM vw_estoque_geral WHERE status_estoque IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
?>
