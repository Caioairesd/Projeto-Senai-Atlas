<?php
$conn = new mysqli("localhost", "root", "", "gamestore");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
