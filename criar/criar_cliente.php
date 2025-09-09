<?php
require_once '../config/conexao.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura os dados enviados pelo formulário
    $nome = $_POST['nome_cliente'] ?? '';
    $email = $_POST['email_cliente'] ?? '';
    $telefone = $_POST['telefone_cliente'] ?? '';
    $cnpj = $_POST['cnpj_cliente'] ?? '';

    // Verifica se já existe cliente com o mesmo email ou CNPJ
    $verifica = $pdo->prepare("SELECT COUNT(*) FROM cliente WHERE email_cliente = :email OR cnpj_cliente = :cnpj");
    $verifica->bindParam(':email', $email);
    $verifica->bindParam(':cnpj', $cnpj);
    $verifica->execute();
    $existe = $verifica->fetchColumn();

    if ($existe > 0) {
        // Redireciona com mensagem de erro por duplicidade
        header("Location: criar_cliente.php?msg=Já existe um cliente com este email ou CNPJ.&type=error");
        exit();
    }

    // Insere o novo cliente no banco de dados
    $sql = "INSERT INTO cliente (nome_cliente, email_cliente, telefone_cliente, cnpj_cliente)
            VALUES (:nome, :email, :telefone, :cnpj)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);

    // Redireciona com mensagem de sucesso ou erro após tentativa de inserção
    if ($stmt->execute()) {
        header("Location: criar_cliente.php?msg=Operação realizada com sucesso.&type=success");
        exit();
    } else {
        header("Location: criar_cliente.php?msg=Erro ao realizar operação.&type=error");
        exit();
    }
}
?>

<?php include '../assets/sidebar.php'; ?>

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

        <!-- Exibe mensagem de feedback, se houver -->
        <?php if (isset($_GET['msg']) && isset($_GET['type'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type']) ?>">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <form id="formCadastrarCliente" method="post" action="criar_cliente.php" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nome_cliente">Nome</label>
                <input type="text" id="nome_cliente" name="nome_cliente" placeholder="Ex: Empresa XYZ Ltda." required />
            </div>

            <div class="input-group">
                <label for="email_cliente">Email</label>
                <input type="email" id="email_cliente" name="email_cliente" placeholder="contato@empresa.com.br" required />
            </div>

            <div class="input-group">
                <label for="telefone_cliente">Telefone</label>
                <input type="text" id="telefone_cliente" name="telefone_cliente" placeholder="(XX) XXXXX-XXXX" required />
            </div>

            <div class="input-group">
                <label for="cnpj_cliente">CNPJ</label>
                <input type="text" id="cnpj_cliente" name="cnpj_cliente" placeholder="00.000.000/0000-00" required />
            </div>

            <button type="submit" class="btn">Cadastrar</button>
            <button type="reset" class="btn btn-edit">Limpar</button>
        </form>
    </div>

    <script src="../assets/validacoes.js"></script>

    <!-- Remove automaticamente a mensagem após 4 segundos -->
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.style.display = 'none';
        }, 4000);
    </script>
</body>
</html>
