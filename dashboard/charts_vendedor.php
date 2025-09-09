
<?php
require_once '../config/conexao.php';
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$faturamentoTotal = $pdo->query("SELECT SUM(qtde_item * preco_unitario) FROM item_pedido")->fetchColumn();
$ticketMedio = $pdo->query("SELECT AVG(total) FROM (SELECT SUM(qtde_item * preco_unitario) AS total FROM item_pedido GROUP BY pedido_id) AS subtotais")->fetchColumn();
$pedidosMes = $pdo->query("SELECT MONTH(data_pedido) AS mes, COUNT(*) AS total FROM pedidos GROUP BY mes ORDER BY mes")->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query("SELECT p.nome_produto, SUM(i.qtde_item) AS total_vendido FROM item_pedido i JOIN produto p ON i.produto_id = p.id_produto WHERE p.ativo = 1 GROUP BY p.nome_produto ORDER BY total_vendido DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card-grid">
  <div class="card"><h3>Total de Pedidos</h3><p><?= $totalPedidos ?></p></div>
  <div class="card"><h3>Faturamento</h3><p>R$ <?= number_format($faturamentoTotal, 2, ',', '.') ?></p></div>
  <div class="card"><h3>Ticket Médio</h3><p>R$ <?= number_format($ticketMedio, 2, ',', '.') ?></p></div>
</div>

<div class="chart-grid">
  <div class="chart"><h2>Pedidos por Mês</h2><canvas id="graficoMes"></canvas></div>
  <div class="chart"><h2>Top Produtos Vendidos</h2><canvas id="graficoProdutos"></canvas></div>
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
      borderColor: '#28a745',
      backgroundColor: 'rgba(40,167,69,0.1)',
      fill: true,
      tension: 0.3
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

const produtoLabels = <?= json_encode(array_column($produtos, 'nome_produto')) ?>;
const produtoData = <?= json_encode(array_column($produtos, 'total_vendido')) ?>;

new Chart(document.getElementById('graficoProdutos'), {
  type: 'bar',
  data: {
    labels: produtoLabels,
    datasets: [{
      label: 'Top Produtos Vendidos',
      data: produtoData,
      backgroundColor: '#28a745'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true } }
  }
});
</script>
