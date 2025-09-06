<?php
include 'processar_historico.php';
include '../assets/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Estoque</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <!-- Adicionado header melhorado com classes específicas -->
        <div class="historico-header">
            <h2>Histórico de Movimentações</h2>
            <p>Veja todas as entradas e saídas registradas no sistema de estoque</p>
        </div>

        <?php if (empty($historico)): ?>
            
            <div class="no-records">
                <p>Não há registros de movimentação para exibir no momento.</p>
            </div>
        <?php else: ?>
            
            <table class="historico-table">
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
                            <td class="<?= $item['tipo_estoque'] === 'Entrada' ? 'tipo-entrada' : 'tipo-saida' ?>">
                                <?= htmlspecialchars($item['tipo_estoque']) ?>
                            </td>
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

    </div>
</body>
</html>