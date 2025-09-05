<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.parent.fecharModal();</script>";
    exit();
}

$id_cliente = $_GET['id'];

// BUSCA DADOS DO CLIENTE
$sql = "SELECT * FROM cliente WHERE id_cliente = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// ATUALIZA CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_cliente'];
    $email = $_POST['email_cliente'];
    $telefone = $_POST['telefone_cliente'];
    $cnpj = $_POST['cnpj_cliente'];



    $sql = "UPDATE cliente SET 
                nome_cliente = :nome, 
                email_cliente = :email, 
                telefone_cliente = :telefone, 
                cnpj_cliente = :cnpj,
            WHERE id_cliente = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj_cliente);
    $stmt->bindParam(':id', $id_cliente);

    if ($stmt->execute()) {
        echo "<script>
            alert('✅ Cliente atualizado com sucesso!');
            window.parent.fecharModal();
        </script>";
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar cliente.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Editar Cliente</h2>
        <form method="post">
            <div class="input-group">
                <label>Nome:</label>
                <input type="text" name="nome_cliente" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>"
                    required>
            </div>

            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email_cliente']) ?>"
                    required>
            </div>

            <div class="input-group">
                <label>Telefone:</label>
                <input type="text" name="telefone_cliente" value="<?= htmlspecialchars($cliente['telefone_cliente']) ?>"
                    required>
            </div>

            <div class="input-group">
                <label>CNPJ:</label>
                <input type="text" name="cnpj_cliente" value="<?= htmlspecialchars($cliente['cnpj_cliente']) ?>" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Salvar</button>
                <button type="button" class="btn btn-delete" onclick="window.parent.fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</body>

</html>