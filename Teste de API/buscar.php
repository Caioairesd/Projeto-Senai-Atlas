<?php
require 'config.php';

$busca = $_GET['busca'] ?? 'gta';
$url = "https://api.rawg.io/api/games?key=" . RAWG_API_KEY . "&search=" . urlencode($busca) . "&page_size=10";

$response = @file_get_contents($url);
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Jogos</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>🔍 Buscar Jogos</h1>
    <form method="GET">
        <input type="text" name="busca" placeholder="Digite o nome do jogo" value="<?= htmlspecialchars($busca) ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php
    if (!isset($data['results'])) {
        echo "<p>Erro ao buscar dados da API.</p>";
        exit;
    }

    foreach ($data['results'] as $jogo):
        $plataformas = array_map(fn($p) => $p['platform']['name'], $jogo['platforms']);
    ?>
        <div class="jogo">
            <h2><?= htmlspecialchars($jogo['name']) ?></h2>
            <img src="<?= htmlspecialchars($jogo['background_image']) ?>" alt="Imagem do jogo">
            <p><strong>Nota:</strong> <?= $jogo['rating'] ?></p>
            <p><strong>Plataformas:</strong> <?= implode(', ', $plataformas) ?></p>

            <form method="POST" action="salvar.php">
                <input type="hidden" name="id" value="<?= $jogo['id'] ?>">
                <input type="hidden" name="nome" value="<?= $jogo['name'] ?>">
                <input type="hidden" name="imagem" value="<?= $jogo['background_image'] ?>">
                <input type="hidden" name="nota" value="<?= $jogo['rating'] ?>">
                <input type="hidden" name="plataformas" value="<?= implode(', ', $plataformas) ?>">
                <button type="submit">Salvar no catálogo</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
