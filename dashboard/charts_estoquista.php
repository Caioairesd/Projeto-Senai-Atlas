<?php
require_once '../config/conexao.php';
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produto WHERE ativo = 1")->fetchColumn();
$produtosBaixoEstoque = $pdo->query("SELECT nome_produto, estoque FROM produto WHERE estoque < 10 AND ativo = 1")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card-grid">
  <div class="card"><h3>Produtos Ativos</h3><p><?= $totalProdutos ?></p></div>
  <div class="card"><h3>Estoque Baixo</h3><p><?= count($produtosBaixoEstoque) ?> produtos</p></div>
</div>

<div class="chart-grid">
  <div class="chart">
    <h2>Produtos com Estoque Baixo</h2>
    <ul>
      <?php foreach ($produtosBaixoEstoque as $p): ?>
        <li><?= $p['nome_produto'] ?> — <?= $p['estoque'] ?> unidades</li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<script>
const estoqueLabels = <?= json_encode(array_column($produtosBaixoEstoque, 'nome_produto')) ?>;
const estoqueData = <?= json_encode(array_column($produtosBaixoEstoque, 'estoque')) ?>;

new Chart(document.getElementById('graficoEstoqueBaixo'), {
  type: 'bar',
  data: {
    labels: estoqueLabels,
    datasets: [{
      label: 'Estoque Baixo',
      data: estoqueData,
      backgroundColor: '#dc3545'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true } }
  }
});
</script>
