<?php
session_start();
require_once '../config.php';

$usuarioId = (int) $_SESSION['usuario_id'];
$perfil = (int) $_SESSION['perfil'];

// Monta SQL base
$sql = "
    SELECT
        e.id_estoque,
        p.nome_produto,
        e.tipo_estoque,
        e.qtde_estoque,
        COALESCE(e.data_entrada, e.data_saida) AS data_movimentacao,
        f.nome_funcionario,
        u.nome_usuario,
        e.observacao_estoque,
        pe.id_pedido
    FROM estoque e
    JOIN produto p    ON e.produto_id     = p.id_produto
    JOIN usuario u    ON e.usuario_id     = u.id_usuario
    JOIN funcionario f ON u.funcionario_id = f.id_funcionario
    LEFT JOIN pedidos pe 
      ON pe.usuario_id   = u.id_usuario
     AND e.tipo_estoque  = 'Saída'
";

//Se o usuário não for Admin (1) nem Estoquista (2), limita ao próprio histórico
if ($perfil !== 1 && $perfil !== 2) {
    $sql .= " WHERE e.usuario_id = :usuarioId";
}

//Ordena mais recentes primeiro
$sql .= " ORDER BY data_movimentacao DESC";

try {
    $stmt = $pdo->prepare($sql);
    if ($perfil !== 1 && $perfil !== 2) {
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
    }
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>
            alert('Erro ao buscar histórico de estoque.');
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Estoque</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Movimentações de Estoque</h1>

    <?php if (empty($historico)): ?>
        <p>Não há registros para exibir.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Data</th>
                    <th>Funcionário</th>
                    <th>Observação</th>
                    <th>Pedido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id_estoque']) ?></td>
                        <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                        <td><?= htmlspecialchars($item['tipo_estoque']) ?></td>
                        <td><?= htmlspecialchars($item['qtde_estoque']) ?></td>
                        <td><?= htmlspecialchars($item['data_movimentacao']) ?></td>
                        <td><?= htmlspecialchars($item['nome_funcionario']) ?></td>
                        <td><?= htmlspecialchars($item['observacao_estoque']) ?></td>
                        <td><?= htmlspecialchars($item['id_pedido'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>