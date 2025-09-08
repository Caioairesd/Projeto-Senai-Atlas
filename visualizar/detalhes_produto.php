<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo '<div class="alert alert-error">ID inválido.</div>';
    exit;
}

$sql = 'SELECT * FROM produto WHERE id_produto = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

$fornecedor_nome = '';
if (!empty($produto['fornecedor_id'])) {
    $stmtFornecedor = $pdo->prepare("SELECT nome_fornecedor FROM fornecedor WHERE id_fornecedor = :id");
    $stmtFornecedor->bindParam(':id', $produto['fornecedor_id'], PDO::PARAM_INT);
    $stmtFornecedor->execute();
    $fornecedor = $stmtFornecedor->fetch(PDO::FETCH_ASSOC);
    if ($fornecedor) {
        $fornecedor_nome = $fornecedor['nome_fornecedor'];
    }
}

if (!$produto) {
    echo '<div class="alert alert-error">Produto não encontrado.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
    <div class="form-wrapper">
        <h2>Detalhes do Produto</h2>
        <p>Confira as informações completas do produto selecionado.</p>
        <!-- 
        <div class="imagem-detalhe">
            <img src="exibir_imagem.php?tipo=produto&id=<?= $produto['id_produto'] ?>" alt="Imagem do Produto">
        </div>
        -->
        <div class="input-group">
            <label>Nome:</label>
            <input type="text" value="<?= htmlspecialchars($produto['nome_produto']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Descrição:</label>
            <input type="text" value="<?= htmlspecialchars($produto['descricao_produto']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Plataforma:</label>
            <input type="text" value="<?= htmlspecialchars($produto['plataforma_produto']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Categoria:</label>
            <input type="text" value="<?= htmlspecialchars($produto['tipo_produto']) ?>" disabled>
        </div>

        <div class="input-group">
            <label>Preço:</label>
            <input type="text" value="R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?>" disabled>
        </div>

        <div class="input-group">
            <label>Fornecedor:</label>
            <input type="text" value="<?= htmlspecialchars($fornecedor_nome) ?>" disabled>
        </div>


        <div class="btn-group">
            <a class="btn btn-delete" href="../excluir/excluir_produto.php?id=<?= $produto['id_produto'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
            <a class="btn" href="visualizar_produto.php">Voltar</a>
        </div>
    </div>
</body>

</html>