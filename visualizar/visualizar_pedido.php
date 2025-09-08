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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <div class="form-wrapper">
            <h2>Lista de Pedidos</h2>
            <p>Resumo de todos os pedidos cadastrados</p>

        <!-- Filtros -->
        <form method="GET" action="" class="search-form">
            <input type="text" name="cliente" class="input" placeholder="Buscar pedido" value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>">

            
            <label for="data_ini">De:</label>
            <input type="date" name="data_ini" class="input" value="<?= htmlspecialchars($_GET['data_ini'] ?? '') ?>">
            
            <label for="data_fim">Até:</label>
            <input type="date" name="data_fim" class="input" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
            
            <select name="status" id="status-select" class="input">
                <option value="">Todos os status</option>
                <?php
                $statusList = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
                foreach ($statusList as $status) {
                    $sel = (isset($_GET['status']) && $_GET['status'] === $status) ? 'selected' : '';
                    echo "<option value=\"$status\" $sel>$status</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Filtrar</button>
            <a href="pedidos_lista.php" class="btn">Limpar Filtros</a>
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
                            <td><a class="btn" href="pedido_detalhe.php?id=<?= $p['id_pedido'] ?>">Ver Detalhes</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#status-select').select2({
                placeholder: "Todos os status",
                allowClear: true,
                width: 'style' // respeita o CSS existente
            });
        });
    </script>
</body>

</html>