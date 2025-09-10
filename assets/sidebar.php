<?php
session_start();
require_once '../config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['perfil'])) {
    header('Location: ../Login/login.php');
    exit();
}

$id_perfil = (int) $_SESSION['perfil'];

// Busca nome do perfil
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);

$nome_perfil = $perfil['nome_perfil'] ?? 'Perfil não encontrado';

// Permissões por perfil (com pasta definida)
$permissoes = [
    1 => [
        "Cadastrar" => [
            ["pasta" => "criar", "arquivo" => "criar_cliente.php", "icone" => "fas fa-user-plus", "texto" => "Cadastrar Cliente"],
            ["pasta" => "criar", "arquivo" => "criar_fornecedor.php", "icone" => "fas fa-truck", "texto" => "Cadastrar Fornecedor"],
            ["pasta" => "criar", "arquivo" => "criar_funcionario.php", "icone" => "fas fa-user-tie", "texto" => "Cadastrar Funcionário"],
            ["pasta" => "criar", "arquivo" => "criar_produto.php", "icone" => "fas fa-box", "texto" => "Cadastrar Produto"]
        ],
        "Visualizar" => [
            ["pasta" => "visualizar", "arquivo" => "visualizar_cliente.php", "icone" => "fas fa-users", "texto" => "Visualizar Cliente"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_funcionario.php", "icone" => "fas fa-id-badge", "texto" => "Visualizar Funcionário"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-box", "texto" => "Visualizar Produto"],
            ["pasta" => "estoque", "arquivo" => "estoque_geral.php", "icone" => "fas fa-warehouse", "texto" => "Visualizar Estoque"],
            ["pasta" => "estoque", "arquivo" => "pedidos_lista.php", "icone" => "fas fa-shopping-cart", "texto" => "Lista de Pedidos"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "estoque_entrada.php", "icone" => "fas fa-plus-square", "texto" => "Entrada de Estoque"],
            ["pasta" => "estoque", "arquivo" => "estoque_saida.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "estoque_historico.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ],
    2 => [
        "Visualizar" => [
            ["pasta" => "visualizar", "arquivo" => "visualizar_cliente.php", "icone" => "fas fa-users", "texto" => "Visualizar Cliente"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"],
            ["pasta" => "estoque", "arquivo" => "pedidos_lista.php", "icone" => "fas fa-shopping-cart", "texto" => "Lista de Pedidos"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "estoque_saida.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "estoque_historico.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ],
    3 => [
        "Visualizar" => [
            ["pasta" => "visualizar", "arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"],
            ["pasta" => "estoque", "arquivo" => "estoque_geral.php", "icone" => "fas fa-warehouse", "texto" => "Visualizar Estoque"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "estoque_entrada.php", "icone" => "fas fa-plus-square", "texto" => "Entrada de Estoque"],
            ["pasta" => "estoque", "arquivo" => "estoque_saida.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "estoque_historico.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ]
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Atlas</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>


    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-cube"></i>
                <span class="logo-text">Atlas</span>
            </div>
          
        </div>

        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $links): ?>
                <?php if (!empty($links)): ?>
                    <li class="dropdown">
                        <a href="#" class="menu-item">
                            <i class="menu-icon 
                                <?= ($categoria === 'Cadastrar') ? 'fas fa-plus-circle' :
                                    (($categoria === 'Visualizar') ? 'fas fa-search' :
                                    (($categoria === 'Estoque') ? 'fas fa-warehouse' : 'fas fa-eye')) ?>">
                            </i>

                            <span class="menu-text"><?= htmlspecialchars($categoria) ?></span>
                            <i class="dropdown-arrow fas fa-chevron-right"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($links as $link): ?>
                                <li>
                                    <a href="../<?= htmlspecialchars($link['pasta']) ?>/<?= htmlspecialchars($link['arquivo']) ?>">
                                        <i class="<?= htmlspecialchars($link['icone']) ?>"></i>
                                        <span><?= htmlspecialchars($link['texto']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <div class="sidebar-footer">
                <div class="logout-item">
                    <form action="../Login/logout.php" method="post">
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="menu-text">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
            
            
            <!-- Dashboard -->
            <li>
                <a href="../dashboard/dashboard.php" class="menu-item">
                    <i class="menu-icon fas fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                
            </li>
            
            
        </ul>



        
    </nav>

    <script>
        // Função para alternar tema
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const currentTheme = html.getAttribute('data-theme');

            if (currentTheme === 'light') {
                html.setAttribute('data-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            } else {
                html.setAttribute('data-theme', 'light');
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            }
        }

        // Carregar tema salvo
        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');

            html.setAttribute('data-theme', savedTheme);
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });

        // Adicionar animação de fade-in aos elementos
        document.addEventListener('DOMContentLoaded', function () {
            const elements = document.querySelectorAll('.content-wrapper, .form-wrapper, .table-wrapper, .details-wrapper');
            elements.forEach(el => el.classList.add('fade-in'));
        });
    </script>
</body>

</html>