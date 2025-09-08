<?php
require_once '../config/conexao.php';

if (!isset($_GET['tipo']) || !isset($_GET['id'])) {
    http_response_code(400);
    exit('Parâmetros inválidos.');
}

$tipo = $_GET['tipo'];
$id   = (int) $_GET['id'];

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

$sql = "SELECT $campoImagem FROM $tabela WHERE $campoId = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$imagem = $stmt->fetchColumn();

if ($imagem) {
    // Detecta tipo da imagem (simples, baseado no conteúdo)
    $finfo = finfo_open();
    $mimeType = finfo_buffer($finfo, $imagem, FILEINFO_MIME_TYPE);
    finfo_close($finfo);

    header("Content-Type: $mimeType");
    echo $imagem;
} else {
    header("Content-Type: image/png");
    readfile("../assets/sem_foto.png");
}
