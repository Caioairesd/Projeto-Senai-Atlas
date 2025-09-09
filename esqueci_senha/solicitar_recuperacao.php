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
      $mail->Subject = 'Redefinir Senha';
      $mail->Body    = "Clique no link para redefinir sua senha: 
                <a href='http://localhost/Projeto-Senai-Atlas/esqueci_senha/redefinir_senha.php?token=$token'>Redefinir Senha</a>";
      $mail->AltBody = "Copie e cole no navegador: http://localhost/Projeto-Senai-Atlas/esqueci_senha/redefinir_senha.php?token=$token";

      $mail->send();
      echo "<script>alert('E-mail de recuperação enviado!'); window.location.href='../login/index.php';</script>";
    } catch (Exception $e) {
      echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
  } else {
    echo "<script>alert('E-mail não encontrado.');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recuperar Senha - Sistema Atlas</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
  <div class="card-container">
    <div class="container">
      <div class="visual-side">
        <img src="../assets/images/logo_dark'.png" alt="Logo Atlas" />
        <h1 class="slogan">Recupere o acesso à sua conta</h1>
      </div>

      <form method="POST" class="log-card" aria-label="formulário de recuperação">
        <p class="para">Digite seu e-mail cadastrado para receber o link de redefinição de senha.</p>

        <div class="input-group">
          <label for="email" class="text">E-mail</label>
          <input id="email" name="email" class="input" type="email" placeholder="exemplo@empresa.com" required />
        </div>

        <button type="submit" class="btn">Recuperar senha</button>

        <p class="no-account">
          Lembrou sua senha? <a href="../login/index.php">Voltar ao login</a>
        </p>
      </form>
    </div>
  </div>

  <footer class="footer">
    <p>© 2025 Atlas Sistemas. Todos os direitos reservados.</p>
    <p>Versão 1.0.0</p>
  </footer>
</body>

</html>