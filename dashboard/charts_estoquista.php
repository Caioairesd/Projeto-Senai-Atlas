<?php
require_once '../config/conexao.php';

$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produto WHERE ativo = 1")->fetchColumn();
$totalEstoque = $pdo->query("SELECT SUM(qtde_estoque_produto) FROM produto WHERE ativo = 1")->fetchColumn();
$produtosZerados = $pdo->query("SELECT COUNT(*) FROM produto WHERE qtde_estoque_produto = 0 AND ativo = 1")->fetchColumn();
$produtosBaixoEstoque = $pdo->query("
    SELECT nome_produto, qtde_estoque_produto 
    FROM produto 
    WHERE qtde_estoque_produto < 10 AND ativo = 1 
    ORDER BY qtde_estoque_produto ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card-grid">
    <div class="card">
        <h3>Produtos Ativos</h3>
        <p><?= $totalProdutos ?></p>
    </div>
    <div class="card">
        <h3>Estoque Total</h3>
        <p><?= $totalEstoque ?> unidades</p>
    </div>
    <div class="card card-zerado">
        <h3>Produtos Zerados</h3>
        <p><?= $produtosZerados ?></p>
    </div>
    <div class="card card-baixo">
        <h3>Estoque Baixo</h3>
        <p><?= count($produtosBaixoEstoque) ?> produtos</p>
    </div>
</div>

<div class="chart-grid">
    <div class="chart">
        <h2>Produtos com Estoque Baixo</h2>
        <canvas id="graficoEstoqueBaixo"></canvas>
    </div>
    <div class="chart estoquista-lista">
        <h2>Lista Detalhada</h2>
        <ul>
            <?php foreach ($produtosBaixoEstoque as $p): ?>
                <li>
                    <?= $p['nome_produto'] ?>
                    <span class="estoquista-alerta"><?= $p['qtde_estoque_produto'] ?> unidades</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    const estoqueLabels = <?= json_encode(array_column($produtosBaixoEstoque, 'nome_produto')) ?>;
    const estoqueData = <?= json_encode(array_column($produtosBaixoEstoque, 'qtde_estoque_produto')) ?>;

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
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        autoSkip: false
                    }
                }
            }
        }
    });
</script>