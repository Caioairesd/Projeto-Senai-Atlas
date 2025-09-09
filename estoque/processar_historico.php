<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();
// Inclui arquivo de configuração de conexão com o banco de dados
require_once '../config/conexao.php';

// Obtém ID do usuário e perfil da sessão (convertidos para inteiro por segurança)
$usuarioId = (int) $_SESSION['usuario_id'];
$perfil = (int) $_SESSION['perfil'];

// Captura e sanitiza filtros recebidos via GET
$filtroTipo     = isset($_GET['tipo']) ? trim($_GET['tipo']) : ''; // Filtro por tipo de movimentação
$filtroProduto  = isset($_GET['produto']) ? trim($_GET['produto']) : ''; // Filtro por nome do produto
$filtroDataIni  = isset($_GET['data_ini']) ? trim($_GET['data_ini']) : ''; // Filtro por data inicial
$filtroDataFim  = isset($_GET['data_fim']) ? trim($_GET['data_fim']) : ''; // Filtro por data final

// Monta SQL base para consulta do histórico de movimentações
$sql = "
 SELECT
    m.id_movimentacao, -- ID da movimentação
    p.nome_produto, -- Nome do produto
    m.tipo_movimentacao, -- Tipo (Entrada/Saída)
    m.quantidade, -- Quantidade movimentada
    DATE_FORMAT(m.data_movimentacao, '%d/%m/%Y') AS data_movimentacao, -- Data formatada
    f.nome_funcionario, -- Nome do funcionário
    u.nome_usuario, -- Nome do usuário
    fo.nome_fornecedor, -- Nome do fornecedor
    m.observacao, -- Observação da movimentação
    m.pedido_id -- ID do pedido relacionado
FROM movimentacao m
JOIN produto p ON m.produto_id = p.id_produto -- Junta com tabela de produtos
LEFT JOIN funcionario f ON m.funcionario_id = f.id_funcionario -- Junta com funcionários (opcional)
LEFT JOIN usuario u ON f.id_funcionario = u.funcionario_id -- Junta com usuários (opcional)
LEFT JOIN fornecedor fo ON m.fornecedor_id = fo.id_fornecedor -- Junta com fornecedores (opcional)
WHERE 1=1 -- Condição sempre verdadeira para facilitar concatenação
";

// Se o usuário não for Admin (1) nem Estoquista (2), limita ao próprio histórico
if ($perfil !== 1 && $perfil !== 2) {
    $sql .= " AND u.id_usuario = :usuarioId";
}

// Array para armazenar parâmetros da consulta
$params = [];

// Aplica filtro por tipo de movimentação se fornecido
if ($filtroTipo !== '') {
    $sql .= " AND m.tipo_movimentacao = :tipo";
    $params[':tipo'] = $filtroTipo;
}

// Aplica filtro por nome do produto se fornecido
if ($filtroProduto !== '') {
    $sql .= " AND p.nome_produto LIKE :produto";
    $params[':produto'] = "%{$filtroProduto}%";
}

// Aplica filtros por data
if ($filtroDataIni !== '' && $filtroDataFim !== '') {
    // Filtro por intervalo de datas
    $sql .= " AND m.data_movimentacao BETWEEN :dataIni AND :dataFim";
    $params[':dataIni'] = $filtroDataIni;
    $params[':dataFim'] = $filtroDataFim;
} elseif ($filtroDataIni !== '') {
    // Filtro por data inicial apenas
    $sql .= " AND m.data_movimentacao >= :dataIni";
    $params[':dataIni'] = $filtroDataIni;
} elseif ($filtroDataFim !== '') {
    // Filtro por data final apenas
    $sql .= " AND m.data_movimentacao <= :dataFim";
    $params[':dataFim'] = $filtroDataFim;
}

// Ordena por ID decrescente (mais recentes primeiro)
$sql .= " ORDER BY id_movimentacao desc";

try {
    // Prepara a consulta SQL
    $stmt = $pdo->prepare($sql);

    // Se não for admin/estoquista, vincula o ID do usuário
    if ($perfil !== 1 && $perfil !== 2) {
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
    }

    // Vincula todos os parâmetros da consulta
    foreach ($params as $chave => $valor) {
        $stmt->bindValue($chave, $valor);
    }

    // Executa a consulta
    $stmt->execute();
    // Obtém todos os resultados
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Exibe alerta em caso de erro
    echo "<script>
            alert('Erro ao buscar histórico de movimentações.');
          </script>";
    exit();
}