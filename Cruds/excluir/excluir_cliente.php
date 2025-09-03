<?php
session_start();

require_once('../conexao.php');


// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE adm

// INICIALIZA AS VARIÁVEIS
$clientes = null;

// BUSCA TODOS OS CLIENTES CADASTRADOS EM ORDEM ALFABÉTICA
$query = "SELECT * FROM cliente ORDER BY nome_cliente ASC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// SE UM id FOR PASSADO VIA GET, EXCLUI O CLIENTE
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = $_GET['id'];

    $query = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id_cliente, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script> alert('Cliente excluído com sucesso!'); window.location.href='excluir_cliente.php'; </script>";
    } else {
        echo "<script> alert('Erro ao excluir cliente!'); </script>";
    }
}
?>
