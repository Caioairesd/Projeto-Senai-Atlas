<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// Filtros
$filtro = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$plataforma = $_GET['plataforma'] ?? '';
$tipo = $_GET['tipo'] ?? '';
$status = $_GET['status'] ?? '';

// Monta SQL com todos os filtros
$sql = "SELECT * FROM vw_estoque_geral WHERE ativo = 1";
$params = [];

if ($filtro !== '') {
    $sql .= " AND nome_produto LIKE ?";
    $params[] = "%{$filtro}%";
}
if ($plataforma !== '') {
    $sql .= " AND plataforma_produto = ?";
    $params[] = $plataforma;
}
if ($tipo !== '') {
    $sql .= " AND tipo_produto = ?";
    $params[] = $tipo;
}
if ($status !== '') {
    $sql .= " AND status_estoque = ?";
    $params[] = $status;
}

// Dados para os selects
$plataformas = $pdo->query("SELECT DISTINCT plataforma_produto FROM vw_estoque_geral WHERE plataforma_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$tipos = $pdo->query("SELECT DISTINCT tipo_produto FROM vw_estoque_geral WHERE tipo_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$statuses = $pdo->query("SELECT DISTINCT status_estoque FROM vw_estoque_geral WHERE status_estoque IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

// Executa consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estoque Geral</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Estoque Geral</h2>
        <p>Visão consolidada de todos os produtos e seus níveis de estoque</p>
        <!-- Campo de busca -->
        <form method="get" class="search-form">
            <input type="text" name="busca" placeholder="Buscar Produto..." value="<?= htmlspecialchars($filtro) ?>"
                class="input">

            <select name="plataforma" class="input">
                <option value="">Todas as Plataformas</option>
                <?php foreach ($plataformas as $p): ?>
                    <option value="<?= $p ?>" <?= $plataforma === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Select com busca -->
            <select name="tipo" class="input">
                <option value="">Todas as categorias</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t ?>" <?= $tipo === $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>



            <button type="submit" class="btn">Filtrar</button>
            <a href="estoque_geral.php" class="btn">Limpar Filtros</a>
        </form>



        <?php if (empty($estoque)): ?>
            <div class="no-records">
                <p>Não há produtos cadastrados ou que correspondam à busca.</p>
            </div>
        <?php else: ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Plataforma</th>
                        <th>Tipo</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estoque as $item): ?>
                        <tr style="background-color: <?= $item['status_estoque'] === 'Baixo' ? '#ffcccc' : '#ccffcc' ?>;">
                            <td><?= htmlspecialchars($item['id_produto']) ?></td>
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                            <td><?= htmlspecialchars($item['plataforma_produto']) ?></td>
                            <td><?= htmlspecialchars($item['tipo_produto']) ?></td>
                            <td>R$ <?= number_format($item['preco_produto'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($item['qtde_estoque_produto']) ?></td>
                            <td><?= htmlspecialchars($item['status_estoque']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <!-- jQuery + Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function () {
            $('#tipo').select2({
                placeholder: "Todas as categorias",
                allowClear: true
            });
        });
    </script>
</body>

</html>