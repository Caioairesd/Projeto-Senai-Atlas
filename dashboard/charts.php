<?php
$totalItens = $pdo->query("SELECT SUM(qtde_item) FROM item_pedido")->fetchColumn();
$faturamentoTotal = $pdo->query("SELECT SUM(qtde_item * preco_unitario) FROM item_pedido")->fetchColumn();
$ticketMedio = $pdo->query("
    SELECT AVG(total) FROM (
        SELECT SUM(qtde_item * preco_unitario) AS total
        FROM item_pedido
        GROUP BY pedido_id
    ) AS subtotais
")->fetchColumn();
$taxaCancelamento = $pdo->query("
    SELECT ROUND(
        (SELECT COUNT(*) FROM pedidos WHERE status_pedido = 'Cancelado') / 
        (SELECT COUNT(*) FROM pedidos) * 100, 2
    ) AS taxa
")->fetchColumn();

$pedidosMes = $pdo->query("
    SELECT MONTH(data_pedido) AS mes, COUNT(*) AS total
    FROM pedidos
    GROUP BY mes
    ORDER BY mes
")->fetchAll(PDO::FETCH_ASSOC);

$status = $pdo->query("
    SELECT status_pedido, COUNT(*) AS total 
    FROM pedidos 
    GROUP BY status_pedido
")->fetchAll(PDO::FETCH_ASSOC);

$produtos = $pdo->query("
    SELECT produto_id, SUM(qtde_item) AS total_vendido 
    FROM item_pedido 
    GROUP BY produto_id 
    ORDER BY total_vendido DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$receita = $pdo->query("
    SELECT produto_id, SUM(qtde_item * preco_unitario) AS receita_total 
    FROM item_pedido 
    GROUP BY produto_id 
    ORDER BY receita_total DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card-grid">
    <div class="card">
        <h3><i class="fas fa-boxes"></i> Itens Vendidos</h3>
        <p><?= $totalItens ?></p>
    </div>
    <div class="card">
        <h3><i class="fas fa-dollar-sign"></i> Faturamento</h3>
        <p>R$ <?= number_format($faturamentoTotal, 2, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3><i class="fas fa-receipt"></i> Ticket Médio</h3>
        <p>R$ <?= number_format($ticketMedio, 2, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3><i class="fas fa-ban"></i> Cancelamentos</h3>
        <p><?= $taxaCancelamento ?>%</p>
    </div>
</div>

<div class="chart">
    <h2>Pedidos por Mês</h2>
    <canvas id="graficoMes"></canvas>
</div>

<div class="chart">
    <h2>Pedidos por Status</h2>
    <canvas id="graficoStatus"></canvas>
</div>

<div class="chart">
    <h2>Top Produtos Vendidos</h2>
    <canvas id="graficoProdutos"></canvas>
</div>

<div class="chart">
    <h2>Receita por Produto</h2>
    <canvas id="graficoReceita"></canvas>
</div>

<script>
    const meses = <?= json_encode(array_map(fn($m) => DateTime::createFromFormat('!m', $m['mes'])->format('M'), $pedidosMes)) ?>;
    const pedidosPorMes = <?= json_encode(array_column($pedidosMes, 'total')) ?>;

    new Chart(document.getElementById('graficoMes'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Pedidos por Mês',
                data: pedidosPorMes,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    const statusLabels = <?= json_encode(array_column($status, 'status_pedido')) ?>;
    const statusData = <?= json_encode(array_column($status, 'total')) ?>;

    new Chart(document.getElementById('graficoStatus'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#007bff', '#36A2EB', '#FFCE56', '#8BC34A', '#FF9800']
            }]
        }
    });

    const produtoLabels = <?= json_encode(array_column($produtos, 'produto_id')) ?>;
    const produtoData = <?= json_encode(array_column($produtos, 'total_vendido')) ?>;

    new Chart(document.getElementById('graficoProdutos'), {
        type: 'bar',
        data: {
            labels: produtoLabels,
            datasets: [{
                label: 'Top Produtos Vendidos',
                data: produtoData,
                backgroundColor: '#007bff'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const receitaLabels = <?= json_encode(array_column($receita, 'produto_id')) ?>;
    const receitaData = <?= json_encode(array_map(fn($r) => round($r['receita_total'], 2), $receita)) ?>;

    new Chart(document.getElementById('graficoReceita'), {
        type: 'line',
        data: {
            labels: receitaLabels,
            datasets: [{
                label: 'Receita por Produto',
                data: receitaData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>