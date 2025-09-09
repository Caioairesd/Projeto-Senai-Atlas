<?php
require_once '../config/conexao.php'; // $pdo é o objeto PDO

$token = $_POST['token'] ?? $_GET['token'] ?? null;
$mensagem = '';
$tipoMensagem = '';

if ($token) {
    $stmt = $pdo->prepare("SELECT id_usuario, token_expira FROM usuario WHERE token_recuperacao = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (strtotime($usuario['token_expira']) > time()) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("UPDATE usuario SET senha_usuario = :senha, token_recuperacao = NULL, token_expira = NULL WHERE id_usuario = :id_usuario");
                $stmt->bindParam(':senha', $novaSenha);
                $stmt->bindParam(':id_usuario', $usuario['id_usuario']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $mensagem = 'Senha alterada com sucesso! Você pode fechar esta aba.';
                    $tipoMensagem = 'success';
                } else {
                    $mensagem = 'Erro ao alterar senha. Tente novamente mais tarde.';
                    $tipoMensagem = 'error';
                }
            }
        } else {
            $mensagem = 'Token expirado. Solicite uma nova recuperação.';
            $tipoMensagem = 'error';
        }
    } else {
        $mensagem = 'Token inválido. Verifique o link enviado por e-mail.';
        $tipoMensagem = 'error';
    }
} else {
    $mensagem = 'Token não informado.';
    $tipoMensagem = 'error';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-wrapper {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .reset-wrapper h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="reset-wrapper">
        <h2>Redefinir Senha</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipoMensagem ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if ($tipoMensagem !== 'success' && isset($usuario) && strtotime($usuario['token_expira']) > time()): ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="input-group">
                    <label for="senha">Nova Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Digite sua nova senha" required>
                </div>
                <button type="submit" class="btn">Alterar Senha</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
