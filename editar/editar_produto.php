<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido.'); window.parent.fecharModal();</script>";
    exit;
}

$sql = "SELECT * FROM produto WHERE id_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch();

if (!$produto) {
    echo "<script>alert('Produto não encontrado.'); window.parent.fecharModal();</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_produto = $_POST['nome_produto'] ?? '';
    $descricao_produto = $_POST['descricao_produto'] ?? '';
    $plataforma_produto = $_POST['plataforma_produto'] ?? '';
    $tipo_produto = $_POST['tipo_produto'] ?? '';

    $preco_produto = $_POST['preco_produto'] ?? '';
    // Converte "R$ 59,90" para "59.90" retirando as máscaras para salvar no banco
    $preco_produto = str_replace(['R$', '.', ','], ['', '', '.'], $preco_produto);
    $preco_produto = trim($preco_produto);
    $preco_produto = (float) $preco_produto;

    $fornecedor_id = $_POST['fornecedor_id'] ?? '';

    $imagem_blob = $produto['imagem_url_produto'];

    if (!empty($_FILES['imagem_url_produto']['tmp_name']) && $_FILES['imagem_url_produto']['error'] === UPLOAD_ERR_OK) {
        $tmpFile  = $_FILES['imagem_url_produto']['tmp_name'];
        $fileSize = $_FILES['imagem_url_produto']['size'];
        $maxSize = 2 * 1024 * 1024;

        if ($fileSize > $maxSize) {
            echo "<script>alert('Erro: A imagem excede 2MB.');</script>";
        } else {
            $tipo = @exif_imagetype($tmpFile);
            $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

            if ($tipo === false || !in_array($tipo, $tiposPermitidos)) {
                echo "<script>alert('Erro: Apenas JPEG, PNG ou GIF são permitidos.');</script>";
            } else {
                $imagem_blob = file_get_contents($tmpFile);
            }
        }
    }

    $sql = "UPDATE produto SET 
                nome_produto = :nome_produto,
                descricao_produto = :descricao_produto,
                plataforma_produto = :plataforma_produto,
                tipo_produto = :tipo_produto,
                preco_produto = :preco_produto,
                imagem_url_produto = :imagem_blob,
                fornecedor_id = :fornecedor_id
            WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_produto', $nome_produto);
    $stmt->bindParam(':descricao_produto', $descricao_produto);
    $stmt->bindParam(':plataforma_produto', $plataforma_produto);
    $stmt->bindParam(':tipo_produto', $tipo_produto);
    $stmt->bindParam(':preco_produto', $preco_produto);
    $stmt->bindParam(':imagem_blob', $imagem_blob, PDO::PARAM_LOB);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>
            alert('Produto atualizado com sucesso!');
            window.parent.fecharModal();
        </script>";
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar produto.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/style.css" />
    <title>Editar Produto</title>
</head>

<body>
    <div class="form-wrapper">
        <h2>Editar Produto</h2>
        <p>Atualize os dados do produto abaixo.</p>

        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome_produto" value="<?= htmlspecialchars($produto['nome_produto']) ?>" required />
            </div>

            <div class="input-group">
                <label for="descricao_produto">Descrição</label>
                <input type="text" id="descricao_produto" name="descricao_produto" value="<?= htmlspecialchars($produto['descricao_produto']) ?>" required />
            </div>

            <div class="input-group">
                <label for="plataforma_produto">Plataforma</label>
                <select id="plataforma_produto" name="plataforma_produto" class="select2" required>
                    <option value="">Selecione a plataforma</option>
                    <?php
                    $plataformas = ["PC", "PlayStation 5", "PlayStation 4", "Xbox Series X/S", "Nintendo Switch", "Mobile"];
                    foreach ($plataformas as $p) {
                        $selected = $p === $produto['plataforma_produto'] ? 'selected' : '';
                        echo "<option value=\"$p\" $selected>$p</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label for="tipo_produto">Categoria</label>
                <select id="tipo_produto" name="tipo_produto" class="select2" required>
                    <option value="">Selecione a categoria</option>
                    <?php
                    $categorias = [
                        "Ação",
                        "Aventura",
                        "RPG",
                        "FPS",
                        "TPS",
                        "Estratégia",
                        "Simulação",
                        "Corrida",
                        "Esporte",
                        "Luta",
                        "Terror",
                        "Plataforma",
                        "Puzzle",
                        "Casual",
                        "Battle Royale",
                        "MOBA",
                        "MMORPG",
                        "Sandbox",
                        "Stealth",
                        "Musical",
                        "Narrativo",
                        "Visual Novel",
                        "Survival",
                        "Tower Defense",
                        "Roguelike",
                        "Metroidvania",
                        "Hack and Slash",
                        "Idle",
                        "Tycoon"
                    ];
                    foreach ($categorias as $c) {
                        $selected = $c === $produto['tipo_produto'] ? 'selected' : '';
                        echo "<option value=\"$c\" $selected>$c</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label for="preco_produto">Preço</label>
                <input type="text" id="preco_produto" name="preco_produto" value="<?= htmlspecialchars($produto['preco_produto']) ?>" required />
            </div>

            <div class="input-group">
                <label for="imagem_url_produto">Nova Imagem (opcional)</label>
                <input type="file" id="imagem_url_produto" name="imagem_url_produto" accept="image/*" />
            </div>

            <div class="input-group">
                <label for="fornecedor_id">Fornecedor</label>
                <select name="fornecedor_id" class="select2" required>
                    <option value="">Selecione o fornecedor</option>
                    <?php
                    $fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor")->fetchAll();
                    foreach ($fornecedores as $f) {
                        $selected = $f['id_fornecedor'] == $produto['fornecedor_id'] ? 'selected' : '';
                        echo "<option value='{$f['id_fornecedor']}' $selected>{$f['nome_fornecedor']}</option>";
                    }
                    ?>
                </select>

                <div class="btn-group">
                    <button type="submit" class="btn btn-edit">Salvar alterações</button>
                    <a href="../visualizar/visualizar_produto.php" class="btn">Cancelar</a>
                </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Selecione",
                width: '100%'
            });
        });
    </script>
    <script src="../assets/validacoes.js"></script>
</body>

</html>