<?php
require_once '../config/conexao.php'; // Inclui arquivo de configuração de conexão com o banco de dados
include '../assets/sidebar.php'; // Inclui a barra lateral do sistema

// Inicializa filtros da busca
$filtro = isset($_GET['busca']) ? trim($_GET['busca']) : ''; // Filtro de texto para nome do produto
$plataforma = $_GET['plataforma'] ?? ''; // Filtro por plataforma
$tipo = $_GET['tipo'] ?? ''; // Filtro por tipo/categoria
$status = $_GET['status'] ?? ''; // Filtro por status de estoque

// Monta SQL base com view de estoque geral e produtos ativos
$sql = "SELECT * FROM vw_estoque_geral WHERE ativo = 1";
$params = []; // Array para armazenar parâmetros da consulta

// Adiciona condições de filtro conforme preenchimento
if ($filtro !== '') {
    $sql .= " AND nome_produto LIKE ?"; // Filtra por nome do produto
    $params[] = "%{$filtro}%"; // Adiciona parâmetro com wildcards
}
if ($plataforma !== '') {
    $sql .= " AND plataforma_produto = ?"; // Filtra por plataforma
    $params[] = $plataforma; // Adiciona parâmetro
}
if ($tipo !== '') {
    $sql .= " AND tipo_produto = ?"; // Filtra por tipo/categoria
    $params[] = $tipo; // Adiciona parâmetro
}
if ($status !== '') {
    $sql .= " AND status_estoque = ?"; // Filtra por status de estoque
    $params[] = $status; // Adiciona parâmetro
}

// Busca dados para preencher os selects de filtro
$plataformas = $pdo->query("SELECT DISTINCT plataforma_produto FROM vw_estoque_geral WHERE plataforma_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Plataformas distintas
$tipos = $pdo->query("SELECT DISTINCT tipo_produto FROM vw_estoque_geral WHERE tipo_produto IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Tipos distintos
$statuses = $pdo->query("SELECT DISTINCT status_estoque FROM vw_estoque_geral WHERE status_estoque IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN); // Status distintos

// Executa consulta principal com filtros aplicados
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estoque = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém resultados
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estoque Geral</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- CSS personalizado -->
</head>

<body>
    <div class="form-wrapper">
        <h2>Estoque Geral</h2>
        <p>Visão consolidada de todos os produtos e seus níveis de estoque</p>
        
        <!-- Formulário de filtros -->
        <form method="get" class="search-form">
            <!-- Campo de busca por texto -->
            <input type="text" name="busca" placeholder="Buscar Produto..." value="<?= htmlspecialchars($filtro) ?>"
                class="input">

            <!-- Filtro por plataforma -->
            <select name="plataforma" class="input">
                <option value="">Todas as Plataformas</option>
                <?php foreach ($plataformas as $p): ?>
                    <option value="<?= $p ?>" <?= $plataforma === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Filtro por tipo/categoria -->
            <select name="tipo" class="input">
                <option value="">Todas as categorias</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t ?>" <?= $tipo === $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Botões de ação -->
            <button type="submit" class="btn">Filtrar</button>
            <a href="estoque_geral.php" class="btn">Limpar Filtros</a>
        </form>

        <!-- Mensagem de estoque vazio -->
        <?php if (empty($estoque)): ?>
            <div class="no-records">
                <p>Não há produtos cadastrados ou que correspondam à busca.</p>
            </div>
        <?php else: ?>
            <!-- Tabela de resultados -->
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
                        <!-- Linha da tabela com cor de fundo baseada no status -->
                        <tr style="background-color: <?= $item['status_estoque'] === 'Baixo' ? '#ffcccc' : '#ccffcc' ?>;">
                            <td><?= htmlspecialchars($item['id_produto']) ?></td> <!-- ID do produto -->
                            <td><?= htmlspecialchars($item['nome_produto']) ?></td> <!-- Nome do produto -->
                            <td><?= htmlspecialchars($item['plataforma_produto']) ?></td> <!-- Plataforma -->
                            <td><?= htmlspecialchars($item['tipo_produto']) ?></td> <!-- Tipo/Categoria -->
                            <td>R$ <?= number_format($item['preco_produto'], 2, ',', '.') ?></td> <!-- Preço formatado -->
                            <td><?= htmlspecialchars($item['qtde_estoque_produto']) ?></td> <!-- Quantidade em estoque -->
                            <td><?= htmlspecialchars($item['status_estoque']) ?></td> <!-- Status do estoque -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> <!-- CSS do Select2 -->

    <script>
        $(document).ready(function () {
            // Inicializa Select2 no campo de tipo
            $('#tipo').select2({
                placeholder: "Todas as categorias", // Texto placeholder
                allowClear: true // Permite limpar seleção
            });
        });
    </script>
</body>

</html>