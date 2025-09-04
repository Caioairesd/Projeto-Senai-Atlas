<!DOCTYPE html>
<html lang="pt-BR">
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
            <!-- Cadastrar -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-plus-circle"></i>
                    <span class="menu-text">Cadastrar</span>
                    <i class="dropdown-arrow fas fa-chevron-right"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="../criar/criar_cliente.php">
                        <i class="fas fa-user-plus"></i>
                        <span>Cadastrar Cliente</span>
                    </a></li>
                    <li><a href="../criar/criar_fornecedor.php">
                        <i class="fas fa-truck"></i>
                        <span>Cadastrar Fornecedor</span>
                    </a></li>
                    <li><a href="../criar/criar_funcionario.php">
                        <i class="fas fa-user-tie"></i>
                        <span>Cadastrar Funcionário</span>
                    </a></li>
                    <li><a href="../criar/criar_produto.php">
                        <i class="fas fa-box"></i>
                        <span>Cadastrar Produto</span>
                    </a></li>
                </ul>
            </li>

            <!-- Visualizar -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-eye"></i>
                    <span class="menu-text">Visualizar</span>
                    <i class="dropdown-arrow fas fa-chevron-right"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="../visualizar/visualizar_cliente.php">
                        <i class="fas fa-users"></i>
                        <span>Visualizar Cliente</span>
                    </a></li>
                    <li><a href="../visualizar/visualizar_fornecedor.php">
                        <i class="fas fa-industry"></i>
                        <span>Visualizar Fornecedor</span>
                    </a></li>
                    <li><a href="../visualizar/visualizar_funcionario.php">
                        <i class="fas fa-id-badge"></i>
                        <span>Visualizar Funcionário</span>
                    </a></li>
                    <li><a href="../visualizar/visualizar_produto.php">
                        <i class="fas fa-inventory"></i>
                        <span>Visualizar Produto</span>
                    </a></li>
                </ul>
            </li>
        </ul>

        <!-- Logout -->
        <div class="sidebar-footer">
            <div class="logout-item">
                <form action="../../Login/logout.php" method="post">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-text">Sair</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

  
</body>
</html>
