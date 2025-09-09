<?php
session_start(); // Inicia a sessão para acessar dados do usuário logado
require_once '../config/conexao.php'; // Inclui arquivo de configuração de conexão com o banco de dados

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém e sanitiza os dados do formulário
    $produto_id = $_POST['produto_id']; // ID do produto
    $quantidade = $_POST['qtde_estoque']; // Quantidade a ser adicionada
    $observacao = $_POST['observacao_estoque'] ?? ''; // Observação (opcional)
    $data_movimentacao = date('Y-m-d H:i:s'); // Data e hora atual
    $funcionario_id = $_SESSION['funcionario_id'] ?? null; // ID do funcionário logado (se disponível)

    // Validação básica dos dados
    if (!is_numeric($produto_id) || !is_numeric($quantidade) || $quantidade <= 0) {
        // Redireciona com mensagem de erro se dados forem inválidos
        header("Location: estoque_entrada.php?msg=Dados inválidos.&type=error");
        exit;
    }

    // Busca o fornecedor vinculado ao produto
    $stmtFornecedor = $pdo->prepare("SELECT fornecedor_id FROM produto WHERE id_produto = :id");
    $stmtFornecedor->bindParam(':id', $produto_id, PDO::PARAM_INT); // Previne SQL injection
    $stmtFornecedor->execute();
    $fornecedor_id = $stmtFornecedor->fetchColumn(); // Obtém o ID do fornecedor

    // Verifica se o fornecedor foi encontrado
    if (!$fornecedor_id) {
        header("Location: estoque_entrada.php?msg=Fornecedor não encontrado para este produto.&type=error");
        exit;
    }

    // Prepara a query para inserir a movimentação de entrada
    $sql = "INSERT INTO movimentacao (
        tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao, fornecedor_id
    ) VALUES (
        'Entrada', :quantidade, :data_movimentacao, :produto_id, :funcionario_id, :observacao, :fornecedor_id
    )";

    $stmt = $pdo->prepare($sql);
    // Associa os parâmetros à query (prevenção contra SQL injection)
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':data_movimentacao', $data_movimentacao);
    $stmt->bindParam(':produto_id', $produto_id);
    $stmt->bindParam(':funcionario_id', $funcionario_id);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);

    // Executa a query e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        // Redireciona com mensagem de sucesso
        header("Location: estoque_entrada.php?msg=Entrada registrada com sucesso.&type=success");
    } else {
        // Redireciona com mensagem de erro
        header("Location: estoque_entrada.php?msg=Erro ao registrar entrada.&type=error");
    }
    exit; // Termina a execução do script
}