<?php
require_once '../config/conexao.php';

// Captura filtros
$filtroCliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : '';
$filtroStatus  = isset($_GET['status']) ? trim($_GET['status']) : '';
$filtroDataIni = isset($_GET['data_ini']) ? trim($_GET['data_ini']) : '';
$filtroDataFim = isset($_GET['data_fim']) ? trim($_GET['data_fim']) : '';

$sql = "SELECT * FROM vw_pedidos_resumo WHERE 1=1";
$params = [];

if ($filtroCliente !== '') {
    $sql .= " AND nome_cliente LIKE ?";
    $params[] = "%{$filtroCliente}%";
}

if ($filtroStatus !== '') {
    $sql .= " AND status_pedido = ?";
    $params[] = $filtroStatus;
}

if ($filtroDataIni !== '' && $filtroDataFim !== '') {
    $sql .= " AND data_pedido BETWEEN ? AND ?";
    $params[] = $filtroDataIni;
    $params[] = $filtroDataFim;
} elseif ($filtroDataIni !== '') {
    $sql .= " AND data_pedido >= ?";
    $params[] = $filtroDataIni;
} elseif ($filtroDataFim !== '') {
    $sql .= " AND data_pedido <= ?";
    $params[] = $filtroDataFim;
}

$sql .= " ORDER BY id_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);