<?php
include 'processar_pedidos_lista.php'; // Inclui o arquivo de processamento da lista de pedidos (contém a lógica de busca e filtros)
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- CSS personalizado -->
    <!-- Select2 CSS para selects estilizados -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <div class="form-wrapper">
        <h2>Lista de Pedidos</h2>
        <p>Resumo de todos os pedidos cadastrados</p>

        <!-- Formulário de filtros -->
        <form method="GET" action="" class="search-form">
            <!-- Filtro por nome do cliente -->
            <input type="text" name="cliente" class="input" placeholder="Buscar pedido" value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>">

            <!-- Filtro por data inicial -->
            <label for="data_ini">De:</label>
            <input type="date" name="data_ini" class="input" value="<?= htmlspecialchars($_GET['data_ini'] ?? '') ?>">
            
            <!-- Filtro por data final -->
            <label for="data_fim">Até:</label>
            <input type="date" name="data_fim" class="input" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
            
            <!-- Filtro por status do pedido -->
            <select name="status" id="status-select" class="input">
                <option value="">Todos os status</option>
                <?php
                // Lista de status possíveis para pedidos
                $statusList = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
                foreach ($statusList as $status) {
                    // Marca como selecionado se for o filtro atual
                    $sel = (isset($_GET['status']) && $_GET['status'] === $status) ? 'selected' : '';
                    echo "<option value=\"$status\" $sel>$status</option>";
                }
                ?>
            </select>
            
            <!-- Botões de ação -->
            <button type="submit" class="btn">Filtrar</button>
            <a href="pedidos_lista.php" class="btn">Limpar Filtros</a>
        </form>

        <!-- Mensagem para quando não há pedidos -->
        <?php if (empty($pedidos)): ?>
            <div class="no-records">
                <p>Não há pedidos cadastrados.</p>
            </div>
        <?php else: ?>
            <!-- Tabela de pedidos -->
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>ID</th> <!-- ID do pedido -->
                        <th>Cliente</th> <!-- Nome do cliente -->
                        <th>Data</th> <!-- Data do pedido -->
                        <th>Status</th> <!-- Status atual do pedido -->
                        <th>Valor Total</th> <!-- Valor total do pedido -->
                        <th>Itens</th> <!-- Quantidade de itens no pedido -->
                        <th>Ações</th> <!-- Ações disponíveis -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td><?= $p['id_pedido'] ?></td> <!-- ID -->
                            <td><?= htmlspecialchars($p['nome_cliente']) ?></td> <!-- Cliente -->
                            <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td> <!-- Data formatada -->
                            <td><?= htmlspecialchars($p['status_pedido']) ?></td> <!-- Status -->
                            <td>R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></td> <!-- Valor formatado -->
                            <td><?= $p['total_itens'] ?></td> <!-- Quantidade de itens -->
                            <td><a class="btn" href="pedido_detalhe.php?id=<?= $p['id_pedido'] ?>">Ver Detalhes</a></td> <!-- Link para detalhes -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <!-- Select2 -->
    <script>
        $(document).ready(function() {
            // Inicializa Select2 no campo de status
            $('#status-select').select2({
                placeholder: "Todos os status", // Texto placeholder
                allowClear: true, // Permite limpar seleção
                width: 'style' // Respeita o CSS existente
            });
        });
    </script>
</body>

</html>