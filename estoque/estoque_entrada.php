<?php
session_start();
require_once '../config/conexao.php';

if ($_SESSION['perfil_usuario'] != 1 && $_SESSION['perfil_usuario'] != 3) {
    echo "<script>alert('Acesso negado.'); window.location.href = '../index.php';</script>";
} else {

    $produto_id = $_POST['produto_id'];
    $qtde_estoque = $_POST['qtde_estoque'];
    $data_entrada = date('Y-m-d H:i:s');
    $observacao_estoque = $_POST['observacao_estoque'];
    $usuario_id = $_SESSION['usuario_id'];

    if (!is_numeric($produto_id) || !is_numeric($qtde_estoque)) {
        die("Dados inválidos.");
    } else if ($qtde_estoque <= 0) {
        die("A quantidade deve ser maior que zero.");
    } else {

        $sql = "INSERT INTO estoque (
    produto_id, tipo_estoque, qtde_estoque, data_entrada, observacao_estoque, usuario_id
) VALUES (
    :produto_id, 'Entrada', :qtde_estoque, :data_entrada, :observacao_estoque, :usuario_id
)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':qtde_estoque', $qtde_estoque);
        $stmt->bindParam(':data_entrada', $data_entrada);
        $stmt->bindParam(':observacao_estoque', $observacao_estoque);
        $stmt->bindParam(':usuario_id', $usuario_id);

        if ($stmt->execute()) {
            echo "<script>alert('Entrada registrada com sucesso!'); window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('Erro ao registrar entrada.'); window.location.href = '../index.php';</script>";
        }
    }
}
?>