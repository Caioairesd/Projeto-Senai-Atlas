<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo '<div class="alert alert-error">ID inválido.</div>';
    exit;
}

$sql = 'SELECT * FROM funcionario WHERE id_funcionario = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcionario) {
    echo '<div class="alert alert-error">Funcionário não encontrado.</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Detalhes do Funcionário</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
    <div class="form-wrapper">
        <h2>Detalhes do Funcionário</h2>
        <p>Confira as informações completas do funcionário selecionado.</p>

        <?php if (!empty($funcionario['imagem_funcionario'])): ?>
            <div style="margin-bottom: 20px;">
                <img src="<?= htmlspecialchars($funcionario['imagem_funcionario']) ?>" alt="Foto do Funcionário"
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
            </div>
        <?php endif; ?>

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
            <label>Salário:</label>
            <input type="text" value="R$ <?= number_format($funcionario['salario_funcionario'], 2, ',', '.') ?>" disabled>
        </div>

        <div class="input-group">
            <label>Endereço:</label>
            <input type="text" value="<?= htmlspecialchars($funcionario['endereco_funcionario']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Data de Nascimento:</label>
            <input type="text" value="<?= htmlspecialchars(date('d/m/Y', strtotime($funcionario['data_nascimento']))) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Data de Admissão:</label>
            <input type="text" value="<?= htmlspecialchars(date('d/m/Y', strtotime($funcionario['data_admissao']))) ?>" disabled>
        </div>

        <div class="btn-group" style="display: flex; gap: 10px; margin-top: 20px;">
            <a class="btn btn-edit" href="editar_funcionario.php?id=<?= $funcionario['id_funcionario'] ?>">✏️ Editar</a>
            <a class="btn btn-delete" href="../excluir/excluir_funcionario.php?id=<?= $funcionario['id_funcionario'] ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">🗑️ Excluir</a>
            <a class="btn" href="visualizar_funcionarios.php">🔙 Voltar</a>
        </div>
    </div>
</body>
</html>
