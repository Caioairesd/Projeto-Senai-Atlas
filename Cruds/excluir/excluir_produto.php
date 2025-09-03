<?php
require_once('../conexao.php');

$id_produto = $_GET['id_produto'] ?? null;

if (!$id_produto || !is_numeric($id_produto)) {
    echo '<div class="alert alert-error">ID inválido.</div>';
    exit;
}

// Comando de exclusão
$sql = 'DELETE FROM produto WHERE id_produto = :id_produto';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_produto', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Redireciona para a lista com sucesso
    header('Location: ../visualizar/visualizar_produtos.php?msg=excluido');
    exit;
} else {
    echo '<div class="alert alert-error">Erro ao excluir o produto.</div>';
}
?>
