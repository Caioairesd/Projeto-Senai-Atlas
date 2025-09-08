CREATE DATABASE atlas_db;
USE atlas_db;

CREATE TABLE fornecedor(
    id_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
    nome_fornecedor VARCHAR(50) NOT NULL,
    contato_fornecedor VARCHAR(50),
    email_fornecedor VARCHAR(50) NOT NULL UNIQUE,
    cnpj_fornecedor VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE produto(
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(50) NOT NULL,
    descricao_produto TEXT,
    plataforma_produto VARCHAR(30) NOT NULL,
    tipo_produto VARCHAR(30) NOT NULL,
    preco_produto DECIMAL(10, 2) NOT NULL,
    qtde_estoque_produto INT NOT NULL,
    imagem_url_produto LONGBLOB, -- Alterado para armazenar imagem no banco
    fornecedor_id INT,
    FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id_fornecedor) ON DELETE CASCADE
);

CREATE TABLE funcionario(
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome_funcionario VARCHAR(50) NOT NULL,
    email_funcionario VARCHAR(50) NOT NULL,
    telefone_funcionario VARCHAR(20),
    cpf_funcionario VARCHAR(11) NOT NULL UNIQUE,
    salario_funcionario DECIMAL(10, 2),
    endereco_funcionario VARCHAR(100),
    data_nascimento DATE,
    data_admissao DATE,
    imagem_url_funcionario LONGBLOB -- Campo para armazenar foto do funcionário
);

CREATE TABLE perfil(
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    nome_perfil VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE usuario(
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(30) NOT NULL,
    email_usuario VARCHAR(50) NOT NULL UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL, -- Aumentado para suportar password_hash()
    token_recuperacao VARCHAR(255) DEFAULT NULL, -- Para recuperação de senha
    token_expira DATETIME DEFAULT NULL,          -- Validade do token
    perfil_id INT,
    funcionario_id INT,
    FOREIGN KEY(perfil_id) REFERENCES perfil(id_perfil),
    FOREIGN KEY(funcionario_id) REFERENCES funcionario(id_funcionario)
);

CREATE TABLE cliente(
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(50) NOT NULL,
    email_cliente VARCHAR(50) NOT NULL UNIQUE,
    telefone_cliente VARCHAR(20),
    cnpj_cliente VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE movimentacao (
    id_movimentacao INT AUTO_INCREMENT PRIMARY KEY,
    tipo_movimentacao ENUM('Entrada', 'Saída') NOT NULL,
    quantidade INT NOT NULL,
    data_movimentacao DATE NOT NULL,
    produto_id INT NOT NULL,
    fornecedor_id INT,
    funcionario_id INT,
    pedido_id INT,
    observacao TEXT,
    FOREIGN KEY (produto_id) REFERENCES produto(id_produto),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedor(id_fornecedor),
    FOREIGN KEY (funcionario_id) REFERENCES funcionario(id_funcionario),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id_pedido)
);

CREATE TABLE pedidos(
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    data_pedido DATE NOT NULL,
    status_pedido ENUM('Pendente','Processando','Enviado','Entregue','Cancelado') NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    usuario_id INT,
    FOREIGN KEY(usuario_id) REFERENCES usuario(id_usuario),
    FOREIGN KEY(cliente_id) REFERENCES cliente(id_cliente)
);

CREATE TABLE item_pedido(
    id_item_pedido INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    qtde_item INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY(pedido_id) REFERENCES pedidos(id_pedido),
    FOREIGN KEY(produto_id) REFERENCES produto(id_produto)
);

INSERT INTO perfil(nome_perfil)
VALUES ('Administrador'), ('Vendedor'), ('Estoquista');

DELIMITER //

CREATE TRIGGER trg_entrada_movimentacao
AFTER INSERT ON movimentacao
FOR EACH ROW
BEGIN
    IF NEW.tipo_movimentacao = 'Entrada' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto + NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END//
 
CREATE TRIGGER trg_saida_movimentacao
AFTER INSERT ON movimentacao
FOR EACH ROW
BEGIN
    IF NEW.tipo_movimentacao = 'Saída' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto - NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END//

CREATE TRIGGER trg_valida_movimentacao
BEFORE INSERT ON movimentacao
FOR EACH ROW
BEGIN
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
END//
DELIMITER ;

CREATE VIEW vw_estoque_baixo AS
SELECT id_produto, nome_produto, qtde_estoque_produto
FROM produto
WHERE qtde_estoque_produto < 5;

CREATE VIEW vw_produto_item AS
SELECT p.id_pedido, p.data_pedido, c.nome_cliente, pr.nome_produto, i.qtde_item, i.preco_unitario
FROM pedidos p
JOIN cliente c ON p.cliente_id = c.id_cliente
JOIN item_pedido i ON p.id_pedido = i.pedido_id
JOIN produto pr ON i.produto_id = pr.id_produto;

USE atlas_db;

DROP TRIGGER trg_entrada_estoque;
DROP TRIGGER trg_saida_estoque;
DROP TRIGGER trg_valida_estoque;

DROP TABLE estoque;

CREATE TABLE movimentacao (
    id_movimentacao INT AUTO_INCREMENT PRIMARY KEY,
    tipo_movimentacao ENUM('Entrada', 'Saída') NOT NULL,
    quantidade INT NOT NULL,
    data_movimentacao DATE NOT NULL,
    produto_id INT NOT NULL,
    fornecedor_id INT,
    funcionario_id INT,
    pedido_id INT,
    observacao TEXT,
    FOREIGN KEY (produto_id) REFERENCES produto(id_produto),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedor(id_fornecedor),
    FOREIGN KEY (funcionario_id) REFERENCES funcionario(id_funcionario),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id_pedido)
) ENGINE=InnoDB;

DELIMITER //

CREATE TRIGGER trg_entrada_movimentacao
AFTER INSERT ON movimentacao
FOR EACH ROW
BEGIN
    IF NEW.tipo_movimentacao = 'Entrada' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto + NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END//
 
CREATE TRIGGER trg_saida_movimentacao
AFTER INSERT ON movimentacao
FOR EACH ROW
BEGIN
    IF NEW.tipo_movimentacao = 'Saída' THEN
        UPDATE produto
        SET qtde_estoque_produto = qtde_estoque_produto - NEW.quantidade
        WHERE id_produto = NEW.produto_id;
    END IF;
END//

CREATE TRIGGER trg_valida_movimentacao
BEFORE INSERT ON movimentacao
FOR EACH ROW
BEGIN
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
END//
DELIMITER ;
