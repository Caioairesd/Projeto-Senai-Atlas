<?php
/**
 * Hardware Inventory Management System - Dashboard
 * Main dashboard page with inventory metrics and analytics
 */

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth_middleware.php';

// Get current user
$current_user = getCurrentUser();

// Get database connection
$pdo = getDBConnection();

// Get dashboard metrics
$totalItems = getTotalItemsCount($pdo);
$lowStockCount = getLowStockCount($pdo);
$inventoryValue = calculateInventoryValue($pdo);
$recentMovements = getRecentMovements($pdo, 5);

// Get category statistics
try {
    $categoryStats = $pdo->query("
        SELECT c.name, COUNT(hi.id) as item_count, 
               SUM(hi.quantity_in_stock * hi.unit_price) as category_value
        FROM categories c
        LEFT JOIN hardware_items hi ON c.id = hi.category_id AND hi.status = 'active'
        GROUP BY c.id, c.name
        ORDER BY category_value DESC
        LIMIT 6
    ")->fetchAll();
} catch(PDOException $e) {
    $categoryStats = [];
}

// Get monthly stock movements for chart
try {
    $monthlyMovements = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            movement_type,
            COUNT(*) as count
        FROM stock_movements 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY month, movement_type
        ORDER BY month ASC
    ")->fetchAll();
} catch(PDOException $e) {
    $monthlyMovements = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Inventory - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-40">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center space-x-4">
                <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-cyan-600 to-cyan-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-microchip text-white text-sm"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Hardware Inventory</h1>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                        <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-cyan-600 text-sm"></i>
                        </div>
                        <span class="hidden md:block font-medium"><?php echo htmlspecialchars($current_user['full_name']); ?></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="p-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($current_user['full_name']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($current_user['email']); ?></p>
                        </div>
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i>Perfil
                        </a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg border-r border-gray-200 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <nav class="p-4 space-y-2">
            <a href="index.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
                <i class="fas fa-tachometer-alt"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="inventory.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-boxes"></i>
                <span class="font-medium">Inventário</span>
            </a>
            <?php if (hasRole('manager')): ?>
            <a href="categories.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-tags"></i>
                <span class="font-medium">Categorias</span>
            </a>
            <a href="suppliers.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-truck"></i>
                <span class="font-medium">Fornecedores</span>
            </a>
            <?php endif; ?>
            <a href="movements.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-exchange-alt"></i>
                <span class="font-medium">Movimentações</span>
            </a>
            <?php if (hasRole('manager')): ?>
            <a href="reports.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chart-bar"></i>
                <span class="font-medium">Relatórios</span>
            </a>
            <?php endif; ?>
            <?php if (hasRole('admin')): ?>
            <a href="users.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-users"></i>
                <span class="font-medium">Usuários</span>
            </a>
            <?php endif; ?>
            <a href="settings.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-cog"></i>
                <span class="font-medium">Configurações</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 pt-16 min-h-screen">
        <div class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h2>
                <p class="text-gray-600">Visão geral do seu inventário de hardware</p>
            </div>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total de Itens</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($totalItems); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+12%</span>
                        <span class="text-gray-600 ml-2">vs mês anterior</span>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Estoque Baixo</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $lowStockCount; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-red-600 font-medium">+3</span>
                        <span class="text-gray-600 ml-2">itens críticos</span>
                    </div>
                </div>

                <!-- Inventory Value -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Valor do Estoque</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo formatCurrency($inventoryValue); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+8.2%</span>
                        <span class="text-gray-600 ml-2">crescimento</span>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Categorias Ativas</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo count($categoryStats); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-gray-600">Bem distribuído</span>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Stock Movements Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Movimentações Mensais</h3>
                    <canvas id="movementsChart" height="300"></canvas>
                </div>

                <!-- Category Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribuição por Categoria</h3>
                    <div class="space-y-4">
                        <?php foreach($categoryStats as $category): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-cyan-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $category['item_count']; ?> itens</div>
                                <div class="text-xs text-gray-600"><?php echo formatCurrency($category['category_value'] ?? 0); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Movimentações Recentes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($recentMovements as $movement): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($movement['item_name']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $movement['movement_type'] == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $movement['movement_type'] == 'in' ? 'Entrada' : 'Saída'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $movement['quantity']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($movement['user_name'] ?? 'Sistema'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo formatDateTime($movement['created_at']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script>
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Stock Movements Chart
        const ctx = document.getElementById('movementsChart').getContext('2d');
        const movementsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Entradas',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Saídas',
                    data: [8, 15, 12, 18, 16, 20],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
