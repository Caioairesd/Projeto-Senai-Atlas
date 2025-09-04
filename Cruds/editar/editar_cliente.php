<?php
session_start();
require_once '../conexao.php';


// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_cliente.php';</script>";
    exit();
}

$id_cliente = $_GET['id'];

// BUSCA DADOS DO cliente
$sql = "SELECT * FROM cliente WHERE id_cliente = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// ATUALIZA cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_cliente'];
    $email = $_POST['email_cliente'];
    $telefone = $_POST['telefone_cliente'];
    $cnpj = $_POST['cnpj_cliente'];

    $sql = "UPDATE cliente SET 
                nome_cliente = :nome, 
                email_cliente = :email, 
                telefone_cliente = :telefone, 
                cnpj_cliente = :cnpj 
            WHERE id_cliente = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':id', $id_cliente);

    if ($stmt->execute()) {
        echo "<script>alert('cliente atualizado com sucesso!'); window.location.href='../visualizar/visualizar_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar cliente.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar cliente</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Editar cliente</h2>
        <form method="post">
            <label>Nome:</label>
            <input type="text" name="nome_cliente" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email_cliente']) ?>" required>

            <label>Telefone:</label>
            <input type="text" name="telefone_cliente" value="<?= htmlspecialchars($cliente['telefone_cliente']) ?>" required>

            <label>CNPJ:</label>
            <input type="text" name="cnpj_cliente" value="<?= htmlspecialchars($cliente['cnpj_cliente']) ?>" required>

            <button type="submit" class="btn">Salvar Alterações</button>
            <a href="../visualizar/visualizar_cliente.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>

