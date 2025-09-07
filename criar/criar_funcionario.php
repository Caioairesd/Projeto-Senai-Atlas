<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';
$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Dados do funcionário
  $nome = $_POST['nome_funcionario'] ?? '';
  $email = $_POST['email_funcionario'] ?? '';
  $telefone = $_POST['telefone_funcionario'] ?? '';
  $cpf = $_POST['cpf_funcionario'] ?? '';
  $salario = $_POST['salario_funcionario'] ?? '';
  $endereco = $_POST['endereco_funcionario'] ?? '';
  $nascimento = $_POST['data_nascimento'] ?? '';
  $admissao = $_POST['data_admissao'] ?? '';

  $imagem = null;

  // Verifica se foi enviada uma imagem
    if (!empty($_FILES['imagem_url_funcionario']['tmp_name'])) {
        $tmpFile = $_FILES['imagem_url_funcionario']['tmp_name'];
        $fileSize = $_FILES['imagem_url_funcionario']['size'];

        // Limite de tamanho (2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($fileSize > $maxSize) {
            die("Erro: A imagem excede o tamanho máximo de 2MB.");
        }

        // Verifica o tipo real da imagem
        $tipo = @exif_imagetype($tmpFile);
        $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

        if ($tipo === false || !in_array($tipo, $tiposPermitidos)) {
            die("Erro: Apenas imagens JPEG, PNG ou GIF são permitidas.");
        }

        // Lê o conteúdo da imagem para salvar no banco
        $imagem = file_get_contents($tmpFile);
    }
  // Inserir funcionário
  $sql = "INSERT INTO funcionario (nome_funcionario, email_funcionario, telefone_funcionario, cpf_funcionario, salario_funcionario, endereco_funcionario, data_nascimento, data_admissao, imagem_url_funcionario)
            VALUES (:nome, :email, :telefone, :cpf, :salario, :endereco, :nascimento, :admissao, :imagem)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':nome', $nome);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':telefone', $telefone);
  $stmt->bindParam(':cpf', $cpf);
  $stmt->bindParam(':salario', $salario);
  $stmt->bindParam(':endereco', $endereco);
  $stmt->bindParam(':nascimento', $nascimento);
  $stmt->bindParam(':admissao', $admissao);
  $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB);

  if ($stmt->execute()) {
    $idFuncionario = $pdo->lastInsertId();

    // Dados do usuário
    $nomeUsuario = $_POST['nome_usuario'] ?? $nome;
    $emailUsuario = $email; // mesmo e-mail do funcionário
    $senhaUsuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT);
    $perfilId = $_POST['perfil_id'] ?? 2;

    // Inserir usuário
    $sqlUsuario = "INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, perfil_id, funcionario_id)
                       VALUES (:nome, :email, :senha, :perfil, :funcionario)";
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->bindParam(':nome', $nomeUsuario);
    $stmtUsuario->bindParam(':email', $emailUsuario);
    $stmtUsuario->bindParam(':senha', $senhaUsuario);
    $stmtUsuario->bindParam(':perfil', $perfilId);
    $stmtUsuario->bindParam(':funcionario', $idFuncionario);
    $stmtUsuario->execute();

    $msg = '<div class="sucesso">Funcionário e usuário cadastrados com sucesso!</div>';
  } else {
    $msg = '<div class="erro">Erro ao cadastrar funcionário!</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Cadastrar Funcionário</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
  <div class="form-wrapper">
    <h2>Cadastrar Funcionário + Usuário</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
      <div class="input-group"><label>Nome</label><input type="text" name="nome_funcionario" required></div>
      <div class="input-group"><label>Email</label><input type="email" name="email_funcionario" required></div>
      <div class="input-group"><label>Telefone</label><input type="text" name="telefone_funcionario" required></div>
      <div class="input-group"><label>CPF</label><input type="text" name="cpf_funcionario" required></div>
      <div class="input-group"><label>Salário</label><input type="text" name="salario_funcionario" required></div>
      <div class="input-group"><label>Endereço</label><input type="text" name="endereco_funcionario" required></div>
      <div class="input-group"><label>Data de nascimento</label><input type="date" name="data_nascimento" required>
      </div>
      <div class="input-group"><label>Data de admissão</label><input type="date" name="data_admissao" required></div>
      <div class="input-group"><label>Foto</label><input type="file" name="imagem_url_funcionario" accept="image/*"></div>

      <hr>
      <h3>Dados de acesso do usuário</h3>
      <div class="input-group"><label>Nome de usuário</label><input type="text" name="nome_usuario" required></div>
      <div class="input-group"><label>Senha</label><input type="password" name="senha_usuario" required></div>
      <div class="input-group">
        <label>Perfil</label>
        <select name="perfil_id">
          <option value="1">Administrador</option>
          <option value="2" selected>Vendedor</option>
          <option value="3" selected>Estoquista</option>
        </select>
      </div>

      <div class="btn-group">
        <button type="submit" class="btn">Cadastrar</button>
        <button type="reset" class="btn btn-edit">Limpar</button>
      </div>
    </form>
  </div>
  <script src="../assets/validacoes.js"></script>
</body>

</html>