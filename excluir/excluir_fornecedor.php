<?php
session_start();
require_once '../config/conexao.php';


// EXCLUI FORNECEDOR SE O ID FOR VÁLIDO
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fornecedor = $_GET['id'];

    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor excluído com sucesso!'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir fornecedor.'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
}
?>

