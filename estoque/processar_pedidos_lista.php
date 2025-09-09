<?php
// Inclui arquivo de configuração de conexão com o banco de dados
require_once '../config/conexao.php';

// Captura e sanitiza filtros recebidos via GET
$filtroCliente = isset($_GET['cliente']) ? trim($_GET['cliente']) : ''; // Filtro por nome do cliente
$filtroStatus  = isset($_GET['status']) ? trim($_GET['status']) : ''; // Filtro por status do pedido
$filtroDataIni = isset($_GET['data_ini']) ? trim($_GET['data_ini']) : ''; // Filtro por data inicial
$filtroDataFim = isset($_GET['data_fim']) ? trim($_GET['data_fim']) : ''; // Filtro por data final

// Monta SQL base usando a view vw_pedidos_resumo
$sql = "SELECT * FROM vw_pedidos_resumo WHERE 1=1"; // 1=1 para facilitar concatenação de condições
$params = []; // Array para armazenar parâmetros da consulta

// Aplica filtro por nome do cliente se fornecido
if ($filtroCliente !== '') {
    $sql .= " AND nome_cliente LIKE ?"; // Filtra por nome do cliente
    $params[] = "%{$filtroCliente}%"; // Adiciona parâmetro com wildcards
}

// Aplica filtro por status do pedido se fornecido
if ($filtroStatus !== '') {
    $sql .= " AND status_pedido = ?"; // Filtra por status
    $params[] = $filtroStatus; // Adiciona parâmetro
}

// Aplica filtros por data
if ($filtroDataIni !== '' && $filtroDataFim !== '') {
    // Filtro por intervalo de datas
    $sql .= " AND data_pedido BETWEEN ? AND ?";
    $params[] = $filtroDataIni;
    $params[] = $filtroDataFim;
} elseif ($filtroDataIni !== '') {
    // Filtro por data inicial apenas
    $sql .= " AND data_pedido >= ?";
    $params[] = $filtroDataIni;
} elseif ($filtroDataFim !== '') {
    // Filtro por data final apenas
    $sql .= " AND data_pedido <= ?";
    $params[] = $filtroDataFim;
}

// Ordena por ID do pedido em ordem decrescente (mais recentes primeiro)
$sql .= " ORDER BY id_pedido DESC";

// Prepara e executa a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
// Obtém todos os resultados
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
