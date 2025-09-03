<?php
session_start();
require_once '../conexao.php';


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
    <div class="details-wrapper">
        <h2>Detalhes do Fornecedor</h2>

        <ul class="details-list">
            <li><strong>Nome:</strong> <?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($fornecedor['email_fornecedor']) ?></li>
            <li><strong>Telefone:</strong> <?= htmlspecialchars($fornecedor['contato_fornecedor']) ?></li>
            <li><strong>CNPJ:</strong> <?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?></li>
        </ul>

        <div class="actions">
            <a href="../editar/editar_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>" class="btn">Editar</a>
            <a href="../visualizar/visualizar_fornecedor.php" class="btn">Voltar</a>
        </div>
    </div>
</body>
</html>
