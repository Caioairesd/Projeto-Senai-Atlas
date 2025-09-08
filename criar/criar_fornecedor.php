<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome_fornecedor = $_POST['nome_fornecedor'] ?? '';
  $email_fornecedor = $_POST['email_fornecedor'] ?? '';
  $contato_fornecedor = $_POST['contato_fornecedor'] ?? '';
  $cnpj_fornecedor = $_POST['cnpj_fornecedor'] ?? '';

  $sql = "INSERT INTO fornecedor (nome_fornecedor, email_fornecedor, contato_fornecedor, cnpj_fornecedor)
            VALUES (:nome_fornecedor, :email_fornecedor, :contato_fornecedor, :cnpj_fornecedor)";
  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
  $stmt->bindParam(':email_fornecedor', $email_fornecedor);
  $stmt->bindParam(':contato_fornecedor', $contato_fornecedor);
  $stmt->bindParam(':cnpj_fornecedor', $cnpj_fornecedor);

  if ($stmt->execute()) {
    $msg = '<div class="sucesso">Fornecedor cadastrado com sucesso!</div>';
  } else {
    $msg = '<div class="erro">Erro ao cadastrar fornecedor!</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/style.css" />
  <title>Cadastrar Fornecedor</title>
</head>

<body>

  <div class="form-wrapper">
    <h2>Cadastrar Fornecedor</h2>
    <p>Preencha os dados abaixo para registrar um novo fornecedor.</p>

    <?= $msg ?? '' ?>
    <form id="formCadastrarCliente" method="post" enctype="multipart/form-data">

      <!-- Contêiner para mensagens de erro -->
      <div class="erros-validacao"></div>

      <div class="input-group">
        <label for="nome_fornecedor">Nome</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" placeholder="Ex: Empresa XYZ Ltda." required />
      </div>

      <div class="input-group">
        <label for="email_fornecedor">Email</label>
        <input type="email" id="email_fornecedor" name="email_fornecedor" placeholder="contato@empresa.com.br" required />
      </div>

      <div class="input-group">
        <label for="contato_fornecedor">Telefone</label>
        <input type="text" id="contato_fornecedor" name="contato_fornecedor" placeholder="(XX) XXXXX-XXXX" required />
      </div>

      <div class="input-group">
        <label for="cnpj_fornecedor">CNPJ</label>
        <input type="text" id="cnpj_fornecedor" name="cnpj_fornecedor" placeholder="00.000.000/0000-00" required />
      </div>

      <button type="submit" class="btn">Cadastrar</button>
      <button type="reset" class="btn btn-edit">Limpar</button>
    </form>

  </div>
  <script src="../assets/validacoes.js"></script>
</body>

</html>