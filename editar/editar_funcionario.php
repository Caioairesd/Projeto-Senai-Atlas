<?php
session_start();
require_once '../config/conexao.php';
include '../assets/sidebar.php';

// VERIFICA SE O ID FOI PASSADO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location.href='../visualizar/visualizar_funcionarios.php';</script>";
    exit();
}

$id_funcionario = $_GET['id'];

// BUSCA DADOS DO FUNCIONÁRIO
$sql = "SELECT * FROM funcionario WHERE id_funcionario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
$stmt->execute();
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

// ATUALIZA FUNCIONÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_funcionario'];
    $email = $_POST['email_funcionario'];
    $telefone = $_POST['telefone_funcionario'];
    $cargo = $_POST['cargo_funcionario'];
    $cpf = $_POST['cpf_funcionario'];
    $salario = $_POST['salario_funcionario'];
    $endereco = $_POST['endereco_funcionario'];
    $nascimento = $_POST['nascimento_funcionario'];
    $admissao = $_POST['data_admissao'];

    $sql = "UPDATE funcionario SET 
                nome_funcionario = :nome, 
                email_funcionario = :email, 
                telefone_funcionario = :telefone, 
                cargo_funcionario = :cargo,
                cpf_funcionario = :cpf,
                salario_funcionario = :salario,
                endereco_funcionario = :endereco,
                data_nascimento = :nascimento,
                data_admissao = :admissao
            WHERE id_funcionario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':salario', $salario);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':nascimento', $nascimento);
    $stmt->bindParam(':admissao', $admissao);
    $stmt->bindParam(':id', $id_funcionario);

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href='../visualizar/visualizar_funcionarios.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar funcionário.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Editar Funcionário</h2>
        <form method="post">
            <div class="input-group">
                <label>Nome:</label>
                <input type="text" name="nome_funcionario"
                    value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" required>
            </div>

            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email_funcionario"
                    value="<?= htmlspecialchars($funcionario['email_funcionario']) ?>" required>
            </div>

            <div class="input-group">
                <label>Telefone:</label>
                <input type="text" name="telefone_funcionario"
                    value="<?= htmlspecialchars($funcionario['telefone_funcionario']) ?>" required>
            </div>


            <div class="input-group">
                <label>CPF:</label>
                <input type="text" name="cpf_funcionario"
                    value="<?= htmlspecialchars($funcionario['cpf_funcionario']) ?>" required>
            </div>

            <div class="input-group">
                <label>Salário:</label>
                <input type="number" step="0.01" name="salario_funcionario"
                    value="<?= htmlspecialchars($funcionario['salario_funcionario']) ?>" required>
            </div>

            <div class="input-group">
                <label>Endereço:</label>
                <input type="text" name="endereco_funcionario"
                    value="<?= htmlspecialchars($funcionario['endereco_funcionario']) ?>" required>
            </div>

            <div class="input-group">
                <label>Data de Nascimento:</label>
                <input type="date" name="nascimento_funcionario"
                    value="<?= htmlspecialchars($funcionario['data_nascimento']) ?>" required>
            </div>

            <div class="input-group">
                <label>Data de Admissão:</label>
                <input type="date" name="data_admissao" value="<?= htmlspecialchars($funcionario['data_admissao']) ?>"
                    required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-edit">Salvar</button>
                <a href="../visualizar/visualizar_funcionarios.php" class="btn btn-delete">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>