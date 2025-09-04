<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_produto = $_POST['nome_produto'] ?? '';
    $descricao_produto = $_POST['descricao_produto'] ?? '';
    $plataforma_produto = $_POST['plataforma_produto'] ?? '';
    $tipo_produto = $_POST['tipo_produto'] ?? '';
    $preco_produto = $_POST['preco_produto'] ?? '';
    $fornecedor_id = $_POST['fornecedor_id'] ?? '';

    $imagem_blob = null;

    // Verifica se foi enviada uma imagem
    if (!empty($_FILES['imagem_url_produto']['tmp_name']) && $_FILES['imagem_url_produto']['error'] === UPLOAD_ERR_OK) {
        $tmpFile  = $_FILES['imagem_url_produto']['tmp_name'];
        $fileSize = $_FILES['imagem_url_produto']['size'];

        // Limite de tamanho (2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($fileSize > $maxSize) {
            die("Erro: A imagem excede o tamanho máximo de 2MB.");
        }

        // Verifica tipo real da imagem
        $tipo = @exif_imagetype($tmpFile);
        $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

        if ($tipo === false || !in_array($tipo, $tiposPermitidos)) {
            die("Erro: Apenas imagens JPEG, PNG ou GIF são permitidas.");
        }

        // Lê o conteúdo binário para salvar no banco
        $imagem_blob = file_get_contents($tmpFile);
    }

    // Inserir produto
    $sql = "INSERT INTO produto 
            (nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, imagem_url_produto, fornecedor_id)
            VALUES 
            (:nome_produto, :descricao_produto, :plataforma_produto, :tipo_produto, :preco_produto, :imagem_blob, :fornecedor_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_produto', $nome_produto);
    $stmt->bindParam(':descricao_produto', $descricao_produto);
    $stmt->bindParam(':plataforma_produto', $plataforma_produto);
    $stmt->bindParam(':tipo_produto', $tipo_produto);
    $stmt->bindParam(':preco_produto', $preco_produto);
    $stmt->bindParam(':imagem_blob', $imagem_blob, PDO::PARAM_LOB);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);

    if ($stmt->execute()) {
        $msg = '<div class="sucesso">✅ Produto cadastrado com sucesso!</div>';
    } else {
        $msg = '<div class="erro">❌ Erro ao cadastrar produto!</div>';
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

        <!-- Mensagem de sucesso ou erro (PHP pode inserir aqui) -->
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

            <select name="fornecedor_id" required>
                <option value="">Selecione o fornecedor</option>
                <?php
                $fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor")->fetchAll();
                foreach ($fornecedores as $f) {
                    echo "<option value='{$f['id_fornecedor']}'>{$f['nome_fornecedor']}</option>";
                }
                ?>
            </select>


            <button type="submit" class="btn">Cadastrar</button>
            <button type="reset" class="btn btn-edit">Limpar</button>
        </form>
    </div>

</body>

</html>