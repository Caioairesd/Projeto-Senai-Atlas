<?php
session_start();
require_once '../config/conexao.php';

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-error'>ID inválido.</div>";
    exit();
}

$id_fornecedor = $_GET['id'];

// Busca dados do fornecedor
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
$stmt->execute();
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fornecedor) {
    echo "<div class='alert alert-error'>Fornecedor não encontrado.</div>";
    exit();
}

// Atualiza fornecedor
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
    header("Location: ../visualizar/visualizar_fornecedor.php?msg=atualizado");
    exit();
} else {
    header("Location: ../visualizar/visualizar_fornecedor.php?msg=erro");
    exit();
}

}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="form-wrapper">
        <h2>Editar Fornecedor</h2>
        <p>Atualize as informações do fornecedor abaixo.</p>

        <form method="POST">
            <input type="hidden" name="id_fornecedor" value="<?= $fornecedor['id_fornecedor'] ?>">

            <div class="input-group">
                <label for="nome_fornecedor">Nome:</label>
                <input type="text" id="nome_fornecedor" name="nome_fornecedor" placeholder="Digite o nome do fornecedor" value="<?= htmlspecialchars($fornecedor['nome_fornecedor']) ?>" required>
            </div>

            <div class="input-group">
                <label for="email_fornecedor">Email:</label>
                <input type="email" id="email_fornecedor" name="email_fornecedor" placeholder="Digite o email" value="<?= htmlspecialchars($fornecedor['email_fornecedor']) ?>" required>
            </div>

            <div class="input-group">
                <label for="contato_fornecedor">Telefone:</label>
                <input type="text" id="contato_fornecedor" name="contato_fornecedor" placeholder="Digite o telefone" value="<?= htmlspecialchars($fornecedor['contato_fornecedor']) ?>" required>
            </div>

            <div class="input-group">
                <label for="cnpj_fornecedor">CNPJ:</label>
                <input type="text" id="cnpj_fornecedor" name="cnpj_fornecedor" placeholder="Digite o CNPJ" value="<?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?>" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Salvar Alterações</button>
                <a href="../visualizar/visualizar_fornecedor.php" class="btn btn-delete">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="../assets/validacoes.js"></script>
</body>
</html>
