<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/assets/style.css">
    <title>Cadastrar produto</title>
</head>

<body>

    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" required>

        <label for="plataforma">plataforma:</label>
        <input type="text" id="plataforma" name="plataforma" required>

        <!-- Colocar ComboBox depois -->
        <label for="tipo_produto">Categoria:</label>
        <input type="checkbox" id="tipo_produto" name="tipo_produto" required>

        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" required>

        <!-- Ajustar imagem-->
        <label for="imagem">imagem:</label>
        <input type="file" id="imagem" name="imagem" required>

        <!--Ajustar para puxar do banco-->
        <label for="fornecedor">Fornecedor:</label>
        <input type="text" id="fornecedor" name="fornecedor" required>


        <button type="submit">Cadastrar</button>
    </form>

</body>

</html>