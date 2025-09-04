CREATE DATABASE atlas_db; USE
    atlas_db;
CREATE TABLE fornecedor(
    id_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
    nome_fornecedor VARCHAR(50) NOT NULL,
    contato_fornecedor VARCHAR(50),
    email_fornecedor VARCHAR(50) NOT NULL UNIQUE,
    cnpj_fornecedor VARCHAR(20) NOT NULL UNIQUE
); CREAtaforma_produto VARCHAR(30) NOT NULL,
    tipo_produto VARCHAR(30) NOT NULL,
    preco_produto DECIMAL(10, 2) NOT NULL,
    qtde_estoque_produto INT NOT NULL,
    imagem_url_produto VARCHAR(255),
    fornecedor_id INT,
    FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id_fornecedor)
); CREATE TABLE funcionario(
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome_funcionario VARCHAR(50) NOT NULL,
    email_funcionario VARCHAR(50) NOT NULL,
    telefone_funcionario VARCHAR(20),
    cpf_funcionario VARCHAR(11) NOT NULL UNIQUE,
    salario_funcionario DECIMAL(10, 2),
    endereco_funcionario VARCHAR(100),
    data_nascimento DATE,
    data_admissao DATE
); CREATE TABLE perfil(
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    nome_perfil VARCHAR(30) NOT NULL UNIQUE
); CREATE TABLE usuario(
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(30) NOT NULL,
    email_usuario VARCHAR(50) NOT NULL UNIQUE,
    senha_usuario VARCHAR(50) NOT NULL,
    perfil_id INT,
    funcionario_id INT,
    FOREIGN KEY(perfil_id) REFERENCES perfil(id_perfil),
    FOREIGN KEY(funcionario_id) REFERENCES funcionario(id_funcionario)
); CREATE TABLE cliente(
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(50) NOT NULL,
    email_cliente VARCHAR(50) NOT NULL UNIQUE,
    telefone_cliente VARCHAR(20),
    cnpj_cliente VARCHAR(20) NOT NULL UNIQUE
); CREATE TABLE estoque(
    id_estoque INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    tipo_estoque ENUM('Entrada', 'Saída') NOT NULL,
    qtde_estoque INT NOT NULL,
    data_entrada DATE,
    data_saida DATE,
    observacao_estoque TEXT,
    usuario_id INT,
    FOREIGN KEY(usuario_id) REFERENCES usuario(id_usuario),
    FOREIGN KEY(produto_id) REFERENCES produto(id_produto)
); CREATE TABLE pedidos(
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    data_pedido DATE NOT NULL,
    status_pedido ENUM(
        'Pendente',
        'Processando',
        'Enviado',
        'Entregue',
        'Cancelado'
    ) NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    usuario_id INT,
    FOREIGN KEY(usuario_id) REFERENCES usuario(id_usuario),
    FOREIGN KEY(cliente_id) REFERENCES cliente(id_cliente)
); CREATE TABLE item_pedido(
    id_item_pedido INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    qtde_item INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY(pedido_id) REFERENCES pedidos(id_pedido),
    FOREIGN KEY(produto_id) REFERENCES produto(id_produto)
); INSERT INTO perfil(nome_perfil)
VALUES('Administrador'),('Vendedor'),('Estoquista');
DELIMITER
    //
CREATE TRIGGER trg_entrada_estoque AFTER INSERT ON
    estoque FOR EACH ROW
BEGIN
    UPDATE
        produto
    SET
        qtde_estoque_produto = qtde_estoque_produto + NEW.qtde_estoque
    WHERE
        id_produto = NEW.produto_id ;
END//
CREATE TRIGGER trg_saida_estoque AFTER INSERT ON
    item_pedido FOR EACH ROW
BEGIN
    UPDATE
        produto
    SET
        qtde_estoque_produto = qtde_estoque_produto - NEW.qtde_item
    WHERE
        id_produto = NEW.produto_id ;
END//
CREATE TRIGGER trg_valida_estoque BEFORE INSERT ON
    item_pedido FOR EACH ROW
BEGIN
    DECLARE
        estoque_atual INT ;
    SELECT
        qtde_estoque_produto
    INTO estoque_atual
FROM
    produto
WHERE
    id_produto = NEW.produto_id ; IF estoque_atual < NEW.qtde_item THEN SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT
    = 'Estoque insuficiente para o produto solicitado.' ;
END IF ;
END//
DELIMITER
    ;
CREATE VIEW vw_estoque_baixo AS SELECT
    id_produto,
    nome_produto,
    qtde_estoque_produto
FROM
    produto
WHERE
    qtde_estoque_produto < 5;
CREATE VIEW vw_produto_item AS SELECT
    p.id_pedido,
    p.data_pedido,
    c.nome_cliente,
    pr.nome_produto,
    i.qtde_item,
    i.preco_unitario
FROM
    pedidos p
JOIN cliente c ON
    p.cliente_id = c.id_cliente
JOIN item_pedido i ON
    p.id_pedido = i.pedido_id
JOIN produto pr ON
    i.produto_id = pr.id_produto;TE TABLE produto(
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(50) NOT NULL,
    descricao_produto TEXT,
    pla