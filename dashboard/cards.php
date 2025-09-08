<?php
$totalClientes = $pdo->query("SELECT COUNT(*) FROM cliente WHERE ativo = 1")->fetchColumn();
$totalFornecedores = $pdo->query("SELECT COUNT(*) FROM fornecedor WHERE ativo = 1")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
?>

<div class="card-grid">
    <div class="card">
        <h3><i class="fas fa-users"></i> Clientes Ativos</h3>
        <p><?= $totalClientes ?></p>
    </div>
    <div class="card">
        <h3><i class="fas fa-truck"></i> Fornecedores Ativos</h3>
        <p><?= $totalFornecedores ?></p>
    </div>
    <div class="card">
        <h3><i class="fas fa-shopping-cart"></i> Total de Pedidos</h3>
        <p><?= $totalPedidos ?></p>
    </div>
</div>