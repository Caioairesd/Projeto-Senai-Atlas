<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$busca = $_GET['busca'] ?? '';

// Consulta com filtro
$sql = "SELECT id_funcionario, nome_funcionario, email_funcionario, imagem_url_funcionario 
        FROM funcionario 
        WHERE nome_funcionario LIKE :busca OR id_funcionario LIKE :busca
        ORDER BY nome_funcionario ASC";

$stmt = $pdo->prepare($sql);
$termoBusca = '%' . $busca . '%';
$stmt->bindParam(':busca', $termoBusca, PDO::PARAM_STR);
$stmt->execute();
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

    <!-- Campo de busca -->
    <form method="get" class="search-form">
  <input type="text" name="busca" placeholder="Buscar Funcionário..." value="<?= htmlspecialchars($busca) ?>" class="input">
  <button type="submit" class="btn">Buscar</button>
</form>


    <?php if (count($funcionarios) > 0): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>ID</th>
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
              <td><?= htmlspecialchars($f['id_funcionario']) ?></td>
              <td>
                <div class="btn-group">
                  <a class="btn" href="detalhes_funcionario.php?id=<?= $f['id_funcionario'] ?>">Ver</a>
                  <a class="btn btn-edit" href="../editar/editar_funcionario.php?id=<?= $f['id_funcionario'] ?>">Editar</a>
                  <a class="btn btn-delete" href="../excluir/excluir_funcionario.php?id=<?= $f['id_funcionario'] ?>" onclick="return confirm('Excluir este funcionário?')">Excluir</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning">Nenhum funcionário encontrado com esse termo.</div>
    <?php endif; ?>
  </div>
</body>

</html>