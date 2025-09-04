<?php
require_once('../conexao.php');
include '../assets/sidebar.php';
$busca = $_GET['busca'] ?? '';

// Consulta com filtro
$sql = 'SELECT * FROM fornecedor WHERE 
        nome_fornecedor LIKE :busca OR 
        email_fornecedor LIKE :busca OR 
        contato_fornecedor LIKE :busca OR 
        cnpj_fornecedor LIKE :busca 
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
        <form method="get" style="margin-bottom: 20px;">
            <input type="text" name="busca" placeholder="Buscar fornecedor..." value="<?= htmlspecialchars($busca) ?>"
                class="input" style="max-width: 300px;">
            <button type="submit" class="btn" style="margin-left: 10px;"> Buscar</button>
        </form>

        <?php if (count($fornecedores) > 0): ?>
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
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['email_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['contato_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['cnpj_fornecedor']) ?></td>
                            <td class="actions">
                                <div class="btn-group">
                                    <a class="btn" href="detalhes_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Ver</a>
                                    <a class="btn btn-edit"
                                        href="../editar/editar_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>">Editar</a>
                                    <a class="btn btn-delete"
                                        href="../excluir/excluir_fornecedor.php?id=<?= $fornecedor['id_fornecedor'] ?>"
                                        onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                                </div>
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