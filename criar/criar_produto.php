<?php
require_once '../config/conexao.php';
include '../assets/sidebar.php';

$msg = '';

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

    $imagem_blob = null;

    if (!empty($_FILES['imagem_url_produto']['tmp_name']) && $_FILES['imagem_url_produto']['error'] === UPLOAD_ERR_OK) {
        $tmpFile  = $_FILES['imagem_url_produto']['tmp_name'];
        $fileSize = $_FILES['imagem_url_produto']['size'];
        $maxSize = 2 * 1024 * 1024;

        if ($fileSize > $maxSize) {
            die("Erro: A imagem excede o tamanho máximo de 2MB.");
        }

        $tipo = @exif_imagetype($tmpFile);
        $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

        if ($tipo === false || !in_array($tipo, $tiposPermitidos)) {
            die("Erro: Apenas imagens JPEG, PNG ou GIF são permitidas.");
        }

        $imagem_blob = file_get_contents($tmpFile);
    }

    $sql = "INSERT INTO produto 
            (nome_produto, descricao_produto, plataforma_produto, tipo_produto, preco_produto, imagem_url_produto, fornecedor_id)
            VALUES 
            (:nome_produto, :descricao_produto, :plataforma_produto, :tipo_produto, :preco_produto, :imagem_blob, :fornecedor_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_produto', $nome_produto);
    $stmt->bindParam(':descricao_produto', $descricao_produto);
    $stmt->bindParam(':plataforma_produto', $plataforma_produto);
    $stmt->bindParam(':tipo_produto', $tipo_produto);
    $stmt->bindParam(':preco_produto', $preco_produto);
    $stmt->bindParam(':imagem_blob', $imagem_blob, PDO::PARAM_LOB);
    $stmt->bindParam(':fornecedor_id', $fornecedor_id);

    if ($stmt->execute()) {
        $msg = '<div class="sucesso">Produto cadastrado com sucesso!</div>';
    } else {
        $msg = '<div class="erro">Erro ao cadastrar produto!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Cadastrar Produto</title>
</head>

<body>

    <div class="form-wrapper">
        <h2>Cadastrar Produto</h2>
        <p>Preencha os dados abaixo para adicionar um novo produto ao sistema.</p>

        <?= $msg ?? '' ?>

        <form method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="nome_produto">Nome do Produto</label>
                <input type="text" id="nome_produto" name="nome_produto"  placeholder="Ex: Elden Ring " required />
            </div>

            <div class="input-group">
                <label for="descricao_produto">Descrição</label>
                <input type="text" id="descricao_produto" name="descricao_produto" placeholder="Breve descrição do produto" required />
            </div>

            <div class="input-group">
                <label for="plataforma_produto">Plataforma</label>
                <select id="plataforma_produto" name="plataforma_produto" class="select2" required>
                    <option value="">Selecione a plataforma</option>
                    <option value="PC">PC</option>
                    <option value="PlayStation">PlayStation</option>
                    <option value="PlayStation 2">PlayStation 2</option>
                    <option value="PlayStation 3">PlayStation 3</option>
                    <option value="PlayStation 4">PlayStation 4</option>
                    <option value="PlayStation 5">PlayStation 5</option>
                    <option value="Xbox 360">Xbox 360</option>
                    <option value="Xbox One">Xbox One</option>
                    <option value="Xbox Series X/S">Xbox Series X/S</option>
                    <option value="Nintendo DS">Nintendo DS</option>
                    <option value="Nintendo 3DS">Nintendo 3DS</option>
                    <option value="Nintendo Wii">Nintendo Wii</option>
                    <option value="Nintendo Switch">Nintendo Switch</option>
                    <option value="Mobile">Mobile</option>
                </select>
            </div>

            <div class="input-group">
                <label for="tipo_produto">Categoria</label>
                <select id="tipo_produto" name="tipo_produto" class="select2" required>
                    <option value="">Selecione a categoria</option>
                    <option value="Ação">Ação</option>
                    <option value="Aventura">Aventura</option>
                    <option value="RPG">RPG</option>
                    <option value="FPS">Tiro em 1ª pessoa (FPS)</option>
                    <option value="TPS">Tiro em 3ª pessoa (TPS)</option>
                    <option value="Estratégia">Estratégia</option>
                    <option value="Simulação">Simulação</option>
                    <option value="Corrida">Corrida</option>
                    <option value="Esporte">Esporte</option>
                    <option value="Luta">Luta</option>
                    <option value="Terror">Terror</option>
                    <option value="Plataforma">Plataforma</option>
                    <option value="Puzzle">Puzzle / Quebra-cabeça</option>          
                    <option value="Casual">Casual</option>
                    <option value="Battle Royale">Battle Royale</option>
                    <option value="MOBA">MOBA</option>
                    <option value="MMORPG">MMORPG</option>
                    <option value="Sandbox">Sandbox / Mundo Aberto</option>
                    <option value="Stealth">Stealth / Furtividade</option>
                    <option value="Musical">Musical / Ritmo</option>
                    <option value="Narrativo">Narrativo / Interativo</option>
                    <option value="Visual Novel">Visual Novel</option>
                    <option value="Survival">Sobrevivência</option>
                    <option value="Tower Defense">Tower Defense</option>
                    <option value="Roguelike">Roguelike / Roguelite</option>
                    <option value="Metroidvania">Metroidvania</option>
                    <option value="Hack and Slash">Hack and Slash</option>
                    <option value="Idle">Idle / Incremental</option>
                    <option value="Tycoon">Tycoon / Gestão</option>
                </select>
            </div>

            <div class="input-group">
                <label for="preco_produto">Preço</label>
                <input type="text" id="preco_produto" name="preco_produto"  placeholder="R$ 0,00" required />
            </div>

            <div class="input-group">
                <label for="imagem_url_produto">Imagem do Produto</label>
                <input type="file" id="imagem_url_produto" name="imagem_url_produto" accept="image/*" required />
                <small style="color: #666;">Selecione uma imagem (JPEG ou PNG)</small>
            </div>

            <div class="input-group">
                <label for="fornecedor_id">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="select2" required>
                    <option value="">Selecione o fornecedor</option>
                    <?php
                    $fornecedores = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor")->fetchAll();
                    foreach ($fornecedores as $f) {
                        echo "<option value='{$f['id_fornecedor']}'>{$f['nome_fornecedor']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn">Cadastrar</button>
            <button type="reset" class="btn btn-edit">Limpar</button>
        </form>
    </div>

    <script src="../assets/validacoes.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Digite ou selecione",
                width: '100%'
            });
        });
    </script>
</body>

</html>