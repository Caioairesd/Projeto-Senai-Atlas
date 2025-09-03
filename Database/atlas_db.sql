-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/09/2025 às 00:44
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
-- Banco de dados: `atlas_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome_cliente` varchar(50) NOT NULL,
  `email_cliente` varchar(50) NOT NULL,
  `telefone_cliente` varchar(20) DEFAULT NULL,
  `cnpj_cliente` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_estoque` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `tipo_estoque` enum('Entrada','Saída') NOT NULL,
  `qtde_estoque` int(11) NOT NULL,
  `data_entrada` date DEFAULT NULL,
  `data_saida` date DEFAULT NULL,
  `observacao_estoque` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `estoque`
--
DELIMITER $$
CREATE TRIGGER `trg_entrada_estoque` AFTER INSERT ON `estoque` FOR EACH ROW BEGIN
UPDATE produto
SET
    qtde_estoque_produto = qtde_estoque_produto + NEW.qtde_estoque
WHERE
    id_produto = NEW.produto_id;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id_fornecedor` int(11) NOT NULL,
  `nome_fornecedor` varchar(50) NOT NULL,
  `contato_fornecedor` varchar(50) DEFAULT NULL,
  `email_fornecedor` varchar(50) NOT NULL,
  `cnpj_fornecedor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `id_funcionario` int(11) NOT NULL,
  `nome_funcionario` varchar(50) NOT NULL,
  `email_funcionario` varchar(50) NOT NULL,
  `telefone_funcionario` varchar(20) DEFAULT NULL,
  `cpf_funcionario` varchar(11) NOT NULL,
  `salario_funcionario` decimal(10,2) DEFAULT NULL,
  `endereco_funcionario` varchar(100) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `data_admissao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `id_item_pedido` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `qtde_item` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `item_pedido`
--
DELIMITER $$
CREATE TRIGGER `trg_saida_estoque` AFTER INSERT ON `item_pedido` FOR EACH ROW BEGIN
UPDATE produto
SET
    qtde_estoque_produto = qtde_estoque_produto - NEW.qtde_item
WHERE
    id_produto = NEW.produto_id;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_valida_estoque` BEFORE INSERT ON `item_pedido` FOR EACH ROW BEGIN DECLARE estoque_atual INT;

SELECT
    qtde_estoque_produto INTO estoque_atual
FROM
    produto
WHERE
    id_produto = NEW.produto_id;

IF estoque_atual < NEW.qtde_item THEN SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Estoque insuficiente para o produto solicitado.';

END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `data_pedido` date NOT NULL,
  `status_pedido` enum('Pendente','Processando','Enviado','Entregue','Cancelado') NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL,
  `nome_perfil` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `nome_perfil`) VALUES
(1, 'Administrador'),
(3, 'Estoquista'),
(2, 'Vendedor');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(50) NOT NULL,
  `descricao_produto` text DEFAULT NULL,
  `plataforma_produto` varchar(30) NOT NULL,
  `tipo_produto` varchar(30) NOT NULL,
  `preco_produto` decimal(10,2) NOT NULL,
  `qtde_estoque_produto` int(11) NOT NULL,
  `imagem_url_produto` varchar(255) DEFAULT NULL,
  `fornecedor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(30) NOT NULL,
  `email_usuario` varchar(50) NOT NULL,
  `senha_usuario` varchar(50) NOT NULL,
  `perfil_id` int(11) DEFAULT NULL,
  `funcionario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_estoque_baixo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_estoque_baixo` (
`id_produto` int(11)
,`nome_produto` varchar(50)
,`qtde_estoque_produto` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_produto_item`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_produto_item` (
`id_pedido` int(11)
,`data_pedido` date
,`nome_cliente` varchar(50)
,`nome_produto` varchar(50)
,`qtde_item` int(11)
,`preco_unitario` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_estoque_baixo`
--
DROP TABLE IF EXISTS `vw_estoque_baixo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_estoque_baixo`  AS SELECT `produto`.`id_produto` AS `id_produto`, `produto`.`nome_produto` AS `nome_produto`, `produto`.`qtde_estoque_produto` AS `qtde_estoque_produto` FROM `produto` WHERE `produto`.`qtde_estoque_produto` < 5 ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_produto_item`
--
DROP TABLE IF EXISTS `vw_produto_item`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_produto_item`  AS SELECT `p`.`id_pedido` AS `id_pedido`, `p`.`data_pedido` AS `data_pedido`, `c`.`nome_cliente` AS `nome_cliente`, `pr`.`nome_produto` AS `nome_produto`, `i`.`qtde_item` AS `qtde_item`, `i`.`preco_unitario` AS `preco_unitario` FROM (((`pedidos` `p` join `cliente` `c` on(`p`.`cliente_id` = `c`.`id_cliente`)) join `item_pedido` `i` on(`p`.`id_pedido` = `i`.`pedido_id`)) join `produto` `pr` on(`i`.`produto_id` = `pr`.`id_produto`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email_cliente` (`email_cliente`),
  ADD UNIQUE KEY `cnpj_cliente` (`cnpj_cliente`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_estoque`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id_fornecedor`),
  ADD UNIQUE KEY `email_fornecedor` (`email_fornecedor`),
  ADD UNIQUE KEY `cnpj_fornecedor` (`cnpj_fornecedor`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id_funcionario`),
  ADD UNIQUE KEY `cpf_funcionario` (`cpf_funcionario`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`id_item_pedido`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`),
  ADD UNIQUE KEY `nome_perfil` (`nome_perfil`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `fornecedor_id` (`fornecedor_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`),
  ADD KEY `perfil_id` (`perfil_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_estoque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id_fornecedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `estoque_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `item_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `item_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id_produto`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id_fornecedor`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id_perfil`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionario` (`id_funcionario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
