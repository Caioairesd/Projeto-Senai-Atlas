<?php
require_once '../config/conexao.php';

$id_produto = $_GET['id_produto'] ?? null;

if (!$id_produto || !is_numeric($id_produto)) {
    echo '<div class="alert alert-error">ID inválido.</div>';
    exit;
}

$sql = 'UPDATE produto SET ativo = FALSE WHERE id_produto = :id_produto';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../visualizar/visualizar_produto.php?msg=Produto Excluido com sucesso&type=success');
    exit;
} else {
    echo '<div class="alert alert-error">Erro ao Excluir o produto.</div>';
}
