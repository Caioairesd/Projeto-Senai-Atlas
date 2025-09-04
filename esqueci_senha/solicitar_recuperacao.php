<?php
require_once '../config/conexao.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe
    $stmt = $pdo->prepare("SELECT id_usuario, email_usuario FROM usuario WHERE email_usuario = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $usuario = $resultado;

        // Gera token único
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Salva token no banco
        $stmt = $pdo->prepare("UPDATE usuario SET token_recuperacao = :token, token_expira = :expira WHERE email_usuario = :email");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expira', $expira);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Envia e-mail com PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'danielbalera021@gmail.com';
            $mail->Password   = 'gmyv ejkz kzfi hznc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('danielbalera021@gmail.com', 'Suporte');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha';
            $mail->Body    = "Clique no link para redefinir sua senha: 
                <a href='http://localhost/Projeto-Senai-Atlas/esqueci_senha/redefinir_senha.php?token=$token'>Redefinir Senha</a>";
            $mail->AltBody = "Copie e cole no navegador: http://localhost/Projeto-Senai-Atlas/esqueci_senha/redefinir_senha.php?token=$token";

            $mail->send();
            echo "E-mail de recuperação enviado!";
        } catch (Exception $e) {
            echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    } else {
        echo "E-mail não encontrado.";
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <button type="submit">Recuperar senha</button>
</form>
