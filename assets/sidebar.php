```html
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
    <!-- Botão de alternância do tema (flutuante) -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Alternar tema">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>

    <!-- Sidebar de navegação lateral -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-cube"></i> <!-- Ícone do sistema -->
                <span class="logo-text">Atlas</span> <!-- Nome do sistema -->
            </div>
        </div>

        <!-- Menu principal de navegação -->
        <ul class="menu">
            <!-- Seção Cadastrar (dropdown) -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-user-plus"></i> <!-- Ícone de cadastro -->
                    <span class="menu-text">Cadastrar</span> <!-- Texto do menu -->
                    <i class="dropdown-arrow fas fa-chevron-right"></i> <!-- Seta indicadora de dropdown -->
                </a>
                <!-- Submenu de Cadastrar -->
                <ul class="dropdown-menu">
                    <li><a href="../criar/criar_cliente.php">
                            <i class="fas fa-user-circle"></i> <!-- Ícone de cliente -->
                            <span>Cadastrar Cliente</span> <!-- Link para cadastro de cliente -->
                        </a></li>
                    <li><a href="../criar/criar_fornecedor.php">
                            <i class="fas fa-truck-loading"></i> <!-- Ícone de fornecedor -->
                            <span>Cadastrar Fornecedor</span> <!-- Link para cadastro de fornecedor -->
                        </a></li>
                    <li><a href="../criar/criar_funcionario.php">
                            <i class="fas fa-user-tie"></i> <!-- Ícone de funcionário -->
                            <span>Cadastrar Funcionário</span> <!-- Link para cadastro de funcionário -->
                        </a></li>
                    <li><a href="../criar/criar_produto.php">
                            <i class="fas fa-box-open"></i> <!-- Ícone de produto -->
                            <span>Cadastrar Produto</span> <!-- Link para cadastro de produto -->
                        </a></li>
                </ul>
            </li>

            <!-- Seção Visualizar (dropdown) -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-search"></i> <!-- Ícone de visualização -->
                    <span class="menu-text">Visualizar</span> <!-- Texto do menu -->
                    <i class="dropdown-arrow fas fa-chevron-right"></i> <!-- Seta indicadora de dropdown -->
                </a>
                <!-- Submenu de Visualizar -->
                <ul class="dropdown-menu">
                    <li><a href="../visualizar/visualizar_cliente.php">
                            <i class="fas fa-users"></i> <!-- Ícone de clientes -->
                            <span>Visualizar Cliente</span> <!-- Link para visualização de clientes -->
                        </a></li>
                    <li><a href="../visualizar/visualizar_fornecedor.php">
                            <i class="fas fa-industry"></i> <!-- Ícone de fornecedores -->
                            <span>Visualizar Fornecedor</span> <!-- Link para visualização de fornecedores -->
                        </a></li>
                    <li><a href="../visualizar/visualizar_funcionario.php">
                            <i class="fas fa-id-badge"></i> <!-- Ícone de funcionários -->
                            <span>Visualizar Funcionário</span> <!-- Link para visualização de funcionários -->
                        </a></li>
                    <li><a href="../visualizar/visualizar_produto.php">
                            <i class="fas fa-boxes"></i> <!-- Ícone de produtos -->
                            <span>Visualizar Produto</span> <!-- Link para visualização de produtos -->
                        </a></li>
                </ul>
            </li>

            <!-- Seção Estoque (dropdown) -->
            <li class="dropdown">
                <a href="#" class="menu-item">
                    <i class="menu-icon fas fa-warehouse"></i> <!-- Ícone de estoque -->
                    <span class="menu-text">Estoque</span> <!-- Texto do menu -->
                    <i class="dropdown-arrow fas fa-chevron-right"></i> <!-- Seta indicadora de dropdown -->
                </a>
                <!-- Submenu de Estoque -->
                <ul class="dropdown-menu">
                    <li><a href="../estoque/estoque_entrada.php">
                            <i class="fas fa-arrow-down"></i> <!-- Ícone de entrada -->
                            <span>Entrada de Estoque</span> <!-- Link para entrada de estoque -->
                        </a></li>
                    <li><a href="../estoque/estoque_historico.php">
                            <i class="fas fa-history"></i> <!-- Ícone de histórico -->
                            <span>Histórico</span> <!-- Link para histórico de estoque -->
                        </a></li>
                    <li><a href="../estoque/estoque_saida.php">
                            <i class="fas fa-arrow-up"></i> <!-- Ícone de saída -->
                            <span>Saída de Estoque</span> <!-- Link para saída de estoque -->
                        </a></li>
                    <li><a href="../estoque/estoque_geral.php">
                            <i class="fas fa-clipboard-list"></i> <!-- Ícone de lista -->
                            <span>Estoque Geral</span> <!-- Link para estoque geral -->
                        </a></li>
                    <li><a href="../estoque/pedidos_lista.php">
                            <i class="fas fa-shopping-cart"></i> <!-- Ícone de pedidos -->
                            <span>Lista de Pedidos</span> <!-- Link para lista de pedidos -->
                        </a></li>
                </ul>
            </li>

            <!-- Seção Dashboard (link direto) -->
            <li>
                <a href="../dashboard/dashboard.php" class="menu-item">
                    <i class="menu-icon fas fa-chart-line"></i> <!-- Ícone de dashboard -->
                    <span class="menu-text">Dashboard</span> <!-- Link para dashboard -->
                </a>
            </li>

        <!-- Rodapé da sidebar -->
        <div class="sidebar-footer">
            <div class="logout-item">
                <form action="../Login/logout.php" method="post"> <!-- Formulário de logout -->
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> <!-- Ícone de logout -->
                        <span class="menu-text">Sair</span> <!-- Texto do botão de logout -->
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <script>
        // Função para alternar entre temas claro e escuro
        function toggleTheme() {
            const html = document.documentElement; // Elemento raiz HTML
            const themeIcon = document.getElementById('theme-icon'); // Ícone do tema
            const currentTheme = html.getAttribute('data-theme'); // Tema atual

            // Alterna entre os temas
            if (currentTheme === 'light') {
                html.setAttribute('data-theme', 'dark'); // Aplica tema escuro
                themeIcon.className = 'fas fa-sun'; // Muda ícone para sol
                localStorage.setItem('theme', 'dark'); // Salva preferência no localStorage
            } else {
                html.setAttribute('data-theme', 'light'); // Aplica tema claro
                themeIcon.className = 'fas fa-moon'; // Muda ícone para lua
                localStorage.setItem('theme', 'light'); // Salva preferência no localStorage
            }
        }

        // Carrega o tema salvo ao inicializar a página
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light'; // Obtém tema salvo ou usa 'light' como padrão
            const html = document.documentElement; // Elemento raiz HTML
            const themeIcon = document.getElementById('theme-icon'); // Ícone do tema

            html.setAttribute('data-theme', savedTheme); // Aplica o tema salvo
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon'; // Configura o ícone conforme o tema
        });

        // Adiciona animação de fade-in aos elementos de conteúdo
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.content-wrapper, .form-wrapper, .table-wrapper, .details-wrapper');
            elements.forEach(el => el.classList.add('fade-in')); // Adiciona classe de animação
        });
    </script>
</body>

</html>
