<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();
// Inclui arquivo de configuração de conexão com o banco de dados
require_once '../config/conexao.php';

// Configura exibição de erros para desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configura PDO para lançar exceções em caso de erro
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 0) Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<script>alert('Método inválido.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }

    // 1) Captura e sanitiza os dados do formulário
    $produto_id        = $_POST['produto_id'] ?? null; // ID do produto
    $qtde_item         = $_POST['qtde_estoque'] ?? null; // Quantidade a ser retirada
    $observacao        = $_POST['observacao_estoque'] ?? ''; // Observação opcional
    $cliente_id        = $_POST['cliente_id'] ?? null; // ID do cliente
    $funcionario_id    = $_SESSION['funcionario_id'] ?? null; // ID do funcionário da sessão
    $data_movimentacao = date('Y-m-d H:i:s'); // Data e hora atual

    // 2) Validações básicas dos dados
    if (!is_numeric($produto_id) || !is_numeric($qtde_item)) {
        echo "<script>alert('Dados inválidos.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }
    if ((int)$qtde_item <= 0) {
        echo "<script>alert('A quantidade deve ser maior que zero.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }
    if (empty($cliente_id)) {
        echo "<script>alert('Selecione um cliente.'); window.location.href = 'estoque_saida.php';</script>";
        exit;
    }

    // Inicia transação para garantir atomicidade das operações
    $pdo->beginTransaction();

    // 3) Verifica se o produto existe e obtém seu preço
    $stmt = $pdo->prepare("SELECT preco_produto FROM produto WHERE id_produto = ? AND ativo = 1");
    $stmt->execute([$produto_id]);
    $preco_unitario = $stmt->fetchColumn();
    if ($preco_unitario === false) {
        throw new Exception('Produto não encontrado.');
    }

    // 3.1) Verifica saldo atual em estoque
    $stmt = $pdo->prepare("SELECT SUM(CASE WHEN tipo_movimentacao = 'Entrada' THEN quantidade ELSE -quantidade END) AS saldo_estoque
                       FROM movimentacao
                       WHERE produto_id = ?");
    $stmt->execute([$produto_id]);
    $saldo_estoque = (int)$stmt->fetchColumn();

    // Valida se há estoque suficiente
    if ($saldo_estoque < (int)$qtde_item) {
        throw new Exception('Estoque insuficiente para realizar a saída.');
    }

    // 4) Cria um novo pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, data_pedido, status_pedido) VALUES (?, ?, 'Pendente')");
    $stmt->execute([$cliente_id, $data_movimentacao]);
    $pedido_id = $pdo->lastInsertId(); // Obtém o ID do pedido recém-criado

    // 5) Insere o item no pedido
    $stmt = $pdo->prepare("INSERT INTO item_pedido (pedido_id, produto_id, qtde_item, preco_unitario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pedido_id, $produto_id, $qtde_item, $preco_unitario]);

    // 6) Registra a movimentação de saída no estoque
    $stmt = $pdo->prepare("INSERT INTO movimentacao (
            tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao
        ) VALUES ('Saída', ?, ?, ?, ?, ?)");
    $stmt->execute([$qtde_item, $data_movimentacao, $produto_id, $funcionario_id, $observacao]);

    // Confirma todas as operações da transação
    $pdo->commit();

    // Redireciona para a página de detalhes do pedido com mensagem de sucesso
    echo "<script>alert('Saída e pedido gerados com sucesso!'); window.location.href = 'pedido_detalhe.php?id={$pedido_id}';</script>";
    exit;
    
} catch (PDOException $e) {
    // Em caso de erro no banco, reverte a transação
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Mensagens de erro específicas
    if (str_contains($e->getMessage(), 'Estoque insuficiente')) {
        echo "<script>alert('Erro: Estoque insuficiente para o produto selecionado.'); window.location.href = 'estoque_saida.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar saída: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'estoque_saida.php';</script>";
    }
    exit;
} catch (Exception $e) {
    // Em caso de outros erros, reverte a transação
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<script>alert('Erro: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'estoque_saida.php';</script>";
    exit;
}