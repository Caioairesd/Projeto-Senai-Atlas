<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-error'>ID inválido.</div>";
    exit();
}

$id_cliente = $_GET['id'];

// Busca dados do cliente
$sql = "SELECT * FROM cliente WHERE id_cliente = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo "<div class='alert alert-error'>Cliente não encontrado.</div>";
    exit();
}

// Atualiza cliente
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
        echo "<div class='sucesso'>Cliente atualizado com sucesso!</div>";
    } else {
        echo "<div class='erro'>Erro ao atualizar cliente.</div>";
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
        <p>Atualize as informações do cliente abaixo.</p>

        <form method="POST">
            <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">


            <div class="input-group">
                <label>Nome:</label>
                <input type="text" id="nome_cliente" name="nome_cliente" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" required>
            </div>

            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email_cliente" value="<?= htmlspecialchars($cliente['email_cliente']) ?>" required>
            </div>

            <div class="input-group">
                <label>Telefone:</label>
                <input type="text" id="telefone_cliente" name="telefone_cliente" value="<?= htmlspecialchars($cliente['telefone_cliente']) ?>" required>
            </div>

            <div class="input-group">
                <label>CNPJ:</label>
                <input type="text" id="cnpj_cliente" name="cnpj_cliente" value="<?= htmlspecialchars($cliente['cnpj_cliente']) ?>" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Salvar Alterações</button>
                <a href="../visualizar/visualizar_cliente.php" class="btn">Voltar</a>
            </div>
        </form>
    </div>
    <script src="../assets/validacoes.js"></script>
</body>

</html>