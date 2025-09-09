CREATE TABLE cliente (
  id_cliente int(11) NOT NULL,
  nome_cliente varchar(50) NOT NULL,
  email_cliente varchar(50) NOT NULL,
  telefone_cliente varchar(20) DEFAULT NULL,
  cnpj_cliente varchar(20) NOT NULL,
  ativo tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO cliente (id_cliente, nome_cliente, email_cliente, telefone_cliente, cnpj_cliente, ativo) VALUES
(2, 'cliente_teste', 'CLIENTETESTE@CLIENTETESTE', '(12) 38383-8388', '84.484.884/8484-44', 0),
(3, 'Pichau', 'compras@pichau.com.br', '(47) 98506-7733', '34.344.444/4443-33', 1),
(4, 'akdkkd', 'akdak@aksksk', '(89) 98883-8383', '83.838.494/9219-42', 1),
(5, 'Grupo Nova Era', 'contato@novaera.com.br', '(11) 98765-4321', '12.345.678/0001-90', 1),
(6, 'Distribuidora Sul Brasil', 'vendas@sulbrasil.com', '(51) 99876-5432', '98.765.432/0001-10', 1),
(7, 'TechMax Solutions', 'suporte@techmax.com.br', '(21) 97654-3210', '45.678.901/0001-22', 1),
(8, 'Comercial Andrade', 'andrade@comercial.com', '(31) 96543-2109', '67.890.123/0001-33', 0),
(9, 'Alimentos Vitória', 'compras@vitoriafoods.com.br', '(41) 95432-1098', '23.456.789/0001-44', 1),
(10, 'Construtora Vale Norte', 'financeiro@valenorte.com', '(61) 94321-0987', '34.567.890/0001-55', 1),
(11, 'Auto Peças Joinville', 'joinville@autoparts.com.br', '(47) 93210-9876', '56.789.012/0001-66', 1),
(12, 'Farmácia Popular', 'contato@farmaciapopular.com', '(85) 92109-8765', '78.901.234/0001-77', 1),
(13, 'Loja do Mecânico', 'mecanico@loja.com.br', '(95) 91098-7654', '89.012.345/0001-88', 1),
(14, 'Serviços Express', 'express@servicos.com.br', '(71) 90987-6543', '90.123.456/0001-99', 1);
CREATE TABLE fornecedor (
  id_fornecedor int(11) NOT NULL,
  nome_fornecedor varchar(50) NOT NULL,
  contato_fornecedor varchar(50) DEFAULT NULL,
  email_fornecedor varchar(50) NOT NULL,
  cnpj_fornecedor varchar(20) NOT NULL,
  ativo tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO fornecedor (id_fornecedor, nome_fornecedor, contato_fornecedor, email_fornecedor, cnpj_fornecedor, ativo) VALUES
(2, 'fornecedortestaaa', '(77) 77777-7777', 'fornecedorteste@fornecedorteste', '23.333.333/3333-33', 0),
(3, 'Kabum', '(47) 98506-7733', 'compras@pichau.com.br', '34.344.444/4443-33', 1),
(4, 'Distribuidora Alfa', '(11) 98888-1234', 'contato@alfa.com.br', '12.345.678/0001-90', 1),
(5, 'TechParts Brasil', '(21) 97777-4321', 'suporte@techparts.com', '98.765.432/0001-10', 1),
(6, 'Comercial Vitória', '(31) 96666-5678', 'vendas@comercialvitoria.com.br', '45.678.901/0001-22', 1),
(7, 'Indústria Nova Era', '(41) 95555-8765', 'financeiro@novaera.com', '67.890.123/0001-33', 0),
(8, 'Auto Peças Joinville', '(47) 94444-3456', 'joinville@autoparts.com.br', '56.789.012/0001-66', 1),
(9, 'Farmácia Popular', '(85) 93333-6543', 'contato@farmaciapopular.com', '78.901.234/0001-77', 1),
(10, 'Construtora Vale Norte', '(61) 92222-9876', 'valenorte@construtora.com.br', '34.567.890/0001-55', 1),
(11, 'Serviços Express', '(71) 91111-7890', 'express@servicos.com.br', '90.123.456/0001-99', 0),
(12, 'Distribuidora Sul Brasil', '(51) 90000-1122', 'vendas@sulbrasil.com', '23.456.789/0001-44', 1),
(13, 'Grupo Maxx', '(31) 98888-3344', 'grupo@maxx.com.br', '89.012.345/0001-88', 1);
CREATE TABLE funcionario (
  id_funcionario int(11) NOT NULL,
  nome_funcionario varchar(50) NOT NULL,
  email_funcionario varchar(50) NOT NULL,
  telefone_funcionario varchar(20) DEFAULT NULL,
  cpf_funcionario varchar(11) NOT NULL,
  salario_funcionario decimal(10,2) DEFAULT NULL,
  endereco_funcionario varchar(100) DEFAULT NULL,
  data_nascimento date DEFAULT NULL,
  data_admissao date DEFAULT NULL,
  imagem_url_funcionario longblob DEFAULT NULL,
  ativo tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO funcionario (id_funcionario, nome_funcionario, email_funcionario, telefone_funcionario, cpf_funcionario, salario_funcionario, endereco_funcionario, data_nascimento, data_admissao, imagem_url_funcionario, ativo) 
VALUES (3, 'CAIO VINICIUS AIRES DA SILVA', 'caiovns30@gmail.com', '(47) 99239-4209', '222.213.333', 3333.33, 'rua um', '2025-09-01', '2025-09-10','', 1), 
(4, 'bruno', 'bruno@atlas.com', '(99) 88338-8383', '383.899.212', 8.88, 'rua', '2025-09-01', '2025-09-04', '', 1);
CREATE TABLE item_pedido (
  id_item_pedido int(11) NOT NULL,
  pedido_id int(11) DEFAULT NULL,
  produto_id int(11) DEFAULT NULL,
  qtde_item int(11) NOT NULL,
  preco_unitario decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO item_pedido (id_item_pedido, pedido_id, produto_id, qtde_item, preco_unitario) VALUES
(2, 6, 2, 1, 11.00),
(3, 7, 2, 9, 11.00),
(4, 8, 2, 8, 11.00),
(6, 10, 2, 1, 11.00),
(7, 11, 2, 1, 11.00),
(8, 12, 2, 11, 11.00);
CREATE TABLE movimentacao (
  id_movimentacao int(11) NOT NULL,
  tipo_movimentacao enum('Entrada','Saída') NOT NULL,
  quantidade int(11) NOT NULL,
  data_movimentacao date NOT NULL,
  produto_id int(11) NOT NULL,
  fornecedor_id int(11) DEFAULT NULL,
  funcionario_id int(11) DEFAULT NULL,
  pedido_id int(11) DEFAULT NULL,
  observacao text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO movimentacao (id_movimentacao, tipo_movimentacao, quantidade, data_movimentacao, produto_id, fornecedor_id, funcionario_id, pedido_id, observacao) VALUES
(1, 'Entrada', 10, '2025-09-07', 2, NULL, NULL, NULL, ''),
(2, 'Saída', 1, '2025-09-07', 2, NULL, NULL, NULL, ''),
(3, 'Saída', 1, '2025-09-06', 2, NULL, NULL, 6, 'Saída automática do pedido #6'),
(4, 'Entrada', 1, '2025-09-06', 2, NULL, NULL, 6, 'Estorno do pedido cancelado #6'),
(5, 'Saída', 9, '2025-09-07', 2, NULL, NULL, NULL, ''),
(6, 'Entrada', 9, '2025-09-07', 2, NULL, NULL, NULL, ''),
(7, 'Saída', 8, '2025-09-07', 2, NULL, NULL, NULL, ''),
(9, 'Saída', 1, '2025-09-08', 2, NULL, 3, NULL, ''),
(10, 'Entrada', 122, '2025-09-08', 2, 2, 3, NULL, ''),
(11, 'Entrada', 1, '2025-09-08', 2, 2, 3, NULL, ''),
(12, 'Saída', 1, '2025-09-08', 2, NULL, 3, NULL, ''),
(13, 'Saída', 1, '2025-09-08', 2, NULL, NULL, 11, 'Saída automática do pedido #11'),
(14, 'Saída', 11, '2025-09-08', 2, NULL, 3, NULL, ''),
(15, 'Saída', 11, '2025-09-08', 2, NULL, NULL, 12, 'Saída automática do pedido #12');
DELIMITER $$
CREATE TRIGGER trg_entrada_movimentacao AFTER INSERT ON movimentacao FOR EACH ROW BEGIN
    IF NEW.tipo_movimentacao = 'Entrada' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto + NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER trg_saida_movimentacao AFTER INSERT ON movimentacao FOR EACH ROW BEGIN
    IF NEW.tipo_movimentacao = 'Saída' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto - NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER trg_valida_movimentacao BEFORE INSERT ON movimentacao FOR EACH ROW BEGIN
    DECLARE estoque_atual INT;
    IF NEW.tipo_movimentacao = 'Saída' THEN
        SELECT qtde_estoque_produto
        INTO estoque_atual
        FROM produto
        WHERE id_produto = NEW.produto_id;
        IF estoque_atual < NEW.quantidade THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Estoque insuficiente para o produto solicitado.';
        END IF;
    END IF;
END
$$
DELIMITER ;
CREATE TABLE pedidos (
  id_pedido int(11) NOT NULL,
  cliente_id int(11) DEFAULT NULL,
  data_pedido date NOT NULL,
  status_pedido enum('Pendente','Processando','Enviado','Entregue','Cancelado') NOT NULL,
  valor_total decimal(10,2) NOT NULL,
  usuario_id int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO pedidos (id_pedido, cliente_id, data_pedido, status_pedido, valor_total, usuario_id) VALUES
(6, 2, '2025-09-07', 'Entregue', 0.00, NULL),
(7, 2, '2025-09-07', 'Pendente', 0.00, NULL),
(8, 2, '2025-09-07', 'Pendente', 0.00, NULL),
(10, 2, '2025-09-08', 'Pendente', 0.00, NULL),
(11, 2, '2025-09-08', 'Processando', 0.00, NULL),
(12, 3, '2025-09-08', 'Processando', 0.00, NULL);
DELIMITER $$
CREATE TRIGGER trg_pedido_estorno AFTER UPDATE ON pedidos FOR EACH ROW BEGIN
    IF NEW.status_pedido = 'Cancelado' 
       AND OLD.status_pedido <> 'Cancelado' THEN
        INSERT INTO movimentacao (tipo_movimentacao, quantidade, data_movimentacao, produto_id, pedido_id, observacao)
        SELECT 'Entrada', qtde_item, CURDATE(), produto_id, NEW.id_pedido, CONCAT('Estorno do pedido cancelado #', NEW.id_pedido)
        FROM item_pedido
        WHERE pedido_id = NEW.id_pedido;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER trg_pedido_saida AFTER UPDATE ON pedidos FOR EACH ROW BEGIN
    IF NEW.status_pedido IN ('Processando','Enviado') 
       AND OLD.status_pedido NOT IN ('Processando','Enviado') THEN
        INSERT INTO movimentacao (tipo_movimentacao, quantidade, data_movimentacao, produto_id, pedido_id, observacao)
        SELECT 'Saída', qtde_item, CURDATE(), produto_id, NEW.id_pedido, CONCAT('Saída automática do pedido #', NEW.id_pedido)
        FROM item_pedido
        WHERE pedido_id = NEW.id_pedido;
    END IF;
END
$$
DELIMITER ;
CREATE TABLE perfil (
  id_perfil int(11) NOT NULL,
  nome_perfil varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO perfil (id_perfil, nome_perfil) VALUES
(1, 'Administrador'),
(3, 'Estoquista'),
(2, 'Vendedor');
CREATE TABLE produto (
  id_produto int(11) NOT NULL,
  nome_produto varchar(50) NOT NULL,
  descricao_produto text DEFAULT NULL,
  plataforma_produto varchar(30) NOT NULL,
  tipo_produto varchar(30) NOT NULL,
  preco_produto decimal(10,2) NOT NULL,
  qtde_estoque_produto int(11) NOT NULL,
  imagem_url_produto longblob DEFAULT NULL,
  fornecedor_id int(11) DEFAULT NULL,
  ativo tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(2, 'Grand Thief Auto', 'aaaa', 'aaa', 'aaa', 11.00, 99, '', 2, 0);
INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(3, 'God of War Ragnarok', 'Kratos e Atreus enfrentam deuses nórdicos em uma jornada épica e emocional', 'PlayStation 5', 'Aventura', 299.99, 0, '', 6, 1);
INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(4, 'Elden Ring', 'Um mundo sombrio e vasto criado por Hidetaka Miyazaki e George R R Martin', 'Xbox Series X/S', 'RPG', 259.00, 0, '', 7, 1);
INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(5, 'Grand Theft Auto V', 'Três protagonistas uma cidade viva e missões insanas em Los Santos', 'PlayStation 4', 'Sandbox', 149.90, 0, '', 4, 1);
CREATE TABLE usuario (
  id_usuario int(11) NOT NULL,
  nome_usuario varchar(30) NOT NULL,
  email_usuario varchar(50) NOT NULL,
  senha_usuario varchar(255) NOT NULL,
  token_recuperacao varchar(255) DEFAULT NULL,
  token_expira datetime DEFAULT NULL,
  perfil_id int(11) DEFAULT NULL,
  funcionario_id int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO usuario (id_usuario, nome_usuario, email_usuario, senha_usuario, token_recuperacao, token_expira, perfil_id, funcionario_id) VALUES
(4, 'bruno', 'bruno@atlas.com', '$2y$10$vOZKJ3YiEEGG1rhe8QATQOC8M4I./OqKxp60bywegvJcmxI3rDmai', NULL, NULL, 1, 4);
CREATE TABLE vw_estoque_baixo (
id_produto int(11),
nome_produto varchar(50),
qtde_estoque_produto int(11)
);
CREATE TABLE vw_estoque_geral (
id_produto int(11)
,nome_produto varchar(50)
,plataforma_produto varchar(30)
,tipo_produto varchar(30)
,preco_produto decimal(10,2)
,qtde_estoque_produto int(11)
,status_estoque varchar(5)
);
CREATE TABLE vw_pedidos_resumo (
id_pedido int(11)
,nome_cliente varchar(50)
,data_pedido date
,status_pedido enum('Pendente','Processando','Enviado','Entregue','Cancelado')
,valor_total decimal(42,2)
,total_itens bigint(21)
);
CREATE TABLE vw_produto_item (
id_pedido int(11)
,data_pedido date
,nome_cliente varchar(50)
,nome_produto varchar(50)
,qtde_item int(11)
,preco_unitario decimal(10,2)
);
DROP TABLE IF EXISTS vw_estoque_baixo;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_estoque_baixo  AS SELECT produto.id_produto AS id_produto, produto.nome_produto AS nome_produto, produto.qtde_estoque_produto AS qtde_estoque_produto FROM produto WHERE produto.qtde_estoque_produto < 5 ;
DROP TABLE IF EXISTS vw_estoque_geral;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_estoque_geral  AS SELECT p.id_produto AS id_produto, p.nome_produto AS nome_produto, p.plataforma_produto AS plataforma_produto, p.tipo_produto AS tipo_produto, p.preco_produto AS preco_produto, p.qtde_estoque_produto AS qtde_estoque_produto, CASE WHEN p.qtde_estoque_produto < 5 THEN 'Baixo' ELSE 'OK' END AS status_estoque FROM produto AS p ;
DROP TABLE IF EXISTS vw_pedidos_resumo;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_pedidos_resumo  AS SELECT p.id_pedido AS id_pedido, c.nome_cliente AS nome_cliente, p.data_pedido AS data_pedido, p.status_pedido AS status_pedido, sum(ip.qtde_item * ip.preco_unitario) AS valor_total, count(ip.id_item_pedido) AS total_itens FROM ((pedidos p join cliente c on(c.id_cliente = p.cliente_id)) join item_pedido ip on(ip.pedido_id = p.id_pedido)) GROUP BY p.id_pedido, c.nome_cliente, p.data_pedido, p.status_pedido ;
DROP TABLE IF EXISTS vw_produto_item;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_produto_item  AS SELECT p.id_pedido AS id_pedido, p.data_pedido AS data_pedido, c.nome_cliente AS nome_cliente, pr.nome_produto AS nome_produto, i.qtde_item AS qtde_item, i.preco_unitario AS preco_unitario FROM (((pedidos p join cliente c on(p.cliente_id = c.id_cliente)) join item_pedido i on(p.id_pedido = i.pedido_id)) join produto pr on(i.produto_id = pr.id_produto)) ;
ALTER TABLE cliente
  ADD PRIMARY KEY (id_cliente),
  ADD UNIQUE KEY email_cliente (email_cliente),
  ADD UNIQUE KEY cnpj_cliente (cnpj_cliente);
ALTER TABLE fornecedor
  ADD PRIMARY KEY (id_fornecedor),
  ADD UNIQUE KEY email_fornecedor (email_fornecedor),
  ADD UNIQUE KEY cnpj_fornecedor (cnpj_fornecedor);
ALTER TABLE funcionario
  ADD PRIMARY KEY (id_funcionario),
  ADD UNIQUE KEY cpf_funcionario (cpf_funcionario);
ALTER TABLE item_pedido
  ADD PRIMARY KEY (id_item_pedido),
  ADD KEY pedido_id (pedido_id),
  ADD KEY produto_id (produto_id);
ALTER TABLE movimentacao
  ADD PRIMARY KEY (id_movimentacao),
  ADD KEY produto_id (produto_id),
  ADD KEY fornecedor_id (fornecedor_id),
  ADD KEY funcionario_id (funcionario_id),
  ADD KEY pedido_id (pedido_id);
ALTER TABLE pedidos
  ADD PRIMARY KEY (id_pedido),
  ADD KEY usuario_id (usuario_id),
  ADD KEY cliente_id (cliente_id);
ALTER TABLE perfil
  ADD PRIMARY KEY (id_perfil),
  ADD UNIQUE KEY nome_perfil (nome_perfil);
ALTER TABLE produto
  ADD PRIMARY KEY (id_produto),
  ADD KEY fornecedor_id (fornecedor_id);
ALTER TABLE usuario
  ADD PRIMARY KEY (id_usuario),
  ADD UNIQUE KEY email_usuario (email_usuario),
  ADD KEY perfil_id (perfil_id),
  ADD KEY funcionario_id (funcionario_id);
ALTER TABLE cliente
  MODIFY id_cliente int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
ALTER TABLE fornecedor
  MODIFY id_fornecedor int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE funcionario
  MODIFY id_funcionario int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE item_pedido
  MODIFY id_item_pedido int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE movimentacao
  MODIFY id_movimentacao int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE pedidos
  MODIFY id_pedido int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE perfil
  MODIFY id_perfil int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE produto
  MODIFY id_produto int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE usuario
  MODIFY id_usuario int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE item_pedido
  ADD CONSTRAINT item_pedido_ibfk_1 FOREIGN KEY (pedido_id) REFERENCES pedidos (id_pedido),
  ADD CONSTRAINT item_pedido_ibfk_2 FOREIGN KEY (produto_id) REFERENCES produto (id_produto);
ALTER TABLE movimentacao
  ADD CONSTRAINT movimentacao_ibfk_1 FOREIGN KEY (produto_id) REFERENCES produto (id_produto),
  ADD CONSTRAINT movimentacao_ibfk_2 FOREIGN KEY (fornecedor_id) REFERENCES fornecedor (id_fornecedor),
  ADD CONSTRAINT movimentacao_ibfk_3 FOREIGN KEY (funcionario_id) REFERENCES funcionario (id_funcionario),
  ADD CONSTRAINT movimentacao_ibfk_4 FOREIGN KEY (pedido_id) REFERENCES pedidos (id_pedido);
ALTER TABLE pedidos
  ADD CONSTRAINT pedidos_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuario (id_usuario),
  ADD CONSTRAINT pedidos_ibfk_2 FOREIGN KEY (cliente_id) REFERENCES cliente (id_cliente);
ALTER TABLE produto
  ADD CONSTRAINT produto_ibfk_1 FOREIGN KEY (fornecedor_id) REFERENCES fornecedor (id_fornecedor) ON DELETE CASCADE;
ALTER TABLE usuario
  ADD CONSTRAINT usuario_ibfk_1 FOREIGN KEY (perfil_id) REFERENCES perfil (id_perfil),
  ADD CONSTRAINT usuario_ibfk_2 FOREIGN KEY (funcionario_id) REFERENCES funcionario (id_funcionario);
