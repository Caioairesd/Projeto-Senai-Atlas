<?php
// Inclui arquivo de configuração de conexão com o banco de dados
require_once '../config/conexao.php';

// Obtém e sanitiza o ID do pedido da URL, convertendo para inteiro
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Processa atualização de status se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $novoStatus = $_POST['status']; // Novo status do pedido
    // Prepara e executa query para atualizar status
    $stmt = $pdo->prepare("UPDATE pedidos SET status_pedido = ? WHERE id_pedido = ?");
    $stmt->execute([$novoStatus, $id]);

    // Redireciona para a mesma página para evitar reenvio do formulário (padrão PRG - Post/Redirect/Get)
    header("Location: pedido_detalhe.php?id=$id");
    exit; // Termina a execução do script
}

// Busca dados principais do pedido
$sqlPedido = "SELECT p.*, c.nome_cliente 
              FROM pedidos p
              JOIN cliente c ON p.cliente_id = c.id_cliente -- Junta com tabela de clientes
              WHERE p.id_pedido = ?"; // Filtra pelo ID do pedido
$stmt = $pdo->prepare($sqlPedido); // Prepara a consulta
$stmt->execute([$id]); // Executa com o ID como parâmetro
$pedido = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do pedido

// Busca itens do pedido
$sqlItens = "SELECT i.*, pr.nome_produto 
             FROM item_pedido i
             JOIN produto pr ON i.produto_id = pr.id_produto -- Junta com tabela de produtos
             WHERE i.pedido_id = ?"; // Filtra pelo ID do pedido
$stmt = $pdo->prepare($sqlItens); // Prepara a consulta
$stmt->execute([$id]); // Executa com o ID como parâmetro
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os itens do pedido