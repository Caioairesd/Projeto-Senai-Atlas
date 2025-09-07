<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Filtro opcional
$filtro = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$sql = "SELECT * FROM vw_estoque_geral";
$params = [];

if ($filtro !== '') {
    $sql .= " WHERE nome_produto LIKE ?";
    $params[] = "%{$filtro}%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque Geral</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="historico-header">
            <h2>📦 Estoque Geral</h2>
            <p>Visão consolidada de todos os produtos e seus níveis de estoque</p>
        </div>

        <form method="GET" action="" class="form-inline mb-3">
            <input type="text" name="busca" placeholder="Buscar produto..." value="<?= htmlspecialchars($filtro) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (empty($estoque)): ?>
            <div class="no-records">
                <p>Não há produtos cadastrados ou que correspondam à busca.</p>
            </div>
        <?php else: ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Plataforma</th>
                        <th>Tipo</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estoque as $item): ?>
                        <tr style="background-color: <?= $item['status_estoque'] === 'Baixo' ? '#ffcccc' : '#ccffcc' ?>;">
                            <td><?= htmlspecialchars($item['id_produto']) ?></td>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                            <td><?= htmlspecialchars($item['plataforma_produto']) ?></td>
                            <td><?= htmlspecialchars($item['tipo_produto']) ?></td>
                            <td>R$ <?= number_format($item['preco_produto'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($item['qtde_estoque_produto']) ?></td>
                            <td><?= htmlspecialchars($item['status_estoque']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
