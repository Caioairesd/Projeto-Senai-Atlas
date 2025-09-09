<?php
// Inicia a sessão para armazenar dados do usuário logado
session_start();
// Inclui arquivo de configuração de conexão com o banco de dados
require_once '../config/conexao.php';

// Obtém credenciais do formulário de login
$username = $_POST['username']; // Nome de usuário informado
$password = $_POST['password']; // Senha informada

// Prepara e executa consulta para buscar usuário pelo nome
$sql = "SELECT * FROM usuario WHERE nome_usuario = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username); // Previne SQL injection
$stmt->execute();

// Obtém dados do usuário
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se usuário existe e se a senha está correta
if ($usuario && password_verify($password, $usuario['senha_usuario'])) {
    // Login bem-sucedido - armazena dados na sessão
    $_SESSION['usuario']     = $usuario['nome_usuario']; // Nome do usuário
    $_SESSION['usuario_id']  = $usuario['id_usuario'];   // ID do usuário (ESSENCIAL para identificação)
    $_SESSION['perfil']      = $usuario['perfil_id'];    // ID do perfil de acesso
    $_SESSION['funcionario_id'] = $usuario['funcionario_id']; // ID do funcionário vinculado

    // Redireciona para o dashboard
    header("Location: ../dashboard/dashboard.php");
    exit();
} else {
    // Login falhou - exibe alerta e redireciona de volta
    echo "<script>alert('Usuário ou senha inválidos'); window.location.href='login.php';</script>";
}
?>