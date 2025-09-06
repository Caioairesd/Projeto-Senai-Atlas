<?php
session_start();
require_once '../config/conexao.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    exit();
}

$id_fornecedor = $_GET['id'];

// BUSCA OS DADOS DO FORNECEDOR
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
$stmt->execute();
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

// VERIFICA SE O FORNECEDOR EXISTE
if (!$fornecedor) {
    echo "<script>alert('Fornecedor não encontrado.'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Fornecedor</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Detalhes do Fornecedor</h2>
        <p>Confira as informações completas do fornecedor selecionado.</p>

        <div class="input-group">
            <label>Nome:</label>
            <input type="text" value="<?= htmlspecialchars($fornecedor['nome_fornecedor']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Email:</label>
            <input type="text" value="<?= htmlspecialchars($fornecedor['email_fornecedor']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Telefone:</label>
            <input type="text" value="<?= htmlspecialchars($fornecedor['contato_fornecedor']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>CNPJ:</label>
            <input type="text" value="<?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?>" disabled>
        </div>

        <div class="btn-group">
            <a class="btn btn-edit" href="../editar/editar_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Editar</a>
            <a class="btn btn-delete" href="../excluir/excluir_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>"
               onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
            <a class="btn" href="../visualizar/visualizar_fornecedor.php">Voltar</a>
        </div>
    </div>
</body>
</html>
