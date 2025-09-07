<?php
session_start();
require_once '../config/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Garante exceptions no PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 0) Verifica método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<script>alert('Método inválido.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }

    // 1) Captura inputs
    $produto_id        = $_POST['produto_id'] ?? null;
    $qtde_item         = $_POST['qtde_estoque'] ?? null;
    $observacao        = $_POST['observacao_estoque'] ?? '';
    $cliente_id        = $_POST['cliente_id'] ?? null;
    $funcionario_id    = $_SESSION['funcionario_id'] ?? null;
    $data_movimentacao = date('Y-m-d H:i:s');

    // 2) Validações
    if (!is_numeric($produto_id) || !is_numeric($qtde_item)) {
        echo "<script>alert('Dados inválidos.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }
    if ((int)$qtde_item <= 0) {
        echo "<script>alert('A quantidade deve ser maior que zero.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }
    if (empty($cliente_id)) {
        echo "<script>alert('Selecione um cliente.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }

    error_log('cheguei_validacoes');

    $pdo->beginTransaction();

    // 3) Verifica produto e preço
    $stmt = $pdo->prepare("SELECT preco_produto FROM produto WHERE id_produto = ?");
    $stmt->execute([$produto_id]);
    $preco_unitario = $stmt->fetchColumn();
    if ($preco_unitario === false) {
        throw new Exception('Produto não encontrado.');
    }

    // 4) Cria pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, data_pedido, status_pedido) VALUES (?, ?, 'Pendente')");
    $stmt->execute([$cliente_id, $data_movimentacao]);
    $pedido_id = $pdo->lastInsertId();
    error_log('cheguei_criou_pedido id=' . $pedido_id);

    // 5) Insere item do pedido
    $stmt = $pdo->prepare("INSERT INTO item_pedido (pedido_id, produto_id, qtde_item, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pedido_id, $produto_id, $qtde_item, $preco_unitario]);
    error_log('cheguei_item');

    // 6) Registra movimentação de saída
    $stmt = $pdo->prepare("INSERT INTO movimentacao (
            tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao
        ) VALUES ('Saída', ?, ?, ?, ?, ?)");
    $stmt->execute([$qtde_item, $data_movimentacao, $produto_id, $funcionario_id, $observacao]);
    error_log('cheguei_mov');

    $pdo->commit();
    error_log('commit_ok');

    echo "<script>alert('Saída e pedido gerados com sucesso!'); window.location.href = 'pedido_detalhe.php?id={$pedido_id}';</script>";
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('pdo_exception: ' . $e->getMessage());

    if (str_contains($e->getMessage(), 'Estoque insuficiente')) {
        echo "<script>alert('Erro: Estoque insuficiente para o produto selecionado.'); window.location.href = 'estoque_saida.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar saída: ".htmlspecialchars($e->getMessage())."'); window.location.href = 'estoque_saida.php';</script>";
    }
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('exception: ' . $e->getMessage());
    echo "<script>alert('Erro: ".htmlspecialchars($e->getMessage())."'); window.location.href = 'estoque_saida.php';</script>";
    exit;
}
