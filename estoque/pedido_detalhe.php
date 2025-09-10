<?php
include 'processar_pedido_detalhe.php';
include '../assets/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhe do Pedido</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <div class="form-wrapper">
    <?php if (isset($_GET['msg']) && isset($_GET['type'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type']) ?>">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>
        <h2>Pedido #<?= $pedido['id_pedido'] ?> - <?= htmlspecialchars($pedido['nome_cliente']) ?></h2>
        <p>Status atual: <?= htmlspecialchars($pedido['status_pedido']) ?></p>
      
        <!-- Formulário para alterar status -->
        <form method="POST">
            <div class="input-group">
                <label for="status">Alterar Status</label>
                <select name="status" id="status" class="select2" required>
                    <?php
                    $statusList = ['Pendente', 'Processando', 'Enviado', 'Entregue', 'Cancelado'];
                    foreach ($statusList as $status) {
                        $sel = $pedido['status_pedido'] === $status ? 'selected' : '';
                        echo "<option value=\"$status\" $sel>$status</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Alterar Status</button>
        </form>

        <h3>Itens do Pedido</h3>
        <?php if (empty($itens)): ?>
            <div class="no-records">
                <p>Este pedido não possui itens cadastrados.</p>
            </div>
        <?php else: ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                            <td><?= $item['qtde_item'] ?></td>
                            <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inicializa o Select2 para o campo de status
        $(document).ready(function () {
            $('#status').select2({
                placeholder: "Selecione o status",
                width: '100%'
            });
        });
    </script>
</body>
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // remove do DOM após o fade
        }
    }, 3000); // tempo antes de começar a desaparecer
</script>
</html>