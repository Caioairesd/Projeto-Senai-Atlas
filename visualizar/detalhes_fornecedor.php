<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_funcionario.php';</script>";
    exit();
}

$id_funcionario = $_GET['id'];

// BUSCA OS DADOS DO FUNCIONÁRIO
$sql = "SELECT * FROM funcionario WHERE id_funcionario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
$stmt->execute();
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

// VERIFICA SE O FUNCIONÁRIO EXISTE
if (!$funcionario) {
    echo "<script>alert('Funcionário não encontrado.'); window.location.href='../visualizar/visualizar_funcionario.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhes do Funcionário</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Detalhes do Funcionário</h2>
        <p>Confira as informações completas do funcionário selecionado.</p>

        <div class="input-group">
            <label>Nome:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Email:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['email_funcionario']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Telefone:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['telefone_funcionario']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>CPF:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['cpf_funcionario']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Cargo:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['cargo_funcionario']) ?>" disabled>
        </div>

        <div class="btn-group">
            <a class="btn btn-edit"
                href="../editar/editar_funcionario.php?id=<?= $funcionario['id_funcionario'] ?>">Editar</a>
            <a class="btn btn-delete" href="../excluir/excluir_funcionario.php?id=<?= $funcionario['id_funcionario'] ?>"
                onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
            <a class="btn" href="../visualizar/visualizar_funcionario.php">Voltar</a>
        </div>
    </div>
</body>

</html>
