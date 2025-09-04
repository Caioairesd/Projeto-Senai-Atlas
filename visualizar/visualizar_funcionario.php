<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';
$sql = "SELECT id_funcionario, nome_funcionario, email_funcionario, imagem_url_funcionario FROM funcionario ORDER BY nome_funcionario ASC";
$stmt = $pdo->query($sql);
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Funcionários</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>

<body>
  <div class="table-wrapper">
    <h2>Funcionários</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($funcionarios as $f): ?>
          <tr>
            <td>
                <img src="exibir_imagem.php?tipo=funcionario&id=<?= $f['id_funcionario'] ?>"
                  style="width:80px; height:80px; object-fit:cover; border-radius:6px;" alt="Foto do Funcionário">
            </td>
            <td><?= htmlspecialchars($f['nome_funcionario']) ?></td>
            <td><?= htmlspecialchars($f['email_funcionario']) ?></td>
            <td>
              <div class="btn-group">
                <a class="btn" href="detalhes_funcionario.php?id=<?= $f['id_funcionario'] ?>">Ver</a>
                <a class="btn btn-edit" href="../editar/editar_funcionario.php?id=<?= $f['id_funcionario'] ?>">Editar</a>
                <a class="btn btn-delete" href="../excluir/excluir_funcionario.php?id=<?= $f['id_funcionario'] ?>"
                  onclick="return confirm('Excluir este funcionário?')">Excluir</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>