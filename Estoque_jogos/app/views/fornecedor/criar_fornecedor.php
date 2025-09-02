<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar novo fornecedor</title>
</head>

<body>

    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        
        <!--No banco está contato_fornecedor-->
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>
        
        <label for="cnpj">Cnpj:</label>
        <input type="text" id="cnpj" name="cnpj" required>


        <button type="submit">Cadastrar</button>
    </form>

</body>

</html>