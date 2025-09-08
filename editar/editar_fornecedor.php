<?php
require_once '../config/conexao.php';
require_once '../assets/sidebar.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    exit();
}

$id_fornecedor = $_GET['id'];

// BUSCA DADOS DO FORNECEDOR
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
$stmt->execute();
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

// ATUALIZA FORNECEDOR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_fornecedor'];
    $email = $_POST['email_fornecedor'];
    $telefone = $_POST['contato_fornecedor'];
    $cnpj = $_POST['cnpj_fornecedor'];

    $sql = "UPDATE fornecedor SET 
                nome_fornecedor = :nome, 
                email_fornecedor = :email, 
                contato_fornecedor = :telefone, 
                cnpj_fornecedor = :cnpj 
            WHERE id_fornecedor = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':id', $id_fornecedor);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor atualizado com sucesso!'); window.location.href='../visualizar/visualizar_fornecedor.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar fornecedor.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Editar Fornecedor</h2>
        <form method="post">
            <label>Nome:</label>
            <input type="text" name="nome_fornecedor" value="<?= htmlspecialchars($fornecedor['nome_fornecedor']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email_fornecedor" value="<?= htmlspecialchars($fornecedor['email_fornecedor']) ?>" required>

            <label>Telefone:</label>
            <input type="text" name="contato_fornecedor" value="<?= htmlspecialchars($fornecedor['contato_fornecedor']) ?>" required>

            <label>CNPJ:</label>
            <input type="text" name="cnpj_fornecedor" value="<?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?>" required>

            <button type="submit" class="btn">Salvar Alterações</button>
            <a href="../visualizar/visualizar_fornecedor.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>

