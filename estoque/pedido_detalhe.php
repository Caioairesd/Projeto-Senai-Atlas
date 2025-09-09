<?php
include 'processar_pedido_detalhe.php'; // Inclui o arquivo de processamento dos detalhes do pedido (contém a lógica de busca dos dados)
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhe do Pedido</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- CSS personalizado -->
</head>

<body>
    <div class="form-wrapper">
        <!-- Cabeçalho com informações do pedido -->
        <h2>Pedido #<?= $pedido['id_pedido'] ?> - <?= htmlspecialchars($pedido['nome_cliente']) ?></h2>
        <p>Status atual: <?= htmlspecialchars($pedido['status_pedido']) ?></p>

        <!-- Formulário para alterar status do pedido -->
        <form method="POST">
            <select name="status">
                <?php
                // Lista de status possíveis para um pedido
                $statusList = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
                foreach ($statusList as $status) {
                    // Marca como selecionado o status atual do pedido
                    $sel = $pedido['status_pedido'] === $status ? 'selected' : '';
                    echo "<option value=\"$status\" $sel>$status</option>";
                }
                ?>
            </select>
            <button type="submit">Alterar Status</button>
        </form>

        <!-- Seção de itens do pedido -->
        <h3>Itens do Pedido</h3>
        
        <!-- Mensagem para pedido sem itens -->
        <?php if (empty($itens)): ?>
            <div class="no-records">
                <p>Este pedido não possui itens cadastrados.</p>
            </div>
        <?php else: ?>
            <!-- Tabela com itens do pedido -->
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Produto</th> <!-- Nome do produto -->
                        <th>Quantidade</th> <!-- Quantidade solicitada -->
                        <th>Preço Unitário</th> <!-- Preço unitário do produto -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td> <!-- Nome do produto -->
                            <td><?= $item['qtde_item'] ?></td> <!-- Quantidade -->
                            <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td> <!-- Preço formatado -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>