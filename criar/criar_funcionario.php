<?php
require_once '../config/conexao.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura os dados do funcionário
    $nome = $_POST['nome_funcionario'] ?? '';
    $email = $_POST['email_funcionario'] ?? '';
    $telefone = $_POST['telefone_funcionario'] ?? '';
    $cpf = $_POST['cpf_funcionario'] ?? '';

    // Processa o salário removendo máscara e convertendo para float
    $salario = $_POST['salario_funcionario'] ?? '';
    $salario = str_replace(['R$', '.', ','], ['', '', '.'], $salario);
    $salario = trim($salario);
    $salario = (float) $salario;

    $endereco = $_POST['endereco_funcionario'] ?? '';
    $nascimento = $_POST['data_nascimento'] ?? '';
    $admissao = $_POST['data_admissao'] ?? '';

    $imagem = null;

    // Verifica se foi enviada uma imagem válida
    if (!empty($_FILES['imagem_url_funcionario']['tmp_name'])) {
        $tmpFile = $_FILES['imagem_url_funcionario']['tmp_name'];
        $fileSize = $_FILES['imagem_url_funcionario']['size'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($fileSize > $maxSize) {
            header("Location: criar_funcionario.php?msg=Imagem excede o tamanho máximo de 2MB.&type=error");
            exit();
        }

        $tipo = @exif_imagetype($tmpFile);
        $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

        if ($tipo === false || !in_array($tipo, $tiposPermitidos)) {
            header("Location: criar_funcionario.php?msg=Tipo de imagem inválido. Use JPEG, PNG ou GIF.&type=error");
            exit();
        }

        $imagem = file_get_contents($tmpFile);
    }

    // Insere os dados do funcionário
    $sql = "INSERT INTO funcionario (
                nome_funcionario, email_funcionario, telefone_funcionario, cpf_funcionario,
                salario_funcionario, endereco_funcionario, data_nascimento, data_admissao, imagem_url_funcionario
            ) VALUES (
                :nome, :email, :telefone, :cpf, :salario, :endereco, :nascimento, :admissao, :imagem
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':salario', $salario);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':nascimento', $nascimento);
    $stmt->bindParam(':admissao', $admissao);
    $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB);

    if ($stmt->execute()) {
        $idFuncionario = $pdo->lastInsertId();

        // Captura dados do usuário vinculado
        $nomeUsuario = $_POST['nome_usuario'] ?? $nome;
        $emailUsuario = $email;
        $senhaUsuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT);
        $perfilId = $_POST['perfil_id'] ?? 2;

        // Insere o usuário vinculado ao funcionário
        $sqlUsuario = "INSERT INTO usuario (
                           nome_usuario, email_usuario, senha_usuario, perfil_id, funcionario_id
                       ) VALUES (
                           :nome, :email, :senha, :perfil, :funcionario
                       )";
        $stmtUsuario = $pdo->prepare($sqlUsuario);
        $stmtUsuario->bindParam(':nome', $nomeUsuario);
        $stmtUsuario->bindParam(':email', $emailUsuario);
        $stmtUsuario->bindParam(':senha', $senhaUsuario);
        $stmtUsuario->bindParam(':perfil', $perfilId);
        $stmtUsuario->bindParam(':funcionario', $idFuncionario);
        $stmtUsuario->execute();

        header("Location: criar_funcionario.php?msg=Funcionário e usuário cadastrados com sucesso.&type=success");
        exit();
    } else {
        header("Location: criar_funcionario.php?msg=Erro ao cadastrar funcionário.&type=error");
        exit();
    }
}
?>

<?php include '../assets/sidebar.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="form-wrapper">
        <h2>Cadastrar Funcionário + Usuário</h2>

        <!-- Exibe mensagem de feedback, se houver -->
        <?php if (isset($_GET['msg']) && isset($_GET['type'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type']) ?>">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-section">
                <h3>Dados do Funcionário</h3>

                <div class="input-group">
                    <label for="nome_funcionario">Nome</label>
                    <input type="text" name="nome_funcionario" id="nome_funcionario" placeholder="Nome completo" required>
                </div>

                <div class="input-group">
                    <label for="email_funcionario">Email</label>
                    <input type="email" name="email_funcionario" id="email_funcionario" placeholder="email@exemplo.com" required>
                </div>

                <div class="input-group">
                    <label for="telefone_funcionario">Telefone</label>
                    <input type="text" name="telefone_funcionario" id="telefone_funcionario" placeholder="(XX) XXXXX-XXXX" required>
                </div>

                <div class="input-group">
                    <label for="cpf_funcionario">CPF</label>
                    <input type="text" name="cpf_funcionario" id="cpf_funcionario" placeholder="000.000.000-00" required>
                </div>

                <div class="input-group">
                    <label for="salario_funcionario">Salário</label>
                    <input type="text" name="salario_funcionario" id="salario_funcionario" placeholder="R$ 0,00" required>
                </div>

                <div class="input-group">
                    <label for="endereco_funcionario">Endereço</label>
                    <input type="text" name="endereco_funcionario" id="endereco_funcionario" placeholder="Rua, número, bairro" required>
                </div>

                <div class="input-group">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" required>
                </div>

                <div class="input-group">
                    <label for="data_admissao">Data de admissão</label>
                    <input type="date" name="data_admissao" id="data_admissao" required>
                </div>

                <div class="input-group">
                    <label for="imagem_url_funcionario">Foto</label>
                    <input type="file" name="imagem_url_funcionario" id="imagem_url_funcionario" accept="image/*">
                </div>
            </div>

            <div class="form-section">
                <h3>Dados de Acesso do Usuário</h3>

                <div class="input-group">
                    <label for="nome_usuario">Nome de usuário</label>
                    <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Nome de login" required>
                </div>

                <div class="input-group">
                    <label for="senha_usuario">Senha</label>
                    <input type="password" name="senha_usuario" id="senha_usuario" placeholder="••••••••" required>
                </div>

                <div class="input-group">
                    <label for="perfil_id">Perfil</label>
                    <select name="perfil_id" id="perfil_id" class="select2" required>
                        <option value="">Selecione o perfil</option>
                        <option value="1">Administrador</option>
                        <option value="2">Vendedor</option>
                        <option value="3">Estoquista</option>
                    </select>
                </div>
            </div>

                       <div class="btn-group">
                <button type="submit" class="btn">Cadastrar</button>
                <button type="reset" class="btn btn-edit">Limpar</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inicializa o componente Select2 para o campo de perfil
        $(document).ready(function() {
            $('#perfil_id').select2({
                placeholder: "Selecione o perfil",
                width: '100%'
            });
        });

        // Remove automaticamente a mensagem de feedback após 4 segundos
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.style.display = 'none';
        }, 4000);
    </script>

    <script src="../assets/validacoes.js"></script>
</body>
</html>
