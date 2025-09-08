<?php
session_start();
require_once '../config/conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_funcionario = $_GET['id'];

    try {
        // Desativa o usuário vinculado ao funcionário, se existir
        $sqlBuscaUsuario = "SELECT id_usuario FROM usuario WHERE funcionario_id = :funcionario_id";
        $stmtBusca = $pdo->prepare($sqlBuscaUsuario);
        $stmtBusca->bindParam(':funcionario_id', $id_funcionario, PDO::PARAM_INT);
        $stmtBusca->execute();
        $usuario = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $sqlDesativaUsuario = "UPDATE usuario SET ativo = FALSE WHERE id_usuario = :id_usuario";
            $stmtDesativa = $pdo->prepare($sqlDesativaUsuario);
            $stmtDesativa->bindParam(':id_usuario', $usuario['id_usuario'], PDO::PARAM_INT);
            $stmtDesativa->execute();
        }

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
exit;
