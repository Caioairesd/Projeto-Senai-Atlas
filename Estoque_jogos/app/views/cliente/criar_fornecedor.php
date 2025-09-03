<?php
require_once"conexao.php";

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_funcionario = $_POST['nome_funcionario_fonecedor'] ?? '';
    $email_fornecedor = $_POST['email_fornecedor_fornecedor'] ?? '';
    $telefone = $_POST['telefone_fornecedor'] ?? '';
    $cnpj = $_POST['cnpj_fornecedor'] ?? '';

    $sql = "INSERT INTO cliente (nome_funcionario_fonecedor, email_fornecedor_fornecedor, telefone_fornecedor, cnpj_fornecedor)
            VALUES (:nome_funcionario, :email_fornecedor, :telefone, :cnpj)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_funcionario', $nome_funcionario);
    $stmt->bindParam(':email_fornecedor', $email_fornecedor);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar novo fornecedor</title>
</head>

<body>

    <form method="post">
        <label for="nome_funcionario">Nome:</label>
        <input type="text" id="nome_funcionario" name="nome_funcionario" required>
        
        <!--No banco está contato_fornecedor-->
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="email_fornecedor">Email:</label>
        <input type="text" id="email_fornecedor" name="email_fornecedor" required>
        
        <label for="cnpj">Cnpj:</label>
        <input type="text" id="cnpj" name="cnpj" required>


        <button type="submit">Cadastrar</button>
    </form>

</body>

</html>