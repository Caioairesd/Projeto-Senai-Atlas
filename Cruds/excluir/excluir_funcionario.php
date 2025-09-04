<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_funcionario = $_GET['id'];

    try {
        // Busca o usuário vinculado ao funcionário
        $sqlBuscaUsuario = "SELECT id_usuario FROM usuario WHERE funcionario_id = :funcionario_id";
        $stmtBusca = $pdo->prepare($sqlBuscaUsuario);
        $stmtBusca->bindParam(':funcionario_id', $id_funcionario, PDO::PARAM_INT);
        $stmtBusca->execute();
        $usuario = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        // Se encontrou o usuário, exclui
        if ($usuario) {
            $sqlExcluiUsuario = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $stmtExclui = $pdo->prepare($sqlExcluiUsuario);
            $stmtExclui->bindParam(':id_usuario', $usuario['id_usuario'], PDO::PARAM_INT);
            $stmtExclui->execute();
        }

        // Exclui o funcionário
        $sqlFuncionario = "DELETE FROM funcionario WHERE id_funcionario = :id_funcionario";
        $stmtFuncionario = $pdo->prepare($sqlFuncionario);
        $stmtFuncionario->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);
        $stmtFuncionario->execute();

        // Redireciona com alerta
        echo "<script>alert('Funcionário e usuário excluídos com sucesso!'); window.location.href='../visualizar/visualizar_funcionario.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "'); window.location.href='../visualizar/visualizar_funcionarios.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_funcionario.php';</script>";
}
?>
