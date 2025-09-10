<?php
session_start();
require_once '../config/conexao.php';

if (isset($_GET['id_funcionario']) && is_numeric($_GET['id_funcionario'])) {
    $id_funcionario = $_GET['id_funcionario'];

    try {
     

        // Desativa o funcionário
        $sqlDesativaFuncionario = "UPDATE funcionario SET ativo = FALSE WHERE id_funcionario = :id_funcionario";
        $stmtFuncionario = $pdo->prepare($sqlDesativaFuncionario);
        $stmtFuncionario->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);
        $stmtFuncionario->execute();

        // Redireciona com mensagem de sucesso
        header("Location: ../visualizar/visualizar_funcionario.php?msg=Funcionário desativado com sucesso.&type=success");
    } catch (Exception $e) {
        header("Location: ../visualizar/visualizar_funcionario.php?msg=Erro ao desativar funcionário.&type=error");
    }
} else {
    header("Location: ../visualizar/visualizar_funcionario.php?msg=ID inválido.&type=error");
}
exit();
