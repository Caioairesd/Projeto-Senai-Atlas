<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1>Cadastrar funcionário</h1>
    <form action="" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone">

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco">

        <label for="data_contratacao">Data de contratação:</label>
        <input type="date" id="data_contratacao" name="data_contratacao">
        <label for="id_perfil">Perfil:</label>
        <select name="id_perfil" id="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Vendedor</option>
            <option value="3">Estoquista</option>

        </select>







    </form>

</body>

</html>