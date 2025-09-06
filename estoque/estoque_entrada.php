<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Busca produtos para o select
$sql = 'SELECT id_produto, nome_produto FROM produto ORDER BY nome_produto ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrada de Estoque</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="form-wrapper">
        <h2>Registrar Entrada de Estoque</h2>
        <p>Associe a entrada a um produto e adicione detalhes.</p>

        <form action="processar_entrada.php" method="post">
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

            <div class="input-group">
                <label for="qtde_estoque">Quantidade:</label>
                <input type="number" id="qtde_estoque" name="qtde_estoque" min="1" required>
            </div>

            <div class="input-group">
                <label for="observacao_estoque">Observação:</label>
                <textarea id="observacao_estoque" name="observacao_estoque" rows="3"></textarea>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Registrar Entrada</button>
                <a href="../index.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
