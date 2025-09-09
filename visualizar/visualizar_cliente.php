<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$busca = $_GET['busca'] ?? '';

// Consulta com filtro de busca e clientes ativos
$sql = 'SELECT * FROM cliente 
        WHERE ativo = 1 AND (
            id_cliente LIKE :busca OR 
            nome_cliente LIKE :busca OR 
            email_cliente LIKE :busca OR 
            telefone_cliente LIKE :busca OR 
            cnpj_cliente LIKE :busca
        )
        ORDER BY id_cliente ASC';


$stmt = $pdo->prepare($sql);
$termoBusca = '%' . $busca . '%';
$stmt->bindParam(':busca', $termoBusca, PDO::PARAM_STR);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Clientes Cadastrados</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
    <div class="table-wrapper">
        <h2>Lista de Clientes Ativos</h2>
        <?php if (isset($_GET['msg'])): ?>
            <?php
            $tipo = $_GET['msg'] === 'atualizado' ? 'success' : ($_GET['msg'] === 'erro' ? 'error' : 'warning');
            $texto = match ($_GET['msg']) {
                'atualizado' => 'Cliente atualizado com sucesso!',
                'erro' => 'Erro ao atualizar cliente.',
                default => htmlspecialchars($_GET['msg']),
            };
            ?>
            <div class="alert alert-<?= $tipo ?>"><?= $texto ?></div>
        <?php endif; ?>

        <!-- Campo de busca -->
        <form method="get" class="search-form">
            <input type="text" name="busca" placeholder="Buscar Cliente..." value="<?= htmlspecialchars($busca) ?>"
                class="input">
            <button type="submit" class="btn">Buscar</button>
            <a href="visualizar_cliente.php" class="btn">Limpar Filtros</a>
        </form>

        <?php if (count($clientes) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>ID</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['nome_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
                            <td class="actions">
                                <a class="btn" href="detalhes_cliente.php?id=<?= $cliente['id_cliente'] ?>">Ver detalhes</a>
                                <a class="btn btn-delete" href="../excluir/excluir_cliente.php?id=<?= $cliente['id_cliente'] ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum cliente ativo encontrado com esse termo.</div>
        <?php endif; ?>
    </div>
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