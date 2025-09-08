<?php
session_start();
require_once '../config/conexao.php';

$usuarioId = (int) $_SESSION['usuario_id'];
$perfil = (int) $_SESSION['perfil'];

// Captura filtros vindos por GET
$filtroTipo     = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
$filtroProduto  = isset($_GET['produto']) ? trim($_GET['produto']) : '';
$filtroDataIni  = isset($_GET['data_ini']) ? trim($_GET['data_ini']) : '';
$filtroDataFim  = isset($_GET['data_fim']) ? trim($_GET['data_fim']) : '';

// Monta SQL base
$sql = "
 SELECT
    m.id_movimentacao,
    p.nome_produto,
    m.tipo_movimentacao,
    m.quantidade,
    DATE_FORMAT(m.data_movimentacao, '%d/%m/%Y') AS data_movimentacao,
    f.nome_funcionario,
    u.nome_usuario,
    fo.nome_fornecedor,
    m.observacao,
    m.pedido_id
FROM movimentacao m
JOIN produto p ON m.produto_id = p.id_produto
LEFT JOIN funcionario f ON m.funcionario_id = f.id_funcionario
LEFT JOIN usuario u ON f.id_funcionario = u.funcionario_id
LEFT JOIN fornecedor fo ON m.fornecedor_id = fo.id_fornecedor
WHERE 1=1
";

// Se o usuário não for Admin (1) nem Estoquista (2), limita ao próprio histórico
if ($perfil !== 1 && $perfil !== 2) {
    $sql .= " AND u.id_usuario = :usuarioId";
}

// Aplica filtros opcionais
$params = [];

if ($filtroTipo !== '') {
    $sql .= " AND m.tipo_movimentacao = :tipo";
    $params[':tipo'] = $filtroTipo;
}

if ($filtroProduto !== '') {
    $sql .= " AND p.nome_produto LIKE :produto";
    $params[':produto'] = "%{$filtroProduto}%";
}

if ($filtroDataIni !== '' && $filtroDataFim !== '') {
    $sql .= " AND m.data_movimentacao BETWEEN :dataIni AND :dataFim";
    $params[':dataIni'] = $filtroDataIni;
    $params[':dataFim'] = $filtroDataFim;
} elseif ($filtroDataIni !== '') {
    $sql .= " AND m.data_movimentacao >= :dataIni";
    $params[':dataIni'] = $filtroDataIni;
} elseif ($filtroDataFim !== '') {
    $sql .= " AND m.data_movimentacao <= :dataFim";
    $params[':dataFim'] = $filtroDataFim;
}

// Ordena mais recentes primeiro
$sql .= " ORDER BY id_movimentacao desc";

try {
    $stmt = $pdo->prepare($sql);

    if ($perfil !== 1 && $perfil !== 2) {
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
    }

    foreach ($params as $chave => $valor) {
        $stmt->bindValue($chave, $valor);
    }

    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>
            alert('Erro ao buscar histórico de movimentações.');
          </script>";
    exit();
}