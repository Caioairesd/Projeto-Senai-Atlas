<?php
include 'processar_historico.php';
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
            <h2>Histórico de Movimentações</h2>
            <p>Veja todas as entradas e saídas registradas no sistema de estoque</p>
        <!-- Filtros -->
        <form method="GET" action="" class="search-form">
            <select name="tipo" class="input">
                <option value="">Todos os Tipos</option>
                <?php
                $tiposMov = ['Entrada', 'Saída'];
                foreach ($tiposMov as $tipo) {
                    $sel = ($filtroTipo === $tipo) ? 'selected' : '';
                    echo "<option value=\"$tipo\" $sel>$tipo</option>";
                }
                ?>
            </select>

            <input type="text" name="produto" class="input" placeholder="Buscar Produto..." value="<?= htmlspecialchars($filtroProduto) ?>">

            <label for="data_ini">De:</label>
            <input type="date" name="data_ini" class="input" value="<?= htmlspecialchars($filtroDataIni) ?>">

            <label for="data_fim">Até:</label>
            <input type="date" name="data_fim" class="input" value="<?= htmlspecialchars($filtroDataFim) ?>">

            <button type="submit" class="btn">Filtrar</button>
            <a href="estoque_historico.php" class="btn">Limpar Filtros</a>
        </form>


        <?php if (empty($historico)): ?>
            <div class="no-records">
                <p>Não há registros de movimentação para exibir no momento.</p>
            </div>
        <?php else: ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Produto</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Data</th>
                        <th>Funcionário</th>
                        <th>Fornecedor</th>
                        <th>Observação</th>
                        <th>Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id_movimentacao']) ?></td>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                            <td class="<?= $item['tipo_movimentacao'] === 'Entrada' ? 'tipo-entrada' : 'tipo-saida' ?>">
                                <?= htmlspecialchars($item['tipo_movimentacao']) ?>
                            </td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            <td><?= htmlspecialchars($item['data_movimentacao']) ?></td>
                            <td><?= htmlspecialchars($item['nome_funcionario']) ?></td>
                            <td><?= htmlspecialchars($item['nome_fornecedor'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['observacao']) ?></td>
                            <td><?= htmlspecialchars($item['pedido_id'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php endif; ?>
    </div>
</body>

</html>