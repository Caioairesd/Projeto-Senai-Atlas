<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo '<div class="alert alert-error">ID inválido.</div>';
    exit;
}

$sql = 'SELECT * FROM cliente WHERE id_cliente = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo '<div class="alert alert-error">Cliente não encontrado.</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Detalhes do Cliente</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
    <div class="form-wrapper">
        <h2>Detalhes do Cliente</h2>
        <p>Confira as informações completas do cliente selecionado.</p>

        <div class="input-group">
            <label>Nome:</label>
            <input type="text" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Email:</label>
            <input type="text" value="<?= htmlspecialchars($cliente['email_cliente']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Telefone:</label>
            <input type="text" value="<?= htmlspecialchars($cliente['telefone_cliente']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>CNPJ:</label>
            <input type="text" value="<?= htmlspecialchars($cliente['cnpj_cliente']) ?>" disabled>
        </div>

        <div class="btn-group" style="display: flex; gap: 10px; margin-top: 20px;">
            <a class="btn btn-edit" href="editar_cliente.php?id=<?= $cliente['id_cliente'] ?>">✏️ Editar</a>
            <a class="btn btn-delete" href="/..excluir/excluir_cliente.php?id=<?= $cliente['id_cliente'] ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
            <a class="btn" href="visualizar_clientes.php">🔙 Voltar</a>
        </div>
    </div>
</body>
</html>
