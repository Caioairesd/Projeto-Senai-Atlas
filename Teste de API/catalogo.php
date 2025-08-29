<?php
require 'conexao.php';
$result = $conn->query("SELECT * FROM jogos");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Jogos</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>🎮 Catálogo de Jogos</h1>
    <?php while ($jogo = $result->fetch_assoc()): ?>
        <div class="jogo">
            <h2><?= htmlspecialchars($jogo['nome']) ?></h2>
            <img src="<?= htmlspecialchars($jogo['imagem']) ?>" alt="Imagem do jogo">
            <p><strong>Nota:</strong> <?= $jogo['nota'] ?></p>
            <p><strong>Plataformas:</strong> <?= htmlspecialchars($jogo['plataformas']) ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>
