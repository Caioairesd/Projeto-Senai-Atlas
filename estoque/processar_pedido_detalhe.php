<?php
require_once '../config/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Atualiza status se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $novoStatus = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE pedidos SET status_pedido = ? WHERE id_pedido = ?");
    $stmt->execute([$novoStatus, $id]);

    // Redireciona para evitar reenvio de formulário
    header("Location: pedido_detalhe.php?id=$id");
    exit;
}

// Busca dados do pedido
$sqlPedido = "SELECT p.*, c.nome_cliente 
              FROM pedidos p
              JOIN cliente c ON p.cliente_id = c.id_cliente
              WHERE p.id_pedido = ?";
$stmt = $pdo->prepare($sqlPedido);
$stmt->execute([$id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

// Busca itens do pedido
$sqlItens = "SELECT i.*, pr.nome_produto 
             FROM item_pedido i
             JOIN produto pr ON i.produto_id = pr.id_produto
             WHERE i.pedido_id = ?";
$stmt = $pdo->prepare($sqlItens);
$stmt->execute([$id]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);