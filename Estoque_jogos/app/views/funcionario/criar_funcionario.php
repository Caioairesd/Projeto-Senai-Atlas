<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/assets/style.css">
    <title>Cadastrar funcionário</title>
</head>

<body>

    <h1>Cadastrar funcionário</h1>
    <form action="" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf">

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone">

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco">

        <label for="data_nascimento">Data de nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento">

        <label for="data_admissao">Data de admissão:</label>
        <input type="date" id="data_admissao" name="data_admissao">

        <label for="salario">Salário:</label>
        <input type="text" id="salario" name="salario">

        <label for="data_contratacao">Data de contratação:</label>
        <input type="date" id="data_contratacao" name="data_contratacao">
        <label for="id_perfil">Perfil:</label>
        <select name="id_perfil" id="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Vendedor</option>
            <option value="3">Estoquista</option>

        </select>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">

        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario">

        <label for="senha">senha:</label>
        <input type="text" id="senha" name="senha">

        <button type="submit">Cadastrar</button>
        <button type="reset">Limpar</button>

    </form>

</body>

</html>