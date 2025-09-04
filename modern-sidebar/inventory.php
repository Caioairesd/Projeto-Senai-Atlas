<?php
/**
 * Hardware Inventory Management System - Inventory Page
 * Display and manage hardware items
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get database connection
$pdo = getDBConnection();

// Handle search and filters
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$status_filter = $_GET['status'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$where_conditions = ["hi.status != 'deleted'"];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(hi.name LIKE :search OR hi.sku LIKE :search OR hi.description LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($category_filter)) {
    $where_conditions[] = "hi.category_id = :category";
    $params[':category'] = $category_filter;
}

if (!empty($status_filter)) {
    if ($status_filter === 'low_stock') {
        $where_conditions[] = "hi.quantity_in_stock <= hi.minimum_stock_level";
    } else {
        $where_conditions[] = "hi.status = :status";
        $params[':status'] = $status_filter;
    }
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
try {
    $count_sql = "
        SELECT COUNT(*) as total 
        FROM hardware_items hi 
        LEFT JOIN categories c ON hi.category_id = c.id 
        WHERE $where_clause
    ";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_items = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_items / $limit);
} catch(PDOException $e) {
    $total_items = 0;
    $total_pages = 1;
}

// Get hardware items
try {
    $sql = "
        SELECT hi.*, c.name as category_name, s.name as supplier_name
        FROM hardware_items hi
        LEFT JOIN categories c ON hi.category_id = c.id
        LEFT JOIN suppliers s ON hi.supplier_id = s.id
        WHERE $where_clause
        ORDER BY hi.name ASC
        LIMIT :limit OFFSET :offset
    ";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $hardware_items = $stmt->fetchAll();
} catch(PDOException $e) {
    $hardware_items = [];
}

// Get categories for filter
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
} catch(PDOException $e) {
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventário - Hardware Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <a href="inventory.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
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
            <a href="reports.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Inventário</h2>
                    <p class="text-gray-600">Gerencie seus itens de hardware</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="add-item.php" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar Item
                    </a>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Nome, SKU ou descrição..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Todas as categorias</option>
                            <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Todos os status</option>
                            <option value="active" <?php echo $status_filter == 'active' ? 'selected' : ''; ?>>Ativo</option>
                            <option value="low_stock" <?php echo $status_filter == 'low_stock' ? 'selected' : ''; ?>>Estoque Baixo</option>
                            <option value="out_of_stock" <?php echo $status_filter == 'out_of_stock' ? 'selected' : ''; ?>>Sem Estoque</option>
                            <option value="discontinued" <?php echo $status_filter == 'discontinued' ? 'selected' : ''; ?>>Descontinuado</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrar
                        </button>
                        <a href="inventory.php" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-600">
                    Mostrando <?php echo count($hardware_items); ?> de <?php echo $total_items; ?> itens
                </p>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Página <?php echo $page; ?> de <?php echo $total_pages; ?></span>
                </div>
            </div>

            <!-- Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <?php foreach($hardware_items as $item): 
                    $stock_status = getStockStatus($item['quantity_in_stock'], $item['minimum_stock_level']);
                ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($item['sku']); ?></p>
                        </div>
                        <div class="ml-2">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo $stock_status['class']; ?> bg-opacity-10">
                                <?php echo $stock_status['text']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Categoria:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Estoque:</span>
                            <span class="font-medium"><?php echo $item['quantity_in_stock']; ?> unidades</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Preço:</span>
                            <span class="font-medium"><?php echo formatCurrency($item['unit_price']); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Localização:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="edit-item.php?id=<?php echo $item['id']; ?>" 
                           class="flex-1 px-3 py-2 bg-cyan-50 text-cyan-600 text-sm font-medium rounded-lg hover:bg-cyan-100 transition-colors text-center">
                            <i class="fas fa-edit mr-1"></i>
                            Editar
                        </a>
                        <a href="item-details.php?id=<?php echo $item['id']; ?>" 
                           class="flex-1 px-3 py-2 bg-gray-50 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors text-center">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-center space-x-2">
                <?php if ($page > 1): ?>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" 
                   class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                   class="px-3 py-2 <?php echo $i == $page ? 'bg-cyan-600 text-white' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50'; ?> rounded-lg">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" 
                   class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
