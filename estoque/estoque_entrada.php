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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="form-wrapper">
        <h2>Registrar Entrada de Estoque</h2>
        <p>Selecione o produto e informe os detalhes da entrada.</p>

        <!-- Mensagem de retorno -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert <?= $_GET['type'] === 'success' ? 'alert-success' : 'alert-error' ?>" id="alert-msg">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <form action="processar_entrada.php" method="post">
            <!-- Produto -->
            <div class="input-group">
                <label for="produto_id">Produto:</label>
                <select id="produto_id" name="produto_id" class="select2" required>
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
                <button type="submit" class="btn btn-edit">Registrar Entrada</button>
                <a href="../index.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Selecione...",
                width: '100%'
            });

            // Esconde a mensagem após 5 segundos
            setTimeout(function() {
                $('#alert-msg').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>

</html>