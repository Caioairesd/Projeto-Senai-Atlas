<?php
require_once"conexao.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cliente = $_POST['nome_cliente'] ?? '';
    $email_cliente = $_POST['email_cliente'] ?? '';
    $telefone_cliente = $_POST['telefone_cliente'] ?? '';
    $cnpj_cliente = $_POST['cnpj_cliente'] ?? '';

    $sql = "INSERT INTO cliente (nome_cliente, email_cliente, telefone_cliente, cnpj_cliente) VALUES (:nome_cliente, :email_cliente, :telefone_cliente, :cnpj_cliente)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':nome_cliente' => $nome_cliente,
            ':email_cliente' => $email_cliente,
            ':telefone_cliente' => $telefone_cliente,
            ':cnpj_cliente' => $cnpj_cliente
        ]);
        echo "✅ Cliente cadastrado com sucesso!";
    } catch (PDOException $e) {
        echo "❌ Erro ao cadastrar: " . $e->getMessage();
    }
}


?>