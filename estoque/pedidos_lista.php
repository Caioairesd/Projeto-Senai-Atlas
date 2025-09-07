<?php
include 'processar_pedidos_lista.php';
include '../assets/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="form-wrapper">
    <div class="historico-header">
        <h2>Lista de Pedidos</h2>
        <p>Resumo de todos os pedidos cadastrados</p>
    </div>

    <!-- Filtros -->
    <form method="GET" action="" class="form-inline mb-3">
        <input type="text" name="cliente" placeholder="Cliente" value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>">

        <select name="status">
            <option value="">Todos os status</option>
            <?php
            $statusList = ['Pendente','Processando','Enviado','Entregue','Cancelado'];
            foreach ($statusList as $status) {
                $sel = (isset($_GET['status']) && $_GET['status'] === $status) ? 'selected' : '';
                echo "<option value=\"$status\" $sel>$status</option>";
            }
            ?>
        </select>

        <label>De:</label>
        <input type="date" name="data_ini" value="<?= htmlspecialchars($_GET['data_ini'] ?? '') ?>">
        <label>Até:</label>
        <input type="date" name="data_fim" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">

        <button type="submit">Filtrar</button>
    </form>

    <?php if (empty($pedidos)): ?>
        <div class="no-records">
            <p>Não há pedidos cadastrados.</p>
        </div>
    <?php else: ?>
        <table class="historico-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                    <th>Itens</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><?= $p['id_pedido'] ?></td>
                        <td><?= htmlspecialchars($p['nome_cliente']) ?></td>
                        <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                        <td><?= htmlspecialchars($p['status_pedido']) ?></td>
                        <td>R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></td>
                        <td><?= $p['total_itens'] ?></td>
                        <td><a href="pedido_detalhe.php?id=<?= $p['id_pedido'] ?>">Ver Detalhes</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
