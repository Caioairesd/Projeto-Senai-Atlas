<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Busca produtos para o select
$sql = 'SELECT id_produto, nome_produto FROM produto ORDER BY nome_produto ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca clientes para o select
$sqlClientes = 'SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente ASC';
$stmtClientes = $pdo->prepare($sqlClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Saída de Estoque</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Registrar Saída de Estoque</h2>
        <p>Informe os dados abaixo para registrar a retirada de produtos do estoque e gerar o pedido automaticamente.</p>

        <form action="processar_saida.php" method="post">
            
            <!-- Cliente -->
            <div class="input-group">
                <label for="cliente_id">Cliente:</label>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_cliente'] ?>">
                            <?= htmlspecialchars($c['nome_cliente']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Produto -->
            <div class="input-group">
                <label for="produto_id">Produto:</label>
                <select id="produto_id" name="produto_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id_produto'] ?>">
                            <?= htmlspecialchars($p['nome_produto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Quantidade -->
            <div class="input-group">
                <label for="qtde_estoque">Quantidade:</label>
                <input type="number" id="qtde_estoque" name="qtde_estoque" min="1" required>
            </div>

            <!-- Observação -->
            <div class="input-group">
                <label for="observacao_estoque">Observação:</label>
                <textarea id="observacao_estoque" name="observacao_estoque" rows="3"></textarea>
            </div>

            <!-- Botões -->
            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Registrar Saída</button>
                <a href="estoque_saida" class="btn">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
