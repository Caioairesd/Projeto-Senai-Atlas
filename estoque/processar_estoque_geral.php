<?php
// processa_estoque_geral.php
require_once '../config/conexao.php';

// Filtro opcional
$filtro = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "SELECT * FROM vw_estoque_geral";
$params = [];

if ($filtro !== '') {
    $sql .= " WHERE nome_produto LIKE ?";
    $params[] = "%{$filtro}%";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna como JSON para o front
header('Content-Type: application/json');
echo json_encode($estoque);
