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
    <!-- Botão de alternância do tema -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Alternar tema">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>

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
                    <i class="menu-icon fas fa-user-plus"></i>
                    <span class="menu-text">Cadastrar</span>
                    <i class="dropdown-arrow fas fa-chevron-right"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="../criar/criar_cliente.php">
                            <i class="fas fa-user-circle"></i>
                            <span>Cadastrar Cliente</span>
                        </a></li>
                    <li><a href="../criar/criar_fornecedor.php">
                            <i class="fas fa-truck-loading"></i>
                            <span>Cadastrar Fornecedor</span>
                        </a></li>
                    <li><a href="../criar/criar_funcionario.php">
                            <i class="fas fa-user-tie"></i>
                            <span>Cadastrar Funcionário</span>
                        </a></li>
                    <li><a href="../criar/criar_produto.php">
                            <i class="fas fa-box-open"></i>
                            <span>Cadastrar Produto</span>
                        </a></li>
                </ul>
            </li>

            <!-- Visualizar -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-search"></i>
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
                            <i class="fas fa-boxes"></i>
                            <span>Visualizar Produto</span>
                        </a></li>
                </ul>
            </li>

            <!-- Estoque -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-warehouse"></i>
                    <span class="menu-text">Estoque</span>
                    <i class="dropdown-arrow fas fa-chevron-right"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="../estoque/estoque_entrada.php">
                            <i class="fas fa-arrow-down"></i>
                            <span>Entrada de Estoque</span>
                        </a></li>
                    <li><a href="../estoque/estoque_historico.php">
                            <i class="fas fa-history"></i>
                            <span>Histórico</span>
                        </a></li>
                    <li><a href="../estoque/estoque_saida.php">
                            <i class="fas fa-arrow-up"></i>
                            <span>Saída de Estoque</span>
                        </a></li>
                    <li><a href="../estoque/estoque_geral.php">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Estoque Geral</span>
                        </a></li>
                    <li><a href="../estoque/pedidos_lista.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Lista de Pedidos</span>
                        </a></li>
                </ul>
            </li>

            <!-- Dashboard -->
            <li>
                <a href="../dashboard/dashboard.php" class="menu-item">
                    <i class="menu-icon fas fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                
            </li>

      

 

        <!-- Footer da sidebar -->
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
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');

            html.setAttribute('data-theme', savedTheme);
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });

        // Adicionar animação de fade-in aos elementos
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.content-wrapper, .form-wrapper, .table-wrapper, .details-wrapper');
            elements.forEach(el => el.classList.add('fade-in'));
        });
    </script>
</body>

</html>