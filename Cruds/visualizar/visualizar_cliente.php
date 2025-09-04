<?php
require_once('../conexao.php');
include '../assets/sidebar.php';
$busca = $_GET['busca'] ?? '';

// Consulta com filtro
$sql = 'SELECT * FROM cliente WHERE 
        nome_cliente LIKE :busca OR 
        email_cliente LIKE :busca OR 
        telefone_cliente LIKE :busca OR 
        cnpj_cliente LIKE :busca 
        ORDER BY nome_cliente ASC';

$stmt = $pdo->prepare($sql);
$termoBusca = '%' . $busca . '%';
$stmt->bindParam(':busca', $termoBusca, PDO::PARAM_STR);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script>
    function abrirModal(url) {
        const overlay = document.getElementById('modal-overlay');
        const body = document.getElementById('modal-body');

        overlay.style.display = 'flex';
        body.innerHTML = 'Carregando...';

        fetch(url)
            .then(res => res.text())
            .then(html => {
                body.innerHTML = html;
            })
            .catch(() => {
                body.innerHTML = '<p>Erro ao carregar conteúdo.</p>';
            });
    }

    function fecharModal() {
        document.getElementById('modal-overlay').style.display = 'none';
        document.getElementById('modal-body').innerHTML = '';
    }
</script>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Clientes Cadastrados</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
    <div class="table-wrapper">
        <h2>Lista de Clientes</h2>

        <!-- Campo de busca -->
        <form method="get" style="margin-bottom: 20px;">
            <input type="text" name="busca" placeholder="Buscar cliente..." value="<?= htmlspecialchars($busca) ?>" class="input" style="max-width: 300px;">
            <button type="submit" class="btn" style="margin-left: 10px;"> Buscar</button>
        </form>

        <?php if (count($clientes) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CNPJ</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['nome_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['email_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['cnpj_cliente']) ?></td>
                            <td class="actions">
                                <a class="btn" href="#" onclick="abrirModal('detalhes_cliente.php?id=<?= $cliente['id_cliente'] ?>'); return false;">Ver Detalhes</a>

                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum cliente encontrado com esse termo.</div>
        <?php endif; ?>
    </div>
    <div id="modal-overlay" style="display: none;" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="fecharModal()">✖</button>
            <div id="modal-body">Carregando...</div>
        </div>
    </div>

</body>

</html>