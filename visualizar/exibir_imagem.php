<?php
require_once '../config/conexao.php';

// Verifica parâmetros obrigatórios
if (!isset($_GET['tipo']) || !isset($_GET['id'])) {
    http_response_code(400);
    exit('Parâmetros inválidos.');
}

$tipo = $_GET['tipo']; // funcionario ou produto
$id   = (int) $_GET['id'];

// Define configurações conforme o tipo
switch ($tipo) {
    case 'funcionario':
        $tabela = 'funcionario';
        $campoId = 'id_funcionario';
        $campoImagem = 'imagem_url_funcionario';
        break;
    case 'produto':
        $tabela = 'produto';
        $campoId = 'id_produto';
        $campoImagem = 'imagem_url_produto';
        break;
    default:
        http_response_code(400);
        exit('Tipo inválido.');
}

// Busca a imagem no banco
$sql = "SELECT $campoImagem FROM $tabela WHERE $campoId = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$imagem = $stmt->fetchColumn();

if ($imagem) {
    header("Content-Type: image/jpeg"); // ajuste se for PNG/GIF
    echo $imagem;
} else {
    // Imagem padrão caso não exista
    header("Content-Type: image/png");
    readfile("../assets/sem_foto.png");
}
