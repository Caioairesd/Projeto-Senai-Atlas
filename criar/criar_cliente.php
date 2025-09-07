<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';


$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST['nome_cliente'] ?? '';
  $email = $_POST['email_cliente'] ?? '';
  $telefone = $_POST['telefone_cliente'] ?? '';
  $cnpj = $_POST['cnpj_cliente'] ?? '';

  $sql = "INSERT INTO cliente (nome_cliente, email_cliente, telefone_cliente, cnpj_cliente)
            VALUES (:nome, :email, :telefone, :cnpj)";
  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(':nome', $nome);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':telefone', $telefone);
  $stmt->bindParam(':cnpj', $cnpj);

  if ($stmt->execute()) {
    $msg = '<div class="sucesso">Cliente cadastrado com sucesso!</div>';
  } else {
    $msg = '<div class="erro">Erro ao cadastrar cliente!</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/style.css" />
  <title>Cadastrar Cliente</title>
</head>

<body>

  <div class="form-wrapper">
    <h2>Cadastrar Cliente</h2>
    <p>Preencha os dados abaixo para registrar um novo cliente.</p>

    <?= $msg ?? '' ?>

    <form id="formCadastrarCliente" method="post" enctype="multipart/form-data">
      
      <!-- Contêiner para mensagens de erro -->
      <div class="erros-validacao"></div>

      <div class="input-group">
        <label for="nome_cliente">Nome</label>
        <input type="text" id="nome_cliente" name="nome_cliente" required />
      </div>

      <div class="input-group">
        <label for="email_cliente">Email</label>
        <input type="email" id="email_cliente" name="email_cliente" required />
      </div>

      <div class="input-group">
        <label for="telefone_cliente">Telefone</label>
        <input type="text" id="telefone_cliente" name="telefone_cliente" required />
      </div>

      <div class="input-group">
        <label for="cnpj_cliente">CNPJ</label>
        <input type="text" id="cnpj_cliente" name="cnpj_cliente" required />
      </div>

      <button type="submit" class="btn">Cadastrar</button>
      <button type="reset" class="btn btn-edit">Limpar</button>
    </form>
  </div>

  <!-- Importa o arquivo de validações -->
  <script src="../assets/validacoes.js"></script>
</body>

</html>
