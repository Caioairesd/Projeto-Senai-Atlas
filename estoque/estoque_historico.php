<?php
include 'processar_historico.php'; // Inclui o arquivo de processamento do histórico (que contém a lógica de busca e filtros)
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Histórico de Estoque</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- CSS personalizado -->
</head>

<body>
    <div class="form-wrapper">
        <h2>Histórico de Movimentações</h2>
        <p>Veja todas as entradas e saídas registradas no sistema de estoque</p>
        
        <!-- Formulário de filtros -->
        <form method="GET" action="" class="search-form">
            <!-- Filtro por tipo de movimentação -->
            <select name="tipo" class="input">
                <option value="">Todos os Tipos</option>
                <?php
                // Tipos de movimentação disponíveis
                $tiposMov = ['Entrada', 'Saída'];
                foreach ($tiposMov as $tipo) {
                    // Marca como selecionado se for o filtro atual
                    $sel = ($filtroTipo === $tipo) ? 'selected' : '';
                    echo "<option value=\"$tipo\" $sel>$tipo</option>";
                }
                ?>
            </select>

            <!-- Filtro por nome do produto -->
            <input type="text" name="produto" class="input" placeholder="Buscar Produto..." value="<?= htmlspecialchars($filtroProduto) ?>">

            <!-- Filtro por data inicial -->
            <label for="data_ini">De:</label>
            <input type="date" name="data_ini" class="input" value="<?= htmlspecialchars($filtroDataIni) ?>">

            <!-- Filtro por data final -->
            <label for="data_fim">Até:</label>
            <input type="date" name="data_fim" class="input" value="<?= htmlspecialchars($filtroDataFim) ?>">

            <!-- Botões de ação -->
            <button type="submit" class="btn">Filtrar</button>
            <a href="estoque_historico.php" class="btn">Limpar Filtros</a>
        </form>

        <!-- Mensagem para quando não há registros -->
        <?php if (empty($historico)): ?>
            <div class="no-records">
                <p>Não há registros de movimentação para exibir no momento.</p>
            </div>
        <?php else: ?>
            <!-- Tabela de histórico -->
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Código</th> <!-- ID da movimentação -->
                        <th>Produto</th> <!-- Nome do produto -->
                        <th>Tipo</th> <!-- Tipo (Entrada/Saída) -->
                        <th>Quantidade</th> <!-- Quantidade movimentada -->
                        <th>Data</th> <!-- Data da movimentação -->
                        <th>Funcionário</th> <!-- Nome do funcionário responsável -->
                        <th>Fornecedor</th> <!-- Nome do fornecedor (se aplicável) -->
                        <th>Observação</th> <!-- Observações da movimentação -->
                        <th>Pedido</th> <!-- ID do pedido relacionado (se aplicável) -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id_movimentacao']) ?></td> <!-- ID -->
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td> <!-- Produto -->
                            <!-- Tipo com classe CSS diferenciada por cor -->
                            <td class="<?= $item['tipo_movimentacao'] === 'Entrada' ? 'tipo-entrada' : 'tipo-saida' ?>">
                                <?= htmlspecialchars($item['tipo_movimentacao']) ?>
                            </td>
                            <td><?= htmlspecialchars($item['quantidade']) ?></td> <!-- Quantidade -->
                            <td><?= htmlspecialchars($item['data_movimentacao']) ?></td> <!-- Data -->
                            <td><?= htmlspecialchars($item['nome_funcionario']) ?></td> <!-- Funcionário -->
                            <td><?= htmlspecialchars($item['nome_fornecedor'] ?? '-') ?></td> <!-- Fornecedor (com valor padrão) -->
                            <td><?= htmlspecialchars($item['observacao']) ?></td> <!-- Observação -->
                            <td><?= htmlspecialchars($item['pedido_id'] ?? '-') ?></td> <!-- Pedido (com valor padrão) -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>