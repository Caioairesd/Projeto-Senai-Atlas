<?php
session_start();
require_once '../config/conexao.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Busca o usuário pelo nome
$sql = "SELECT * FROM usuario WHERE nome_usuario = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['senha_usuario'])) {
    // Login OK
    $_SESSION['usuario'] = $usuario['nome_usuario'];
    $_SESSION['perfil']  = $usuario['perfil_id'];
    header("Location: ../public/dashboard_principal.php");
    exit();
} else {
    echo "<script>alert('Usuário ou senha inválidos'); window.location.href='login.php';</script>";
}
?>