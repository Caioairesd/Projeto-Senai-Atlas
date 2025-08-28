<?php
require 'conexao.php';

$url = "https://raw.githubusercontent.com/AdemarCastro/game-store-api/main/games.json"; // Exemplo de fonte JSON

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$games = json_decode($response, true);

foreach ($games as $game) {
    $stmt = $pdo->prepare("INSERT INTO games (name, genero, description, year, price, urlImage, available) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $game['name'],
        $game['genero'],
        $game['description'],
        $game['year'],
        $game['price'],
        $game['urlImage'],
        $game['available']
    ]);
}

echo "Jogos importados com sucesso!";
?>
