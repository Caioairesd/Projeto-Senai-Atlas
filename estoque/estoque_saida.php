<?php
session_start();
require_once '../config/conexao.php';

if ($_SESSION['perfil_usuario'] != 1 && $_SESSION['perfil_usuario'] != 3) {
    die("Acesso negado.");
} else {
    try {
        $produto_id = $_POST['produto_id'];
        $qtde_estoque = $_POST['qtde_estoque'];
        $data_saida = date('Y-m-d H:i:s');
        $observacao_estoque = $_POST['observacao_estoque'];
        $usuario_id = $_SESSION['usuario_id'];

        $sql = "INSERT INTO estoque (
        produto_id, tipo_estoque, qtde_estoque, data_saida, observacao_estoque, usuario_id
    ) VALUES (
        :produto_id, 'Saída', :qtde_estoque, :data_saida, :observacao_estoque, :usuario_id
    )";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':produto_id', $produto_id);
        $stmt->bindParam(':qtde_estoque', $qtde_estoque);
        $stmt->bindParam(':data_saida', $data_saida);
        $stmt->bindParam(':observacao_estoque', $observacao_estoque);
        $stmt->bindParam(':usuario_id', $usuario_id);

        $stmt->execute();

        echo "Saída registrada com sucesso!";
    } catch (PDOException $e) {
        // Captura erro do trigger ou falha de banco
        if (str_contains($e->getMessage(), 'Estoque insuficiente')) {
            echo "Erro: Estoque insuficiente para o produto selecionado.";
        } else {
            echo "Erro ao registrar saída: " . $e->getMessage();
        }
    }
}
?>