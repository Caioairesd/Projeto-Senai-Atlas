 <?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['qtde_estoque'];
    $data_movimentacao = date('Y-m-d H:i:s');
    $observacao = $_POST['observacao_estoque'];

    // Aqui assumo que você guarda o id do funcionário logado na sessão
    // Se for usuario_id, adapte para buscar o funcionario_id correspondente
    $funcionario_id = $_SESSION['funcionario_id'] ?? null;

    if (!is_numeric($produto_id) || !is_numeric($quantidade)) {
        echo "<script>alert('Dados inválidos.'); window.location.href = 'estoque_entrada.php';</script>";
        exit;
    }

    if ($quantidade <= 0) {
        echo "<script>alert('A quantidade deve ser maior que zero.'); window.location.href = 'estoque_entrada.php';</script>";
        exit;
    }

    $sql = "INSERT INTO movimentacao (
        tipo_movimentacao, quantidade, data_movimentacao, produto_id, funcionario_id, observacao
    ) VALUES (
        'Entrada', :quantidade, :data_movimentacao, :produto_id, :funcionario_id, :observacao
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':data_movimentacao', $data_movimentacao);
    $stmt->bindParam(':produto_id', $produto_id);
    $stmt->bindParam(':funcionario_id', $funcionario_id);
    $stmt->bindParam(':observacao', $observacao);

    if ($stmt->execute()) {
        echo "<script>alert('Entrada registrada com sucesso!'); window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar entrada.'); window.location.href = 'estoque_entrada.php';</script>";
    }
}
?>