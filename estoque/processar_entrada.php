<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['qtde_estoque'];
    $observacao = $_POST['observacao_estoque'] ?? '';
    $data_movimentacao = date('Y-m-d H:i:s');
    $funcionario_id = $_SESSION['funcionario_id'] ?? null;

    if (!is_numeric($produto_id) || !is_numeric($quantidade) || $quantidade <= 0) {
        echo "<script>alert('Dados inválidos.'); window.location.href = 'estoque_entrada.php';</script>";
        exit;
    }

    // Buscar fornecedor vinculado ao produto
    $stmtFornecedor = $pdo->prepare("SELECT fornecedor_id FROM produto WHERE id_produto = :id");
    $stmtFornecedor->bindParam(':id', $produto_id, PDO::PARAM_INT);
    $stmtFornecedor->execute();
    $fornecedor_id = $stmtFornecedor->fetchColumn();

    if (!$fornecedor_id) {
        echo "<script>alert('Fornecedor não encontrado para este produto.'); window.location.href = 'estoque_entrada.php';</script>";
        exit;
    }

    // Inserir movimentação
    $sql = "INSERT INTO movimentacao (
        tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao, fornecedor_id
    ) VALUES (
        'Entrada', :quantidade, :data_movimentacao, :produto_id, :funcionario_id, :observacao, :fornecedor_id
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':data_movimentacao', $data_movimentacao);
    $stmt->bindParam(':produto_id', $produto_id);
    $stmt->bindParam(':funcionario_id', $funcionario_id);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Entrada registrada com sucesso!'); window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar entrada.'); window.location.href = 'estoque_entrada.php';</script>";
    }
}
