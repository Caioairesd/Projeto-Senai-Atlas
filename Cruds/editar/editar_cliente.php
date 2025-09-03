<?php
require_once('../conexao.php');

$msg = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo '<span class="erro">ID inválido.</span>';
    exit;
}

// Buscar dados atuais
$sql = 'SELECT * FROM cliente WHERE id_cliente = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome_cliente'];
    $email = $_POST['email_cliente'];
    $telefone = $_POST['telefone_cliente'];
    $cnpj = $_POST['cnpj_cliente'];

    $sql = 'UPDATE cliente SET nome_cliente = :nome, email_cliente = :email, telefone_cliente = :telefone, cnpj_cliente = :cnpj WHERE id_cliente = :id';
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $msg = '<span class="sucesso mensagem">✅ Cliente atualizado com sucesso!</span>';
    } else {
        $msg = '<span class="erro mensagem">❌ Erro ao atualizar cliente!</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../assets/style.css"/>
</head>
<body>

    <script>
        setTimeout(() => {
            const msg = document.querySelector('.mensagem');
            if (msg) {
                msg.style.transition = 'opacity 0.5s ease';
                msg.style.opacity = '0';
                setTimeout(() => {
                    msg.style.display = 'none';
                }, 500);
            }
        }, 4000);
    </script>

    <div class="pagina-wrapper">
        <div class="container">
            <div class="titulo">
                <h1>Editar Cliente</h1>
            </div>

            <?php if ($msg): ?>
                <?= $msg ?>
            <?php endif; ?>

            <form method="post">
                <label for="nome_cliente">Nome:</label>
                <input type="text" id="nome_cliente" name="nome_cliente" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" required>

                <label for="email_cliente">Email:</label>
                <input type="email" id="email_cliente" name="email_cliente" value="<?= htmlspecialchars($cliente['email_cliente']) ?>" required>

                <label for="telefone_cliente">Telefone:</label>
                <input type="text" id="telefone_cliente" name="telefone_cliente" value="<?= htmlspecialchars($cliente['telefone_cliente']) ?>" required>

                <label for="cnpj_cliente">CNPJ:</label>
                <input type="text" id="cnpj_cliente" name="cnpj_cliente" value="<?= htmlspecialchars($cliente['cnpj_cliente']) ?>" required>

                <div class="butoes">
                    <button type="submit">Atualizar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
