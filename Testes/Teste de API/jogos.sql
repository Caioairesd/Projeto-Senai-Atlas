-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/08/2025 às 03:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gamestore`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `jogos`
--

CREATE TABLE `jogos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `imagem` text DEFAULT NULL,
  `nota` float DEFAULT NULL,
  `plataformas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `jogos`
--

INSERT INTO `jogos` (`id`, `nome`, `imagem`, `nota`, `plataformas`) VALUES
(19369, 'Call of Duty', 'https://media.rawg.io/media/games/9c5/9c5bc0b6e67102bc96dcf1ba41509e42.jpg', 4.16, 'PC, macOS, Xbox 360, PlayStation 3'),
(29179, 'God of War I', 'https://media.rawg.io/media/games/1aa/1aa4ca34a8a6bb57a2e065c8332dc230.jpg', 4.36, 'PlayStation 3, PlayStation 2, PS Vita'),
(38283, 'Guitar Hero III: Legends of Rock', 'https://media.rawg.io/media/games/444/444d319b3156101aeef3d1a8df219f3f.jpg', 4.39, 'PC, macOS, Xbox 360, PlayStation 3, PlayStation 2, Wii'),
(52998, 'Grand Theft Auto', 'https://media.rawg.io/media/games/786/786f9a212646c793ccbad196cba2cf36.jpg', 3.86, 'PC, PlayStation, Game Boy Color, Game Boy'),
(60192, 'Elden: Path of the Forgotten', 'https://media.rawg.io/media/screenshots/cfe/cfec498e5a7639219714d5fc8d9709d0.jpg', 0, 'PC, Nintendo Switch'),
(415171, 'Valorant', 'https://media.rawg.io/media/games/b11/b11127b9ee3c3701bd15b9af3286d20e.jpg', 3.52, 'PC, PlayStation 5, Xbox Series S/X'),
(546464, 'FIFA 22', 'https://media.rawg.io/media/games/355/355d2ec5d6b87518228dc30a9bb0e70d.jpg', 3.38, 'PC, PlayStation 5, Xbox One, PlayStation 4, Xbox Series S/X, Nintendo Switch');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `jogos`
--
ALTER TABLE `jogos`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
