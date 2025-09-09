<?php
session_start();
require_once '../config/conexao.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../visualizar/visualizar_fornecedor.php?msg=ID inválido.&type=error");
    exit();
}

$id_fornecedor = $_GET['id'];

// BUSCA OS DADOS DO FORNECEDOR ATIVO
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id AND ativo = 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
$stmt->execute();
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

// VERIFICA SE O FORNECEDOR EXISTE
if (!$fornecedor) {
    header("Location: ../visualizar/visualizar_fornecedor.php?msg=Fornecedor não encontrado ou inativo.&type=error");
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
          
            <a class="btn" href="../visualizar/visualizar_fornecedor.php">Voltar</a>
            <a class="btn btn-edit" href="../editar/editar_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Editar</a>
        </div>
    </div>
</body>

</html>