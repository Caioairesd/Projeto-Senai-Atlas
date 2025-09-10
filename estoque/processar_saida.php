<?php
session_start();
require_once '../config/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Garante exceptions no PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: estoque_saida.php?msg=Método inválido.&type=error");
        exit;
    }

    $produto_id = $_POST['produto_id'] ?? null;
    $qtde_item = $_POST['qtde_estoque'] ?? null;
    $observacao = $_POST['observacao_estoque'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? null;
    $funcionario_id = $_SESSION['funcionario_id'] ?? null;
    $data_movimentacao = date('Y-m-d H:i:s');

    if (!is_numeric($produto_id) || !is_numeric($qtde_item)) {
        header("Location: estoque_saida.php?msg=Dados inválidos.&type=error");
        exit;
    }
    if ((int) $qtde_item <= 0) {
        header("Location: estoque_saida.php?msg=A quantidade deve ser maior que zero.&type=error");
        exit;
    }
    if (empty($cliente_id)) {
        header("Location: estoque_saida.php?msg=Selecione um cliente.&type=error");
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT preco_produto FROM produto WHERE id_produto = ? AND ativo = 1");
    $stmt->execute([$produto_id]);
    $preco_unitario = $stmt->fetchColumn();
    if ($preco_unitario === false) {
        throw new Exception('Produto não encontrado.');
    }

    $stmt = $pdo->prepare("SELECT SUM(CASE WHEN tipo_movimentacao = 'Entrada' THEN quantidade ELSE -quantidade END) AS saldo_estoque
                           FROM movimentacao
                           WHERE produto_id = ?");
    $stmt->execute([$produto_id]);
    $saldo_estoque = (int) $stmt->fetchColumn();

    if ($saldo_estoque < (int) $qtde_item) {
        throw new Exception('Estoque insuficiente para realizar a saída.');
    }

    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, data_pedido, status_pedido) VALUES (?, ?, 'Pendente')");
    $stmt->execute([$cliente_id, $data_movimentacao]);
    $pedido_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO item_pedido (pedido_id, produto_id, qtde_item, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pedido_id, $produto_id, $qtde_item, $preco_unitario]);

    $stmt = $pdo->prepare("INSERT INTO movimentacao (
            tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao
        ) VALUES ('Saída', ?, ?, ?, ?, ?)");
    $stmt->execute([$qtde_item, $data_movimentacao, $produto_id, $funcionario_id, $observacao]);

    $pdo->commit();

    header("Location: pedido_detalhe.php?id={$pedido_id}&msg=Saída e pedido gerados com sucesso!&type=success");
    exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $mensagem = str_contains($e->getMessage(), 'Estoque insuficiente')
        ? 'Erro: Estoque insuficiente para o produto selecionado.'
        : 'Erro ao registrar saída: ' . htmlspecialchars($e->getMessage());

    header("Location: estoque_saida.php?msg={$mensagem}&type=error");
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $mensagem = 'Erro: ' . htmlspecialchars($e->getMessage());
    header("Location: estoque_saida.php?msg={$mensagem}&type=error");
    exit;
}
