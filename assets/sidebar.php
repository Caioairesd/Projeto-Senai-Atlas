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
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_estoque.php", "icone" => "fas fa-warehouse", "texto" => "Visualizar Estoque"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "entrada_estoque.php", "icone" => "fas fa-plus-square", "texto" => "Entrada de Estoque"],
            ["pasta" => "estoque", "arquivo" => "saida_estoque.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "historico_movimentacao.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ],
    2 => [
        "Visualizar" => [
            ["pasta" => "visualizar", "arquivo" => "visualizar_cliente.php", "icone" => "fas fa-users", "texto" => "Visualizar Cliente"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "saida_estoque.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "historico_movimentacao.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ],
    3 => [
        "Cadastrar" => [
            ["pasta" => "criar", "arquivo" => "criar_fornecedor.php", "icone" => "fas fa-truck", "texto" => "Cadastrar Fornecedor"],
            ["pasta" => "criar", "arquivo" => "criar_produto.php", "icone" => "fas fa-box", "texto" => "Cadastrar Produto"]
        ],
        "Visualizar" => [
            ["pasta" => "visualizar", "arquivo" => "visualizar_fornecedor.php", "icone" => "fas fa-industry", "texto" => "Visualizar Fornecedor"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_produto.php", "icone" => "fas fa-inventory", "texto" => "Visualizar Produto"],
            ["pasta" => "visualizar", "arquivo" => "visualizar_estoque.php", "icone" => "fas fa-warehouse", "texto" => "Visualizar Estoque"]
        ],
        "Estoque" => [
            ["pasta" => "estoque", "arquivo" => "entrada_estoque.php", "icone" => "fas fa-plus-square", "texto" => "Entrada de Estoque"],
            ["pasta" => "estoque", "arquivo" => "saida_estoque.php", "icone" => "fas fa-minus-square", "texto" => "Saída de Estoque"],
            ["pasta" => "estoque", "arquivo" => "historico_movimentacao.php", "icone" => "fas fa-history", "texto" => "Histórico de Movimentações"]
        ]
    ]
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];
?>

<div class="p-6">
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-cube"></i>
                <span class="logo-text">Atlas</span>
            </div>
            <div class="perfil-info">
                <small><?= htmlspecialchars($nome_perfil) ?></small>
            </div>
        </div>

        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $links): ?>
                <?php if (!empty($links)): ?>
                    <li class="dropdown">
                        <a href="#" class="menu-item">
                            <i class="menu-icon 
                                <?= $categoria === 'Cadastrar' ? 'fas fa-plus-circle' : 
                                   ($categoria === 'Estoque' ? 'fas fa-warehouse' : 'fas fa-eye') ?>">
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
        </ul>

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
    </nav>
</div>
