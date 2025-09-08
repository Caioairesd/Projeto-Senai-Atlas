<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';
$busca = $_GET['busca'] ?? '';

// Consulta com filtro
$sql = 'SELECT * FROM produto WHERE 
        ativo = 1 AND (
            nome_produto LIKE :busca OR 
            descricao_produto LIKE :busca OR 
            plataforma_produto LIKE :busca OR 
            tipo_produto LIKE :busca
        )
        ORDER BY nome_produto ASC';

$stmt = $pdo->prepare($sql);
$termoBusca = '%' . $busca . '%';
$stmt->bindParam(':busca', $termoBusca, PDO::PARAM_STR);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Produtos Cadastrados</title>
    <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
    <div class="table-wrapper">
        <h2>Lista de Produtos</h2>


        <!-- Campo de busca -->
        <form method="get" class="search-form">
            <input type="text" name="busca" placeholder="Buscar Produto..." value="<?= htmlspecialchars($busca) ?>" class="input">
            <button type="submit" class="btn">Buscar</button>
            <a href="visualizar_produto.php" class="btn">Limpar Filtros</a>
        </form>

        <?php if (count($produtos) > 0): ?>
            <table class="table table-produto">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Plataforma</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td>
                                <img src="exibir_imagem.php?tipo=produto&id=<?= $produto['id_produto'] ?>"
                                    style="width:80px; height:80px; object-fit:cover; border-radius:6px;"
                                    alt="Imagem do Produto">
                            </td>
                            <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                            <td><?= htmlspecialchars($produto['plataforma_produto']) ?></td>
                            <td><?= htmlspecialchars($produto['tipo_produto']) ?></td>
                            <td>R$ <?= number_format($produto['preco_produto'], 2, ',', '.') ?></td>
                            <td>
                                <div class="btn-group">

                                    <a class="btn" href="detalhes_produto.php?id=<?= $produto['id_produto'] ?>">Ver detalhes</a>
                                    <a class="btn btn-edit"
                                        href="../editar/editar_produto.php?id=<?= $produto['id_produto'] ?>">Editar</a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum produto encontrado com esse termo.</div>
        <?php endif; ?>
    </div>
</body>

</html>