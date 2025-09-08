<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$busca = $_GET['busca'] ?? '';

// Consulta com filtro de busca e clientes ativos
$sql = 'SELECT * FROM cliente 
        WHERE ativo = 1 AND (
            nome_cliente LIKE :busca OR 
            email_cliente LIKE :busca OR 
            telefone_cliente LIKE :busca OR 
            cnpj_cliente LIKE :busca
        )
        ORDER BY nome_cliente ASC';

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

        <!-- Campo de busca -->
        <form method="get" class="search-form">
            <input type="text" name="busca" placeholder="Buscar Cliente..." value="<?= htmlspecialchars($busca) ?>" class="input">
            <button type="submit" class="btn">Buscar</button>
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
                                <a class="btn btn-edit" href="../editar/editar_cliente.php?id=<?= $cliente['id_cliente'] ?>">Editar</a>
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

</html>