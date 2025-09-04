<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = [];

// Encerra a sessão
session_destroy();

// Redireciona para a tela de login
header("Location: ../login/login.php");
exit();
?>