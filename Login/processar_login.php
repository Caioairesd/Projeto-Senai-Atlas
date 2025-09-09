<?php
session_start();
require_once '../config/conexao.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuario WHERE nome_usuario = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['senha_usuario'])) {
    $_SESSION['usuario'] = $usuario['nome_usuario'];
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['perfil'] = $usuario['perfil_id']; // <- define a permissão
    $_SESSION['funcionario_id'] = $usuario['funcionario_id'];

    header("Location: ../dashboard/dashboard.php");
    exit();
} else {
    echo "<script>alert('Usuário ou senha inválidos'); window.location.href='../login.php';</script>";
}
