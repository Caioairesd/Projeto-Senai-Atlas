<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/assets/style.css">
    <title>Cadastrar Cliente</title>
</head>

<body>
<h1>Cadastrar Cliente</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="cnpj">Cnpj:</label>
        <input type="text" id="cnpj" name="cnpj" required>


        <button type="submit">Cadastrar</button>
        <button type="reset">Limpar</button>
    </form>

</body>

</html>