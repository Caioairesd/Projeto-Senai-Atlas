<?php
/**
 * Hardware Inventory Management System - Reports & Analytics
 * Advanced analytics and reporting dashboard
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get database connection
$pdo = getDBConnection();

// Get date range from query parameters
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
$end_date = $_GET['end_date'] ?? date('Y-m-d'); // Today

// Get inventory summary
try {
    $inventory_summary = $pdo->query("
        SELECT 
            COUNT(*) as total_items,
            SUM(quantity_in_stock) as total_quantity,
            SUM(quantity_in_stock * unit_price) as total_value,
            COUNT(CASE WHEN quantity_in_stock <= minimum_stock_level THEN 1 END) as low_stock_items,
            COUNT(CASE WHEN quantity_in_stock = 0 THEN 1 END) as out_of_stock_items
        FROM hardware_items 
        WHERE status = 'active'
    ")->fetch();
} catch(PDOException $e) {
    $inventory_summary = [
        'total_items' => 0,
        'total_quantity' => 0,
        'total_value' => 0,
        'low_stock_items' => 0,
        'out_of_stock_items' => 0
    ];
}

// Get category performance
try {
    $category_performance = $pdo->query("
        SELECT 
            c.name,
            COUNT(hi.id) as item_count,
            SUM(hi.quantity_in_stock) as total_stock,
            SUM(hi.quantity_in_stock * hi.unit_price) as category_value,
            AVG(hi.unit_price) as avg_price
        FROM categories c
        LEFT JOIN hardware_items hi ON c.id = hi.category_id AND hi.status = 'active'
        GROUP BY c.id, c.name
        HAVING item_count > 0
        ORDER BY category_value DESC
    ")->fetchAll();
} catch(PDOException $e) {
    $category_performance = [];
}

// Get stock movements trend
try {
    $movements_trend = $pdo->prepare("
        SELECT 
            DATE(created_at) as date,
            movement_type,
            COUNT(*) as count,
            SUM(quantity) as total_quantity
        FROM stock_movements 
        WHERE created_at BETWEEN ? AND ?
        GROUP BY DATE(created_at), movement_type
        ORDER BY date ASC
    ");
    $movements_trend->execute([$start_date, $end_date]);
    $movements_data = $movements_trend->fetchAll();
} catch(PDOException $e) {
    $movements_data = [];
}

// Get top items by value
try {
    $top_items_by_value = $pdo->query("
        SELECT 
            hi.name,
            hi.sku,
            hi.quantity_in_stock,
            hi.unit_price,
            (hi.quantity_in_stock * hi.unit_price) as total_value,
            c.name as category_name
        FROM hardware_items hi
        LEFT JOIN categories c ON hi.category_id = c.id
        WHERE hi.status = 'active' AND hi.quantity_in_stock > 0
        ORDER BY total_value DESC
        LIMIT 10
    ")->fetchAll();
} catch(PDOException $e) {
    $top_items_by_value = [];
}

// Get low stock alerts
try {
    $low_stock_alerts = $pdo->query("
        SELECT 
            hi.name,
            hi.sku,
            hi.quantity_in_stock,
            hi.minimum_stock_level,
            hi.location,
            c.name as category_name
        FROM hardware_items hi
        LEFT JOIN categories c ON hi.category_id = c.id
        WHERE hi.status = 'active' 
        AND hi.quantity_in_stock <= hi.minimum_stock_level
        ORDER BY (hi.quantity_in_stock / NULLIF(hi.minimum_stock_level, 0)) ASC
        LIMIT 15
    ")->fetchAll();
} catch(PDOException $e) {
    $low_stock_alerts = [];
}

// Get recent high-value movements
try {
    $high_value_movements = $pdo->prepare("
        SELECT 
            sm.*,
            hi.name as item_name,
            hi.unit_price,
            (sm.quantity * hi.unit_price) as movement_value,
            u.full_name as user_name
        FROM stock_movements sm
        LEFT JOIN hardware_items hi ON sm.hardware_item_id = hi.id
        LEFT JOIN users u ON sm.user_id = u.id
        WHERE sm.created_at BETWEEN ? AND ?
        AND (sm.quantity * hi.unit_price) > 1000
        ORDER BY movement_value DESC, sm.created_at DESC
        LIMIT 10
    ");
    $high_value_movements->execute([$start_date, $end_date]);
    $high_value_movements_data = $high_value_movements->fetchAll();
} catch(PDOException $e) {
    $high_value_movements_data = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Hardware Inventory</title>
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
                <div class="relative">
                    <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                        <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-cyan-600 text-sm"></i>
                        </div>
                        <span class="hidden md:block font-medium">Admin</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg border-r border-gray-200 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <nav class="p-4 space-y-2">
            <a href="index.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-tachometer-alt"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="inventory.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-boxes"></i>
                <span class="font-medium">Inventário</span>
            </a>
            <a href="categories.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-tags"></i>
                <span class="font-medium">Categorias</span>
            </a>
            <a href="suppliers.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-truck"></i>
                <span class="font-medium">Fornecedores</span>
            </a>
            <a href="movements.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-exchange-alt"></i>
                <span class="font-medium">Movimentações</span>
            </a>
            <a href="reports.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
                <i class="fas fa-chart-bar"></i>
                <span class="font-medium">Relatórios</span>
            </a>
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Relatórios e Analytics</h2>
                    <p class="text-gray-600">Análise detalhada do seu inventário</p>
                </div>
                
                <!-- Date Range Filter -->
                <div class="mt-4 sm:mt-0">
                    <form method="GET" class="flex items-center space-x-2">
                        <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <span class="text-gray-500">até</span>
                        <input type="date" name="end_date" value="<?php echo $end_date; ?>" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-filter mr-1"></i>
                            Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total de Itens</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($inventory_summary['total_items']); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Quantidade Total</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($inventory_summary['total_quantity']); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cubes text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Valor Total</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo formatCurrency($inventory_summary['total_value']); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Estoque Baixo</p>
                            <p class="text-2xl font-bold text-yellow-600"><?php echo $inventory_summary['low_stock_items']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sem Estoque</p>
                            <p class="text-2xl font-bold text-red-600"><?php echo $inventory_summary['out_of_stock_items']; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Category Performance Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance por Categoria</h3>
                    <canvas id="categoryChart" height="300"></canvas>
                </div>

                <!-- Stock Movements Trend -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendência de Movimentações</h3>
                    <canvas id="movementsChart" height="300"></canvas>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Top Items by Value -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Itens por Valor</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estoque</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach($top_items_by_value as $item): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($item['sku']); ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo $item['quantity_in_stock']; ?></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo formatCurrency($item['total_value']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Alertas de Estoque Baixo</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Atual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mínimo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach($low_stock_alerts as $alert): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($alert['name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($alert['sku']); ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?php echo $alert['quantity_in_stock'] == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo $alert['quantity_in_stock']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo $alert['minimum_stock_level']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- High Value Movements -->
            <?php if (!empty($high_value_movements_data)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Movimentações de Alto Valor</h3>
                    <p class="text-sm text-gray-600">Movimentações acima de R$ 1.000 no período selecionado</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuário</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach($high_value_movements_data as $movement): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($movement['item_name']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $movement['movement_type'] == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $movement['movement_type'] == 'in' ? 'Entrada' : 'Saída'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $movement['quantity']; ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo formatCurrency($movement['movement_value']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?php echo formatDateTime($movement['created_at']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($movement['user_name'] ?? 'Sistema'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script src="assets/js/dashboard.js"></script>
    <script>
        // Category Performance Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = <?php echo json_encode($category_performance); ?>;
        
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    data: categoryData.map(item => parseFloat(item.category_value)),
                    backgroundColor: [
                        '#0891b2', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = new Intl.NumberFormat('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                }).format(context.parsed);
                                return context.label + ': ' + value;
                            }
                        }
                    }
                }
            }
        });

        // Stock Movements Trend Chart
        const movementsCtx = document.getElementById('movementsChart').getContext('2d');
        const movementsData = <?php echo json_encode($movements_data); ?>;
        
        // Process data for chart
        const dates = [...new Set(movementsData.map(item => item.date))].sort();
        const inData = dates.map(date => {
            const item = movementsData.find(m => m.date === date && m.movement_type === 'in');
            return item ? parseInt(item.total_quantity) : 0;
        });
        const outData = dates.map(date => {
            const item = movementsData.find(m => m.date === date && m.movement_type === 'out');
            return item ? parseInt(item.total_quantity) : 0;
        });

        const movementsChart = new Chart(movementsCtx, {
            type: 'line',
            data: {
                labels: dates.map(date => new Date(date).toLocaleDateString('pt-BR')),
                datasets: [{
                    label: 'Entradas',
                    data: inData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Saídas',
                    data: outData,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</body>
</html>
