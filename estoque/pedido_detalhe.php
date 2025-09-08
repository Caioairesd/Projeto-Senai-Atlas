<?php
include 'processar_pedido_detalhe.php';
include '../assets/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhe do Pedido</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Pedido #<?= $pedido['id_pedido'] ?> - <?= htmlspecialchars($pedido['nome_cliente']) ?></h2>
        <p>Status atual: <?= htmlspecialchars($pedido['status_pedido']) ?></p>


        <!-- Formulário para alterar status -->
        <form method="POST">
            <select name="status">
                <?php
                $statusList = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
                foreach ($statusList as $status) {
                    $sel = $pedido['status_pedido'] === $status ? 'selected' : '';
                    echo "<option value=\"$status\" $sel>$status</option>";
                }
                ?>
            </select>
            <button type="submit">Alterar Status</button>
        </form>

        <h3>Itens do Pedido</h3>
        <?php if (empty($itens)): ?>
            <div class="no-records">
                <p>Este pedido não possui itens cadastrados.</p>
            </div>
        <?php else: ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                            <td><?= $item['qtde_item'] ?></td>
                            <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>