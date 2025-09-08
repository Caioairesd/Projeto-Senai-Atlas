<?php
session_start();
require_once '../config/conexao.php';

// EXCLUI FORNECEDOR SE O ID FOR VÁLIDO
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fornecedor = $_GET['id'];

    $sql = "UPDATE fornecedor SET ativo = FALSE WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../visualizar/visualizar_fornecedor.php?msg=Fornecedor desativado com sucesso.&type=success");
        exit;
    } else {
        header("Location: ../visualizar/visualizar_fornecedor.php?msg=Erro ao desativar fornecedor.&type=error");
        exit;
    }
} else {
    header("Location: ../visualizar/visualizar_fornecedor.php?msg=ID inválido.&type=error");
    exit;
}
