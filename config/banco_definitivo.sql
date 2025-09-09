-- Tabela de clientes
CREATE TABLE cliente (
  id_cliente int(11) NOT NULL, -- ID único do cliente
  nome_cliente varchar(50) NOT NULL, -- Nome do cliente
  email_cliente varchar(50) NOT NULL, -- Email do cliente (único)
  telefone_cliente varchar(20) DEFAULT NULL, -- Telefone do cliente
  cnpj_cliente varchar(20) NOT NULL, -- CNPJ do cliente (único)
  ativo tinyint(1) DEFAULT 1 -- Status do cliente (1 = ativo, 0 = inativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela cliente
INSERT INTO cliente (id_cliente, nome_cliente, email_cliente, telefone_cliente, cnpj_cliente, ativo) VALUES
(2, 'cliente_teste', 'CLIENTETESTE@CLIENTETESTE', '(12) 38383-8388', '84.484.884/8484-44', 0), -- Cliente inativo
(3, 'Pichau', 'compras@pichau.com.br', '(47) 98506-7733', '34.344.444/4443-33', 1), -- Cliente ativo
(4, 'akdkkd', 'akdak@aksksk', '(89) 98883-8383', '83.838.494/9219-42', 1), -- Cliente ativo
(5, 'Grupo Nova Era', 'contato@novaera.com.br', '(11) 98765-4321', '12.345.678/0001-90', 1), -- Cliente ativo
(6, 'Distribuidora Sul Brasil', 'vendas@sulbrasil.com', '(51) 99876-5432', '98.765.432/0001-10', 1), -- Cliente ativo
(7, 'TechMax Solutions', 'suporte@techmax.com.br', '(21) 97654-3210', '45.678.901/0001-22', 1), -- Cliente ativo
(8, 'Comercial Andrade', 'andrade@comercial.com', '(31) 96543-2109', '67.890.123/0001-33', 0), -- Cliente inativo
(9, 'Alimentos Vitória', 'compras@vitoriafoods.com.br', '(41) 95432-1098', '23.456.789/0001-44', 1), -- Cliente ativo
(10, 'Construtora Vale Norte', 'financeiro@valenorte.com', '(61) 94321-0987', '34.567.890/0001-55', 1), -- Cliente ativo
(11, 'Auto Peças Joinville', 'joinville@autoparts.com.br', '(47) 93210-9876', '56.789.012/0001-66', 1), -- Cliente ativo
(12, 'Farmácia Popular', 'contato@farmaciapopular.com', '(85) 92109-8765', '78.901.234/0001-77', 1), -- Cliente ativo
(13, 'Loja do Mecânico', 'mecanico@loja.com.br', '(95) 91098-7654', '89.012.345/0001-88', 1), -- Cliente ativo
(14, 'Serviços Express', 'express@servicos.com.br', '(71) 90987-6543', '90.123.456/0001-99', 1); -- Cliente ativo

-- Tabela de fornecedores
CREATE TABLE fornecedor (
  id_fornecedor int(11) NOT NULL, -- ID único do fornecedor
  nome_fornecedor varchar(50) NOT NULL, -- Nome do fornecedor
  contato_fornecedor varchar(50) DEFAULT NULL, -- Contato do fornecedor
  email_fornecedor varchar(50) NOT NULL, -- Email do fornecedor (único)
  cnpj_fornecedor varchar(20) NOT NULL, -- CNPJ do fornecedor (único)
  ativo tinyint(1) DEFAULT 1 -- Status do fornecedor (1 = ativo, 0 = inativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela fornecedor
INSERT INTO fornecedor (id_fornecedor, nome_fornecedor, contato_fornecedor, email_fornecedor, cnpj_fornecedor, ativo) VALUES
(2, 'fornecedortestaaa', '(77) 77777-7777', 'fornecedorteste@fornecedorteste', '23.333.333/3333-33', 0), -- Fornecedor inativo
(3, 'Kabum', '(47) 98506-7733', 'compras@pichau.com.br', '34.344.444/4443-33', 1), -- Fornecedor ativo
(4, 'Distribuidora Alfa', '(11) 98888-1234', 'contato@alfa.com.br', '12.345.678/0001-90', 1), -- Fornecedor ativo
(5, 'TechParts Brasil', '(21) 97777-4321', 'suporte@techparts.com', '98.765.432/0001-10', 1), -- Fornecedor ativo
(6, 'Comercial Vitória', '(31) 96666-5678', 'vendas@comercialvitoria.com.br', '45.678.901/0001-22', 1), -- Fornecedor ativo
(7, 'Indústria Nova Era', '(41) 95555-8765', 'financeiro@novaera.com', '67.890.123/0001-33', 0), -- Fornecedor inativo
(8, 'Auto Peças Joinville', '(47) 94444-3456', 'joinville@autoparts.com.br', '56.789.012/0001-66', 1), -- Fornecedor ativo
(9, 'Farmácia Popular', '(85) 93333-6543', 'contato@farmaciapopular.com', '78.901.234/0001-77', 1), -- Fornecedor ativo
(10, 'Construtora Vale Norte', '(61) 92222-9876', 'valenorte@construtora.com.br', '34.567.890/0001-55', 1), -- Fornecedor ativo
(11, 'Serviços Express', '(71) 91111-7890', 'express@servicos.com.br', '90.123.456/0001-99', 0), -- Fornecedor inativo
(12, 'Distribuidora Sul Brasil', '(51) 90000-1122', 'vendas@sulbrasil.com', '23.456.789/0001-44', 1), -- Fornecedor ativo
(13, 'Grupo Maxx', '(31) 98888-3344', 'grupo@maxx.com.br', '89.012.345/0001-88', 1); -- Fornecedor ativo

-- Tabela de funcionários
CREATE TABLE funcionario (
  id_funcionario int(11) NOT NULL, -- ID único do funcionário
  nome_funcionario varchar(50) NOT NULL, -- Nome do funcionário
  email_funcionario varchar(50) NOT NULL, -- Email do funcionário
  telefone_funcionario varchar(20) DEFAULT NULL, -- Telefone do funcionário
  cpf_funcionario varchar(11) NOT NULL, -- CPF do funcionário (único)
  salario_funcionario decimal(10,2) DEFAULT NULL, -- Salário do funcionário
  endereco_funcionario varchar(100) DEFAULT NULL, -- Endereço do funcionário
  data_nascimento date DEFAULT NULL, -- Data de nascimento do funcionário
  data_admissao date DEFAULT NULL, -- Data de admissão do funcionário
  imagem_url_funcionario longblob DEFAULT NULL, -- Imagem do funcionário (BLOB)
  ativo tinyint(1) DEFAULT 1 -- Status do funcionário (1 = ativo, 0 = inativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela funcionario
INSERT INTO funcionario (id_funcionario, nome_funcionario, email_funcionario, telefone_funcionario, cpf_funcionario, salario_funcionario, endereco_funcionario, data_nascimento, data_admissao, imagem_url_funcionario, ativo) 
VALUES (3, 'CAIO VINICIUS AIRES DA SILVA', 'caiovns30@gmail.com', '(47) 99239-4209', '222.213.333', 3333.33, 'rua um', '2025-09-01', '2025-09-10','', 1), -- Funcionário ativo
(4, 'bruno', 'bruno@atlas.com', '(99) 88338-8383', '383.899.212', 8.88, 'rua', '2025-09-01', '2025-09-04', '', 1); -- Funcionário ativo

-- Tabela de itens de pedido
CREATE TABLE item_pedido (
  id_item_pedido int(11) NOT NULL, -- ID único do item do pedido
  pedido_id int(11) DEFAULT NULL, -- ID do pedido (chave estrangeira)
  produto_id int(11) DEFAULT NULL, -- ID do produto (chave estrangeira)
  qtde_item int(11) NOT NULL, -- Quantidade do item
  preco_unitario decimal(10,2) NOT NULL -- Preço unitário do item
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela item_pedido
INSERT INTO item_pedido (id_item_pedido, pedido_id, produto_id, qtde_item, preco_unitario) VALUES
(2, 6, 2, 1, 11.00), -- Item do pedido 6
(3, 7, 2, 9, 11.00), -- Item do pedido 7
(4, 8, 2, 8, 11.00), -- Item do pedido 8
(6, 10, 2, 1, 11.00), -- Item do pedido 10
(7, 11, 2, 1, 11.00), -- Item do pedido 11
(8, 12, 2, 11, 11.00); -- Item do pedido 12

-- Tabela de movimentações de estoque
CREATE TABLE movimentacao (
  id_movimentacao int(11) NOT NULL, -- ID único da movimentação
  tipo_movimentacao enum('Entrada','Saída') NOT NULL, -- Tipo de movimentação
  quantidade int(11) NOT NULL, -- Quantidade movimentada
  data_movimentacao date NOT NULL, -- Data da movimentação
  produto_id int(11) NOT NULL, -- ID do produto (chave estrangeira)
  fornecedor_id int(11) DEFAULT NULL, -- ID do fornecedor (chave estrangeira)
  funcionario_id int(11) DEFAULT NULL, -- ID do funcionário (chave estrangeira)
  pedido_id int(11) DEFAULT NULL, -- ID do pedido (chave estrangeira)
  observacao text DEFAULT NULL -- Observações da movimentação
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela movimentacao
INSERT INTO movimentacao (id_movimentacao, tipo_movimentacao, quantidade, data_movimentacao, produto_id, fornecedor_id, funcionario_id, pedido_id, observacao) VALUES
(1, 'Entrada', 10, '2025-09-07', 2, NULL, NULL, NULL, ''), -- Entrada de estoque
(2, 'Saída', 1, '2025-09-07', 2, NULL, NULL, NULL, ''), -- Saída de estoque
(3, 'Saída', 1, '2025-09-06', 2, NULL, NULL, 6, 'Saída automática do pedido #6'), -- Saída por pedido
(4, 'Entrada', 1, '2025-09-06', 2, NULL, NULL, 6, 'Estorno do pedido cancelado #6'), -- Estorno de pedido
(5, 'Saída', 9, '2025-09-07', 2, NULL, NULL, NULL, ''), -- Saída de estoque
(6, 'Entrada', 9, '2025-09-07', 2, NULL, NULL, NULL, ''), -- Entrada de estoque
(7, 'Saída', 8, '2025-09-07', 2, NULL, NULL, NULL, ''), -- Saída de estoque
(9, 'Saída', 1, '2025-09-08', 2, NULL, 3, NULL, ''), -- Saída de estoque
(10, 'Entrada', 122, '2025-09-08', 2, 2, 3, NULL, ''), -- Entrada de estoque
(11, 'Entrada', 1, '2025-09-08', 2, 2, 3, NULL, ''), -- Entrada de estoque
(12, 'Saída', 1, '2025-09-08', 2, NULL, 3, NULL, ''), -- Saída de estoque
(13, 'Saída', 1, '2025-09-08', 2, NULL, NULL, 11, 'Saída automática do pedido #11'), -- Saída por pedido
(14, 'Saída', 11, '2025-09-08', 2, NULL, 3, NULL, ''), -- Saída de estoque
(15, 'Saída', 11, '2025-09-08', 2, NULL, NULL, 12, 'Saída automática do pedido #12'); -- Saída por pedido

-- Trigger para atualizar estoque em entradas
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

-- Trigger para atualizar estoque em saídas
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

-- Trigger para validar estoque antes de saída
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

-- Tabela de pedidos
CREATE TABLE pedidos (
  id_pedido int(11) NOT NULL, -- ID único do pedido
  cliente_id int(11) DEFAULT NULL, -- ID do cliente (chave estrangeira)
  data_pedido date NOT NULL, -- Data do pedido
  status_pedido enum('Pendente','Processando','Enviado','Entregue','Cancelado') NOT NULL, -- Status do pedido
  valor_total decimal(10,2) NOT NULL, -- Valor total do pedido
  usuario_id int(11) DEFAULT NULL -- ID do usuário que fez o pedido (chave estrangeira)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela pedidos
INSERT INTO pedidos (id_pedido, cliente_id, data_pedido, status_pedido, valor_total, usuario_id) VALUES
(6, 2, '2025-09-07', 'Entregue', 0.00, NULL), -- Pedido entregue
(7, 2, '2025-09-07', 'Pendente', 0.00, NULL), -- Pedido pendente
(8, 2, '2025-09-07', 'Pendente', 0.00, NULL), -- Pedido pendente
(10, 2, '2025-09-08', 'Pendente', 0.00, NULL), -- Pedido pendente
(11, 2, '2025-09-08', 'Processando', 0.00, NULL), -- Pedido em processamento
(12, 3, '2025-09-08', 'Processando', 0.00, NULL); -- Pedido em processamento

-- Trigger para estornar pedidos cancelados
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

-- Trigger para baixar estoque em pedidos processados
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

-- Tabela de perfis de usuário
CREATE TABLE perfil (
  id_perfil int(11) NOT NULL, -- ID único do perfil
  nome_perfil varchar(30) NOT NULL -- Nome do perfil (único)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela perfil
INSERT INTO perfil (id_perfil, nome_perfil) VALUES
(1, 'Administrador'), -- Perfil de administrador
(3, 'Estoquista'), -- Perfil de estoquista
(2, 'Vendedor'); -- Perfil de vendedor

-- Tabela de produtos
CREATE TABLE produto (
  id_produto int(11) NOT NULL, -- ID único do produto
  nome_produto varchar(50) NOT NULL, -- Nome do produto
  descricao_produto text DEFAULT NULL, -- Descrição do produto
  plataforma_produto varchar(30) NOT NULL, -- Plataforma do produto
  tipo_produto varchar(30) NOT NULL, -- Tipo do produto
  preco_produto decimal(10,2) NOT NULL, -- Preço do produto
  qtde_estoque_produto int(11) NOT NULL, -- Quantidade em estoque
  imagem_url_produto longblob DEFAULT NULL, -- Imagem do produto (BLOB)
  fornecedor_id int(11) DEFAULT NULL, -- ID do fornecedor (chave estrangeira)
  ativo tinyint(1) DEFAULT 1 -- Status do produto (1 = ativo, 0 = inativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela produto
INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(2, 'Grand Thief Auto', 'aaaa', 'aaa', 'aaa', 11.00, 99, '', 2, 0); -- Produto inativo

INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(3, 'God of War Ragnarok', 'Kratos e Atreus enfrentam deuses nórdicos em uma jornada épica e emocional', 'PlayStation 5', 'Aventura', 299.99, 0, '', 6, 1); -- Produto ativo

INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(4, 'Elden Ring', 'Um mundo sombrio e vasto criado por Hidetaka Miyazaki e George R R Martin', 'Xbox Series X/S', 'RPG', 259.00, 0, '', 7, 1); -- Produto ativo

INSERT INTO produto (id_produto, nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, qtde_estoque_produto, imagem_url_produto, fornecedor_id, ativo) VALUES
(5, 'Grand Theft Auto V', 'Três protagonistas uma cidade viva e missões insanas em Los Santos', 'PlayStation 4', 'Sandbox', 149.90, 0, '', 4, 1); -- Produto ativo

-- Tabela de usuários
CREATE TABLE usuario (
  id_usuario int(11) NOT NULL, -- ID único do usuário
  nome_usuario varchar(30) NOT NULL, -- Nome do usuário
  email_usuario varchar(50) NOT NULL, -- Email do usuário (único)
  senha_usuario varchar(255) NOT NULL, -- Senha do usuário (hash)
  token_recuperacao varchar(255) DEFAULT NULL, -- Token para recuperação de senha
  token_expira datetime DEFAULT NULL, -- Data de expiração do token
  perfil_id int(11) DEFAULT NULL, -- ID do perfil (chave estrangeira)
  funcionario_id int(11) DEFAULT NULL -- ID do funcionário (chave estrangeira)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserção de dados na tabela usuario
INSERT INTO usuario (id_usuario, nome_usuario, email_usuario, senha_usuario, token_recuperacao, token_expira, perfil_id, funcionario_id) VALUES
(4, 'bruno', 'bruno@atlas.com', '$2y$10$vOZKJ3YiEEGG1rhe8QATQOC8M4I./OqKxp60bywegvJcmxI3rDmai', NULL, NULL, 1, 4); -- Usuário administrador

-- Views do sistema

-- View para produtos com estoque baixo (<5 unidades)
CREATE TABLE vw_estoque_baixo (
id_produto int(11),
nome_produto varchar(50),
qtde_estoque_produto int(11)
);

-- View para estoque geral com status
CREATE TABLE vw_estoque_geral (
id_produto int(11)
,nome_produto varchar(50)
,plataforma_produto varchar(30)
,tipo_produto varchar(30)
,preco_produto decimal(10,2)
,qtde_estoque_produto int(11)
,status_estoque varchar(5)
);

-- View para resumo de pedidos
CREATE TABLE vw_pedidos_resumo (
id_pedido int(11)
,nome_cliente varchar(50)
,data_pedido date
,status_pedido enum('Pendente','Processando','Enviado','Entregue','Cancelado')
,valor_total decimal(42,2)
,total_itens bigint(21)
);

-- View para detalhes de produtos em pedidos
CREATE TABLE vw_produto_item (
id_pedido int(11)
,data_pedido date
,nome_cliente varchar(50)
,nome_produto varchar(50)
,qtde_item int(11)
,preco_unitario decimal(10,2)
);

-- Criação da view vw_estoque_baixo
DROP TABLE IF EXISTS vw_estoque_baixo;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_estoque_baixo  AS SELECT produto.id_produto AS id_produto, produto.nome_produto AS nome_produto, produto.qtde_estoque_produto AS qtde_estoque_produto FROM produto WHERE produto.qtde_estoque_produto < 5 ;

-- Criação da view vw_estoque_geral
DROP TABLE IF EXISTS vw_estoque_geral;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_estoque_geral  AS SELECT p.id_produto AS id_produto, p.nome_produto AS nome_produto, p.plataforma_produto AS plataforma_produto, p.tipo_produto AS tipo_produto, p.preco_produto AS preco_produto, p.qtde_estoque_produto AS qtde_estoque_produto, CASE WHEN p.qtde_estoque_produto < 5 THEN 'Baixo' ELSE 'OK' END AS status_estoque FROM produto AS p ;

-- Criação da view vw_pedidos_resumo
DROP TABLE IF EXISTS vw_pedidos_resumo;
CREATE ALGORITHM=UNDefINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_pedidos_resumo  AS SELECT p.id_pedido AS id_pedido, c.nome_cliente AS nome_cliente, p.data_pedido AS data_pedido, p.status_pedido AS status_pedido, sum(ip.qtde_item * ip.preco_unitario) AS valor_total, count(ip.id_item_pedido) AS total_itens FROM ((pedidos p join cliente c on(c.id_cliente = p.cliente_id)) join item_pedido ip on(ip.pedido_id = p.id_pedido)) GROUP BY p.id_pedido, c.nome_cliente, p.data_pedido, p.status_pedido ;

-- Criação da view vw_produto_item
DROP TABLE IF EXISTS vw_produto_item;
CREATE ALGORITHM=UNDEFINED DEFINER=root@localhost SQL SECURITY DEFINER VIEW vw_produto_item  AS SELECT p.id_pedido AS id_pedido, p.data_pedido AS data_pedido, c.nome_cliente AS nome_cliente, pr.nome_produto AS nome_produto, i.qtde_item AS qtde_item, i.preco_unitario AS preco_unitario FROM (((pedidos p join cliente c on(p.cliente_id = c.id_cliente)) join item_pedido i on(p.id_pedido = i.pedido_id)) join produto pr on(i.produto_id = pr.id_produto)) ;

-- Definição de chaves primárias e índices

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

-- Auto incremento das tabelas

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

-- Definição de chaves estrangeiras

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