<?php
require_once '../config/conexao.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura os dados enviados pelo formulário
    $nome = $_POST['nome_fornecedor'] ?? '';
    $email = $_POST['email_fornecedor'] ?? '';
    $telefone = $_POST['contato_fornecedor'] ?? '';
    $cnpj = $_POST['cnpj_fornecedor'] ?? '';

    // Verifica se já existe fornecedor com o mesmo email ou CNPJ
    $verifica = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE email_fornecedor = :email OR cnpj_fornecedor = :cnpj");
    $verifica->bindParam(':email', $email);
    $verifica->bindParam(':cnpj', $cnpj);
    $verifica->execute();
    $existe = $verifica->fetchColumn();

    if ($existe > 0) {
        // Redireciona com mensagem de erro por duplicidade
        header("Location: criar_fornecedor.php?msg=Já existe um fornecedor com este email ou CNPJ.&type=error");
        exit();
    }

    // Insere o novo fornecedor no banco de dados
    $sql = "INSERT INTO fornecedor (nome_fornecedor, email_fornecedor, contato_fornecedor, cnpj_fornecedor)
            VALUES (:nome, :email, :telefone, :cnpj)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);

    // Redireciona com mensagem de sucesso ou erro após tentativa de inserção
    if ($stmt->execute()) {
        header("Location: criar_fornecedor.php?msg=Operação realizada com sucesso.&type=success");
        exit();
    } else {
        header("Location: criar_fornecedor.php?msg=Erro ao realizar operação.&type=error");
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
    <title>Cadastrar Fornecedor</title>
</head>
<body>

    <div class="form-wrapper">
        <h2>Cadastrar Fornecedor</h2>
        <p>Preencha os dados abaixo para registrar um novo fornecedor.</p>

        <!-- Exibe mensagem de feedback, se houver -->
        <?php if (isset($_GET['msg']) && isset($_GET['type'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type']) ?>">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <form id="formCadastrarFornecedor" method="post" action="criar_fornecedor.php" enctype="multipart/form-data">
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

    <!-- Remove automaticamente a mensagem após 4 segundos -->
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.style.display = 'none';
        }, 4000);
    </script>
</body>
</html>
