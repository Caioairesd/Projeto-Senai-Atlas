<?php
session_start();
require_once '../cruds/conexao.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuario WHERE nome_usuario = :username AND senha_usuario = :password";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();

if ($stmt->rowCount() === 1) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['usuario'] = $usuario['nome_usuario'];
    $_SESSION['perfil'] = $usuario['perfil_id']; // <- ESSENCIAL
    header("Location: ../public/dashboard_principal.php");
    exit();
} else {
    echo "<script>alert('Usuário ou senha inválidos'); window.location.href='login.php';</script>";
}
?>