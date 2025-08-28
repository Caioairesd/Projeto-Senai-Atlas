<?php
require 'conexao.php';

$stmt = $pdo->query("SELECT * FROM games");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Jogos</title>
  <style>
    .game { border: 1px solid #ccc; padding: 10px; margin: 10px; width: 300px; float: left; }
    img { max-width: 100%; height: auto; }
  </style>
</head>
<body>
  <h1>Catálogo de Jogos</h1>
  <?php foreach ($games as $game): ?>
    <div class="game">
      <h2><?= htmlspecialchars($game['name']) ?></h2>
      <img src="<?= htmlspecialchars($game['urlImage']) ?>" alt="Imagem do jogo">
      <p><strong>Gênero:</strong> <?= $game['genero'] ?></p>
      <p><strong>Ano:</strong> <?= $game['year'] ?></p>
      <p><strong>Preço:</strong> R$ <?= $game['price'] ?></p>
      <p><?= $game['description'] ?></p>
      <p><em><?= $game['available'] ?></em></p>
    </div>
  <?php endforeach; ?>
</body>
</html>
