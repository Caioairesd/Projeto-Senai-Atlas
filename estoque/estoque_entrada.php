<?php
session_start(); // Inicia a sessão para manter estado do usuário
require_once '../config/conexao.php'; // Inclui arquivo de configuração de conexão com o banco de dados
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema

// Busca apenas produtos ativos para o select
$sql = 'SELECT id_produto, nome_produto FROM produto WHERE ativo = 1 ORDER BY nome_produto ASC';
$stmt = $pdo->prepare($sql); // Prepara a consulta SQL
$stmt->execute(); // Executa a consulta
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os resultados como array associativo
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Entrada de Estoque</title>
    <!-- Inclui CSS do Select2 para selects estilizados -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Inclui CSS personalizado -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <!-- Container do formulário -->
    <div class="form-wrapper">
        <h2>Registrar Entrada de Estoque</h2>
        <p>Selecione o produto e informe os detalhes da entrada.</p>

        <!-- Mensagem de retorno -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert <?= $_GET['type'] === 'success' ? 'alert-success' : 'alert-error' ?>" id="alert-msg">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de entrada de estoque -->
        <form action="processar_entrada.php" method="post">
            <!-- Seleção de produto -->
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

            <!-- Campo de quantidade -->
            <div class="input-group">
                <label for="qtde_estoque">Quantidade:</label>
                <input type="number" id="qtde_estoque" name="qtde_estoque" min="1" required>
            </div>

            <!-- Campo de observação -->
            <div class="input-group">
                <label for="observacao_estoque">Observação:</label>
                <textarea id="observacao_estoque" name="observacao_estoque" rows="3"></textarea>
            </div>

            <!-- Botões de ação -->
            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Registrar Entrada</button>
                <a href="../index.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <!-- Select2 -->
    <script>
        $(document).ready(function() {
            // Inicializa o Select2 nos elementos com classe .select2
            $('.select2').select2({
                placeholder: "Selecione...", // Texto placeholder
                width: '100%' // Largura total
            });

            // Esconde a mensagem de alerta após 5 segundos
            setTimeout(function() {
                $('#alert-msg').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>

</html>