<?php
require 'conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$imagem = $_POST['imagem'];
$nota = $_POST['nota'];
$plataformas = $_POST['plataformas'];

$sql = "INSERT INTO jogos (id, nome, imagem, nota, plataformas) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issds", $id, $nome, $imagem, $nota, $plataformas);
$stmt->execute();

echo "<p>✅ Jogo salvo com sucesso!</p>";
echo "<a href='catalogo.php'>Ver catálogo</a>";
