<?php
session_start();
require_once '../config/conexao.php';

$usuarioId = (int) $_SESSION['usuario_id'];
$perfil = (int) $_SESSION['perfil'];

// Monta SQL base
$sql = "
    SELECT
        e.id_estoque,
        p.nome_produto,
        e.tipo_estoque,
        e.qtde_estoque,
        COALESCE(e.data_entrada, e.data_saida) AS data_movimentacao,
        f.nome_funcionario,
        u.nome_usuario,
        e.observacao_estoque,
        pe.id_pedido
    FROM estoque e
    JOIN produto p    ON e.produto_id     = p.id_produto
    JOIN usuario u    ON e.usuario_id     = u.id_usuario
    JOIN funcionario f ON u.funcionario_id = f.id_funcionario
    LEFT JOIN pedidos pe 
      ON pe.usuario_id   = u.id_usuario
     AND e.tipo_estoque  = 'Saída'
";

//Se o usuário não for Admin (1) nem Estoquista (2), limita ao próprio histórico
if ($perfil !== 1 && $perfil !== 2) {
    $sql .= " WHERE e.usuario_id = :usuarioId";
}

//Ordena mais recentes primeiro
$sql .= " ORDER BY data_movimentacao DESC";

try {
    $stmt = $pdo->prepare($sql);
    if ($perfil !== 1 && $perfil !== 2) {
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
    }
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>
            alert('Erro ao buscar histórico de estoque.');
          </script>";
    exit();
}
