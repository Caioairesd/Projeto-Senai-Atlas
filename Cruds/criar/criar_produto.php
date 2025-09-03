<?php
require_once('../conexao.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_produto = $_POST['nome_produto'] ?? '';
    $descricao_produto = $_POST['descricao_produto'] ?? '';
    $plataforma_produto = $_POST['plataforma_produto'] ?? '';
    $tipo_produto = $_POST['tipo_produto'] ?? '';
    $preco_produto = $_POST['preco_produto'] ?? '';
    $imagem_url_produto = $_POST['imagem_url_produto'] ?? '';
    $fornecedor_produto = $_POST['fornecedor_produto'] ?? '';

    $sql = "INSERT INTO produto (nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto,imagem_url_produto,fornecedor_id)
            VALUES (:nome_produto, :descricao_produto, :plataforma_produto, :tipo_produto, :preco_produto,:imagem_url_produto,:fornecedor_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_produto', $nome_produto);
    $stmt->bindParam(':descricao_produto', $descricao_produto);
    $stmt->bindParam(':plataforma_produto', $plataforma_produto);
    $stmt->bindParam(':tipo_produto', $tipo_produto);
    $stmt->bindParam(':preco_produto', $preco_produto);
    $stmt->bindParam(':imagem_url_produto', $imagem_url_produto);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);

    if ($stmt->execute()) {
        $msg = '<div class="sucesso">✅ Cliente cadastrado com sucesso!</div>';
    } else {
        $msg = '<div class="erro">❌ Erro ao cadastrar cliente!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/style.css" />
    <title>Cadastrar Produto</title>
</head>

<body>

    <div class="form-wrapper">
        <h2>Cadastrar Produto</h2>
        <p>Preencha os dados abaixo para adicionar um novo produto ao sistema.</p>

        <?= $msg ?? '' ?>

        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome_produto" required />
            </div>

            <div class="input-group">
                <label for="descricao_produto">Descrição</label>
                <input type="text" id="descricao_produto" name="descricao_produto" required />
            </div>

            <div class="input-group">
                <label for="plataforma_produto">Plataforma</label>
                <input type="text" id="plataforma_produto" name="plataforma_produto" required />
            </div>

            <div class="input-group">
                <label for="tipo_produto">Categoria</label>
                <input type="text" id="tipo_produto" name="tipo_produto" required />
            </div>

            <div class="input-group">
                <label for="preco_produto">Preço</label>
                <input type="text" id="preco_produto" name="preco_produto" required />
            </div>

            <div class="input-group">
                <label for="imagem_url_produto">Imagem do Produto</label>
                <input type="file" id="imagem_url_produto" name="imagem_url_produto" accept="image/*" required />
            </div>

            <div class="input-group">
                <label for="fornecedor_id">ID do Fornecedor</label>
                <input type="text" id="fornecedor_id" name="fornecedor_produto" required />
            </div>

            <button type="submit" class="btn">Cadastrar</button>
            <button type="reset" class="btn" style="background-color: #ccc; color: #333;">Limpar</button>
        </form>
    </div>

</body>

</html>