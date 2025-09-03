<?php
require_once "conexao.php";

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_funcionario = $_POST['nome_funcionario'] ?? '';
    $email_funcionario = $_POST['email_funcionario'] ?? '';
    $telefone_funcionario = $_POST['telefone_funcionario'] ?? '';
    $cpf_funcionario = $_POST['cpf_funcionario'] ?? '';
    $salario_funcionario = $_POST['salario_funcionario'] ?? '';
    $endereco_funcionario = $_POST['endereco_funcionario'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $data_admissao = $_POST['data_admissao'] ?? '';

    $sql = "INSERT INTO funcionario (nome_funcionario, email_funcionario, telefone_funcionario, cpf_funcionario, salario_funcionario,endereco_funcionario,data_nascimento,data_admissao)
            VALUES (:nome_funcionario, :email_funcionario, :telefone_funcionario, :cpf_funcionario, :salario_funcionario,:endereco_funcionario,:data_nascimento,:data_admissao)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_funcionario', $nome_funcionario);
    $stmt->bindParam(':email_funcionario', $email_funcionario);
    $stmt->bindParam(':telefone_funcionario', $telefone_funcionario);
    $stmt->bindParam(':cpf_funcionario', $cpf_funcionario);
    $stmt->bindParam(':salario_funcionario', $salario_funcionario);
    $stmt->bindParam(':endereco_funcionario', $endereco_funcionario);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':data_admissao', $data_admissao);

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
  <link rel="stylesheet" href="style.css" />
  <title>Cadastrar Funcionário</title>
</head>
<body>

  <div class="form-wrapper">
    <h2>Cadastrar Funcionário</h2>
    <p>Preencha os dados abaixo para registrar um novo funcionário.</p>

    <?= $msg ?? '' ?>

    <form method="post" enctype="multipart/form-data">
      <div class="input-group">
        <label for="nome_funcionario">Nome</label>
        <input type="text" id="nome_funcionario" name="nome_funcionario" required />
      </div>

      <div class="input-group">
        <label for="email_funcionario">Email</label>
        <input type="email" id="email_funcionario" name="email_funcionario" required />
      </div>

      <div class="input-group">
        <label for="telefone_funcionario">Telefone</label>
        <input type="text" id="telefone_funcionario" name="telefone_funcionario" required />
      </div>

      <div class="input-group">
        <label for="cpf_funcionario">CPF</label>
        <input type="text" id="cpf_funcionario" name="cpf_funcionario" required />
      </div>

      <div class="input-group">
        <label for="salario_funcionario">Salário</label>
        <input type="text" id="salario_funcionario" name="salario_funcionario" required />
      </div>

      <div class="input-group">
        <label for="endereco_funcionario">Endereço</label>
        <input type="text" id="endereco_funcionario" name="endereco_funcionario" required />
      </div>

      <div class="input-group">
        <label for="data_nascimento">Data de nascimento</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required />
      </div>

      <div class="input-group">
        <label for="data_admissao">Data de admissão</label>
        <input type="date" id="data_admissao" name="data_admissao" required />
      </div>

      <button type="submit" class="btn">Cadastrar</button>
      <button type="reset" class="btn" style="background-color: #ccc; color: #333;">Limpar</button>
    </form>
  </div>

</body>
</html>
