-- Consultas SQL para substituir os dados estáticos do dashboard
-- Baseadas no banco de dados atlas_db existente

-- ========================================
-- 1. ESTATÍSTICAS GERAIS (Cards do topo)
-- ========================================

-- Adaptado para usar a tabela produto real
-- Total de jogos no estoque
SELECT COUNT(*) as total_jogos 
FROM produto;

-- Adaptado para usar tabelas pedidos e item_pedido reais
-- Vendas do mês atual
SELECT COALESCE(SUM(p.valor_total), 0) as vendas_mes 
FROM pedidos p
WHERE MONTH(p.data_pedido) = MONTH(CURDATE())
AND YEAR(p.data_pedido) = YEAR(CURDATE())
AND p.status_pedido IN ('Entregue', 'Enviado');

-- Usando a view vw_estoque_baixo já existente
-- Produtos com estoque baixo (usando a view existente)
SELECT COUNT(*) as estoque_baixo 
FROM vw_estoque_baixo;

-- Adaptado para usar a tabela cliente real
-- Clientes ativos
SELECT COUNT(*) as clientes_ativos 
FROM cliente;

-- ========================================
-- 2. GRÁFICO DE VENDAS (Últimos 7 dias)
-- ========================================
-- Adaptado para MySQL e tabelas reais
SELECT 
    DATE(p.data_pedido) as data,
    COALESCE(SUM(p.valor_total), 0) as total_vendas,
    DATE_FORMAT(p.data_pedido, '%d/%m') as data_formatada
FROM pedidos p
WHERE p.data_pedido >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
AND p.status_pedido IN ('Entregue', 'Enviado')
GROUP BY DATE(p.data_pedido)
ORDER BY data;

-- ========================================
-- 3. JOGOS MAIS VENDIDOS (Top 5)
-- ========================================
-- Usando as tabelas produto, item_pedido e pedidos reais
SELECT 
    pr.nome_produto,
    pr.preco_produto,
    SUM(ip.qtde_item) as total_vendido,
    SUM(ip.qtde_item * ip.preco_unitario) as receita_total
FROM produto pr
JOIN item_pedido ip ON pr.id_produto = ip.produto_id
JOIN pedidos p ON ip.pedido_id = p.id_pedido
WHERE p.data_pedido >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
AND p.status_pedido IN ('Entregue', 'Enviado')
GROUP BY pr.id_produto, pr.nome_produto, pr.preco_produto
ORDER BY total_vendido DESC
LIMIT 5;

-- ========================================
-- 4. ATIVIDADES RECENTES (Últimas 10)
-- ========================================
-- Criando atividades baseadas nos pedidos e movimentações de estoque
-- Atividades recentes baseadas em pedidos
SELECT 
    'Pedido' as tipo,
    CONCAT('Pedido #', p.id_pedido, ' - ', c.nome_cliente) as descricao,
    f.nome_funcionario as funcionario,
    p.data_pedido as created_at,
    DATE_FORMAT(p.data_pedido, '%d/%m %H:%i') as data_formatada
FROM pedidos p
JOIN cliente c ON p.cliente_id = c.id_cliente
LEFT JOIN usuario u ON p.usuario_id = u.id_usuario
LEFT JOIN funcionario f ON u.funcionario_id = f.id_funcionario
ORDER BY p.data_pedido DESC
LIMIT 10;

-- ========================================
-- 5. ALERTAS DE ESTOQUE BAIXO
-- ========================================
-- Usando a view vw_estoque_baixo existente com mais detalhes
SELECT 
    veb.nome_produto,
    veb.qtde_estoque_produto as estoque_atual,
    5 as estoque_minimo,
    (5 - veb.qtde_estoque_produto) as deficit,
    p.preco_produto
FROM vw_estoque_baixo veb
JOIN produto p ON veb.id_produto = p.id_produto
ORDER BY deficit DESC;

-- ========================================
-- 6. MOVIMENTAÇÕES DE ESTOQUE RECENTES
-- ========================================
-- Nova query para mostrar movimentações de estoque
SELECT 
    e.tipo_estoque,
    p.nome_produto,
    e.qtde_estoque,
    COALESCE(e.data_entrada, e.data_saida) as data_movimentacao,
    f.nome_funcionario,
    e.observacao_estoque
FROM estoque e
JOIN produto p ON e.produto_id = p.id_produto
LEFT JOIN usuario u ON e.usuario_id = u.id_usuario
LEFT JOIN funcionario f ON u.funcionario_id = f.id_funcionario
ORDER BY COALESCE(e.data_entrada, e.data_saida) DESC
LIMIT 10;

-- ========================================
-- 7. RESUMO MENSAL (Para relatórios)
-- ========================================
-- Adaptado para as tabelas reais do sistema
SELECT 
    COUNT(DISTINCT p.id_pedido) as total_pedidos,
    SUM(p.valor_total) as receita_total,
    AVG(p.valor_total) as ticket_medio,
    COUNT(DISTINCT p.cliente_id) as clientes_unicos
FROM pedidos p
WHERE MONTH(p.data_pedido) = MONTH(CURDATE())
AND YEAR(p.data_pedido) = YEAR(CURDATE())
AND p.status_pedido IN ('Entregue', 'Enviado');

-- ========================================
-- 8. PRODUTOS POR PLATAFORMA
-- ========================================
-- Nova query específica para jogos por plataforma
SELECT 
    plataforma_produto,
    COUNT(*) as total_produtos,
    SUM(qtde_estoque_produto) as total_estoque,
    AVG(preco_produto) as preco_medio
FROM produto
GROUP BY plataforma_produto
ORDER BY total_produtos DESC;
