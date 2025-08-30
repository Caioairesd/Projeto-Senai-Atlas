CREATE DATABASE atlas_db;

USE atlas_db;

CREATE TABLE fornecedor(
    id_fornecedor INT AUTO INCREMENT PRIMARY KEY,
    nome_fornecedor VARCHAR(50) NOT NULL,
    contato_fornecedor VARCHAR(50),
    email_fornecedor VARCHAR(50) NOT NULL UNIQUE,
    cnpj_fornecedor VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE produto(
    id_produto INT AUTO INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(50) NOT NULL,
    descricao_produto TEXT,
    plataforma_produto VARCHAR(30) NOT NULL,
    tipo_produto VARCHAR(30) NOT NULL,
    preco_produto DECIMAL(10, 2) NOT NULL,
    qtde_estoque_produto INT NOT NULL,
    imagem_url_produto VARCHAR(255),
    fornecedor_id INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedor(id_fornecedor)
);

CREATE TABLE perfil(
    id_perfil INT AUTO INCREMENT PRIMARY KEY,
    nome_perfil VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE usuario(
    id_usuario INT AUTO INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(30) NOT NULL,
    email_usuario VARCHAR(50) NOT NULL UNIQUE,
    senha_usuario VARCHAR(50) NOT NULL,
    perfil_id INT,
    FOREIGN KEY (perfil_id) REFERENCES perfil(id_perfil)
);

CREATE TABLE cliente(
    id_cliente INT AUTO INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(50) NOT NULL,
    email_cliente VARCHAR(50) NOT NULL UNIQUE,
    telefone_cliente VARCHAR(20),
    email_cliente VARCHAR(50),
    cnpj_fornecedor VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE estoque(
    id_estoque INT AUTO INCREMENT PRIMARY KEY,
    produto_id INT,
    tipo_estoque ENUM('Entrada','Saída') NOT NULL,
    qtde_estoque INT NOT NULL,
    data_entrada DATE,
    data_saida DATE,
    observacao_estoque TEXT,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario),
    FOREIGN KEY (produto_id) REFERENCES produto(id_produto)
);

CREATE TABLE pedidos(
    id_pedido INT AUTO INCREMENT PRIMARY KEY,
    cliente_id INT,
    data_pedido DATE NOT NULL,
    status_pedido ENUM('Pendente','Processando','Enviado','Entregue','Cancelado') NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario),
    FOREIGN KEY (cliente_id) REFERENCES cliente(id_cliente)
);

CREATE TABLE item_pedido(
    id_item_pedido INT AUTO INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    qtde_item INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (produto_id) REFERENCES produto(id_produto)
);