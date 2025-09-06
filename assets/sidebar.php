<?php
// Inicia o bloco PHP

session_start(); 
// Inicia a sessão para permitir o uso de variáveis $_SESSION em todo o sistema

require_once '../config/conexao.php'; 
// Inclui o arquivo de conexão com o banco de dados usando PDO
// require_once garante que o arquivo será incluído apenas uma vez

// Verifica se o usuário está logado e se o perfil foi definido
if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil'])) {
    // !isset() retorna true se a variável não estiver definida
    // || é o operador lógico "OU"
    // Se não houver usuário ou perfil na sessão, redireciona para o login
    header('Location: ../Login/login.php'); // Redireciona para a página de login
    exit(); // Interrompe a execução do script
}

$id_perfil = $_SESSION['perfil']; 
// Armazena o ID do perfil do usuário logado em uma variável

// Consulta SQL para buscar o nome do perfil no banco
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
// :id_perfil é um parâmetro nomeado que será substituído pelo valor real

$stmtPerfil = $pdo->prepare($sqlPerfil); 
// Prepara a consulta para execução segura (evita SQL Injection)

$stmtPerfil->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT); 
// Associa o valor de $id_perfil ao parâmetro :id_perfil como inteiro

$stmtPerfil->execute(); 
// Executa a consulta no banco

$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC); 
// Busca o resultado como array associativo (chaves = nomes das colunas)

$nome_perfil = $perfil['nome_perfil'] ?? 'Perfil não encontrado'; 
// Se existir nome_perfil, usa-o; caso contrário, usa o texto padrão

// Define as permissões para cada perfil
$permissoes = [
    1 => [ // Perfil 1
        "Cadastrar" => [
            ["arquivo" => "criar_cliente.php", "icone" => "fas fa-user-plus", "texto" => "Cadastrar Cliente"],
            ["arquivo" => "criar_fornecedor.php", "icone" => "fas fa-truck", "texto" => "Cadastrar Fornecedor"],
            ["arquivo" => "criar_funcionario.php", "icone" => "fas fa-user-tie", "texto" => "Cadastrar Funcionário"],
            ["arquivo" => "criar_produto.php", "icone" => "fas fa-box", "texto" => "Cadastrar Produto"]
        ],
        "Visualizar" => [
            ["arquivo" => "visualizar_cliente.php", "icone" => "fas fa-users", "texto" => "Visualizar Cliente"],
            ["arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["arquivo" => "visualizar_funcionario.php", "icone" => "fas fa-id-badge", "texto" => "Visualizar Funcionário"],
            ["arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"]
        ]
    ],
    2 => [ // Perfil 2
        "Visualizar" => [
            ["arquivo" => "visualizar_cliente.php", "icone" => "fas fa-users", "texto" => "Visualizar Cliente"],
            ["arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"]
        ]
    ],
    3 => [ // Perfil 3
        "Cadastrar" => [
            ["arquivo" => "criar_fornecedor.php", "icone" => "fas fa-truck", "texto" => "Cadastrar Fornecedor"],
            ["arquivo" => "criar_produto.php", "icone" => "fas fa-box", "texto" => "Cadastrar Produto"]
        ],
        "Visualizar" => [
            ["arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"]
        ]
    ]
];

$opcoes_menu = $permissoes[$id_perfil] ?? []; 
// Seleciona as opções de menu do perfil logado ou array vazio se não existir
?>

<!-- HTML da barra lateral -->
<div class="p-6"> <!-- Div com padding de 6 (Tailwind CSS) -->
    <nav class="sidebar"> <!-- Elemento de navegação com classe 'sidebar' -->
        
        <div class="sidebar-header"> <!-- Cabeçalho da barra lateral -->
            <div class="logo"> <!-- Container do logo -->
                <i class="fas fa-cube"></i> <!-- Ícone de cubo (Font Awesome) -->
                <span class="logo-text">Atlas</span> <!-- Texto do logo -->
            </div>
            <div class="perfil-info"> <!-- Exibe informações do perfil -->
                <small><?= htmlspecialchars($nome_perfil) ?></small> <!-- Mostra o nome do perfil escapado para segurança -->
            </div>
        </div>

        <ul class="menu"> <!-- Lista principal do menu -->
            <?php foreach ($opcoes_menu as $categoria => $links): ?> <!-- Loop pelas categorias do menu -->
                <?php if (!empty($links)): ?> <!-- Se houver links nessa categoria -->
                    <li class="dropdown"> <!-- Item do menu com submenu -->
                        <a href="#" class="menu-item"> <!-- Link principal da categoria -->
                            <i class="menu-icon <?= $categoria === 'Cadastrar' ? 'fas fa-plus-circle' : 'fas fa-eye' ?>"></i> <!-- Ícone condicional -->
                            <span class="menu-text"><?= $categoria ?></span> <!-- Nome da categoria -->
                            <i class="dropdown-arrow fas fa-chevron-right"></i> <!-- Seta indicando submenu -->
                        </a>
                        <ul class="dropdown-menu"> <!-- Lista de subitens -->
                            <?php foreach ($links as $link): ?> <!-- Loop pelos links da categoria -->
                                <li>
                                    <a href="../<?= $categoria === 'Cadastrar' ? 'criar' : 'cruds/visualizar' ?>/<?= $link['arquivo'] ?>"> <!-- Monta o caminho do link -->
                                        <i class="<?= $link['icone'] ?>"></i> <!-- Ícone do link -->
                                        <span><?= $link['texto'] ?></span> <!-- Texto do link -->
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <div class="sidebar-footer"> <!-- Rodapé da barra lateral -->
            <div class="logout-item"> <!-- Container do botão de logout -->
                <form action="../Login/logout.php" method="post"> <!-- Formulário para logout -->
                    <button type="submit" class="logout-btn"> <!-- Botão de envio -->
                        <i class="fas fa-sign-out-alt"></i> <!-- Ícone de saída -->
                        <span class="menu-text">Sair</span> <!-- Texto do botão -->
                    </button>
                </form>
            </div>
        </div>

    </nav>
</div>
