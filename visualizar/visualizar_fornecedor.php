<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';
$busca = $_GET['busca'] ?? '';

// Consulta com filtro
$sql = 'SELECT * FROM fornecedor 
        WHERE ativo = 1 AND (
            id_fornecedor = :busca OR 
            nome_fornecedor LIKE :busca OR 
            email_fornecedor LIKE :busca OR 
            contato_fornecedor LIKE :busca OR 
            cnpj_fornecedor LIKE :busca
        )
        ORDER BY nome_fornecedor ASC';


$stmt = $pdo->prepare($sql);
$termoBusca = '%' . $busca . '%';
$stmt->bindParam(':busca', $termoBusca, PDO::PARAM_STR);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Fornecedores Cadastrados</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
    <div class="table-wrapper">
        <h2>Lista de Fornecedores</h2>

        <!-- Campo de busca -->
        <form method="get" class="search-form">
            <input type="text" name="busca" placeholder="Buscar Fornecedor..." value="<?= htmlspecialchars($busca) ?>" class="input">
            <button type="submit" class="btn">Buscar</button>
            <a href="visualizar_fornecedor.php" class="btn">Limpar Filtros</a>
        </form>

        <?php if (count($fornecedores) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>ID</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                            <td class="actions">
                                <a class="btn" href="detalhes_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Ver detalhes</a>
                                <a class="btn btn-edit" href="../editar/editar_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum fornecedor encontrado com esse termo.</div>
        <?php endif; ?>
    </div>
</body>

</html>