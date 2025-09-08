<?php
require_once '../config/conexao.php'; // $pdo é o objeto PDO

// Captura o token tanto do GET quanto do POST
$token = $_POST['token'] ?? $_GET['token'] ?? null;

if ($token) {
    // Verifica token no banco
    $stmt = $pdo->prepare("SELECT id_usuario, token_expira 
                           FROM usuario 
                           WHERE token_recuperacao = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Verifica se o token ainda é válido
        if (strtotime($usuario['token_expira']) > time()) {

            // Se o formulário foi enviado
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

                var_dump($usuario);

                // Atualiza senha e remove token
                $stmt = $pdo->prepare("UPDATE usuario 
                                       SET senha_usuario = :senha, 
                                           token_recuperacao = NULL, 
                                           token_expira = NULL 
                                       WHERE id_usuario = :id_usuario");
                $stmt->bindParam(':senha', $novaSenha);
                $stmt->bindParam(':id_usuario', $usuario['id_usuario']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Senha alterada com sucesso! Feche a aba');</script>";
                } else {
                    echo "<script>alert('Erro ao alterar senha. Tente novamente mais tarde.');</script>";
                }
                exit;
            }
            ?>

            <!-- Formulário mantém o token como campo oculto -->
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="password" name="senha" placeholder="Nova senha" required>
                <button type="submit">Alterar senha</button>
            </form>

            <?php
        } else {
            echo "<p style='color:red'>Token expirado. Solicite novamente.</p>";
        }
    } else {
        echo "<p style='color:red'>Token inválido.</p>";
    }
} else {
    echo "<p style='color:red'>Token não informado.</p>";
}