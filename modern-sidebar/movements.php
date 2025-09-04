<?php
/**
 * Hardware Inventory Management System - Stock Movements
 * Track and manage all stock movements
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get database connection
$pdo = getDBConnection();

// Handle new movement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_movement') {
    $item_id = intval($_POST['item_id']);
    $movement_type = $_POST['movement_type'];
    $quantity = intval($_POST['quantity']);
    $reason = sanitizeInput($_POST['reason']);
    $reference = sanitizeInput($_POST['reference']);
    
    $errors = [];
    
    if ($item_id <= 0) $errors[] = "Item é obrigatório";
    if (!in_array($movement_type, ['in', 'out', 'adjustment'])) $errors[] = "Tipo de movimentação inválido";
    if ($quantity <= 0) $errors[] = "Quantidade deve ser positiva";
    
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Get current stock
            $stmt = $pdo->prepare("SELECT quantity_in_stock FROM hardware_items WHERE id = ?");
            $stmt->execute([$item_id]);
            $current_stock = $stmt->fetchColumn();
            
            if ($current_stock === false) {
                throw new Exception("Item não encontrado");
            }
            
            // Calculate new stock
            $new_stock = $current_stock;
            switch($movement_type) {
                case 'in':
                    $new_stock += $quantity;
                    break;
                case 'out':
                    if ($current_stock < $quantity) {
                        throw new Exception("Estoque insuficiente");
                    }
                    $new_stock -= $quantity;
                    break;
                case 'adjustment':
                    $new_stock = $quantity;
                    $quantity = abs($quantity - $current_stock);
                    break;
            }
            
            // Update stock
            $stmt = $pdo->prepare("UPDATE hardware_items SET quantity_in_stock = ? WHERE id = ?");
            $stmt->execute([$new_stock, $item_id]);
            
            // Record movement
            $stmt = $pdo->prepare("
                INSERT INTO stock_movements (hardware_item_id, movement_type, quantity, previous_quantity, new_quantity, reason, reference_number, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([$item_id, $movement_type, $quantity, $current_stock, $new_stock, $reason, $reference]);
            
            $pdo->commit();
            $success_message = "Movimentação registrada com sucesso!";
            
        } catch(Exception $e) {
            $pdo->rollBack();
            $errors[] = "Erro ao registrar movimentação: " . $e->getMessage();
        }
    }
}

// Get filters
$search = $_GET['search'] ?? '';
$movement_type_filter = $_GET['movement_type'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$where_conditions = ["1=1"];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(hi.name LIKE :search OR hi.sku LIKE :search OR sm.reason LIKE :search OR sm.reference_number LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($movement_type_filter)) {
    $where_conditions[] = "sm.movement_type = :movement_type";
    $params[':movement_type'] = $movement_type_filter;
}

if (!empty($date_from)) {
    $where_conditions[] = "DATE(sm.created_at) >= :date_from";
    $params[':date_from'] = $date_from;
}

if (!empty($date_to)) {
    $where_conditions[] = "DATE(sm.created_at) <= :date_to";
    $params[':date_to'] = $date_to;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count
try {
    $count_sql = "
        SELECT COUNT(*) as total 
        FROM stock_movements sm 
        LEFT JOIN hardware_items hi ON sm.hardware_item_id = hi.id 
        WHERE $where_clause
    ";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_movements = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_movements / $limit);
} catch(PDOException $e) {
    $total_movements = 0;
    $total_pages = 1;
}

// Get movements
try {
    $sql = "
        SELECT sm.*, hi.name as item_name, hi.sku, u.full_name as user_name
        FROM stock_movements sm
        LEFT JOIN hardware_items hi ON sm.hardware_item_id = hi.id
        LEFT JOIN users u ON sm.user_id = u.id
        WHERE $where_clause
        ORDER BY sm.created_at DESC
        LIMIT :limit OFFSET :offset
    ";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $movements = $stmt->fetchAll();
} catch(PDOException $e) {
    $movements = [];
}

// Get items for movement form
try {
    $items = $pdo->query("
        SELECT id, name, sku, quantity_in_stock 
        FROM hardware_items 
        WHERE status = 'active' 
        ORDER BY name
    ")->fetchAll();
} catch(PDOException $e) {
    $items = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentações - Hardware Inventory</title>
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
            <a href="movements.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Movimentações de Estoque</h2>
                    <p class="text-gray-600">Registre e acompanhe todas as movimentações</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button onclick="openMovementModal()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Movimentação
                    </button>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-400 mt-0.5 mr-3"></i>
                    <p class="text-sm text-green-800"><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Corrija os seguintes erros:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            <?php foreach($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Item, motivo ou referência..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select name="movement_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Todos os tipos</option>
                            <option value="in" <?php echo $movement_type_filter == 'in' ? 'selected' : ''; ?>>Entrada</option>
                            <option value="out" <?php echo $movement_type_filter == 'out' ? 'selected' : ''; ?>>Saída</option>
                            <option value="adjustment" <?php echo $movement_type_filter == 'adjustment' ? 'selected' : ''; ?>>Ajuste</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                        <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                        <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrar
                        </button>
                        <a href="movements.php" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-600">
                    Mostrando <?php echo count($movements); ?> de <?php echo $total_movements; ?> movimentações
                </p>
            </div>

            <!-- Movements Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($movements as $movement): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($movement['item_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($movement['sku']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php 
                                        switch($movement['movement_type']) {
                                            case 'in': echo 'bg-green-100 text-green-800'; break;
                                            case 'out': echo 'bg-red-100 text-red-800'; break;
                                            case 'adjustment': echo 'bg-blue-100 text-blue-800'; break;
                                        }
                                        ?>">
                                        <?php 
                                        switch($movement['movement_type']) {
                                            case 'in': echo 'Entrada'; break;
                                            case 'out': echo 'Saída'; break;
                                            case 'adjustment': echo 'Ajuste'; break;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $movement['quantity']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $movement['previous_quantity']; ?> → <?php echo $movement['new_quantity']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($movement['reason'] ?? 'N/A'); ?></div>
                                    <?php if (!empty($movement['reference_number'])): ?>
                                    <div class="text-sm text-gray-500">Ref: <?php echo htmlspecialchars($movement['reference_number']); ?></div>
                                    <?php endif; ?>
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

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-center space-x-2 mt-6">
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

    <!-- Movement Modal -->
    <div id="movementModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <form method="POST">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Nova Movimentação</h3>
                        
                        <input type="hidden" name="action" value="add_movement">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item *</label>
                                <select name="item_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">Selecione um item</option>
                                    <?php foreach($items as $item): ?>
                                    <option value="<?php echo $item['id']; ?>">
                                        <?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['sku']; ?>) - Estoque: <?php echo $item['quantity_in_stock']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                                <select name="movement_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">Selecione o tipo</option>
                                    <option value="in">Entrada</option>
                                    <option value="out">Saída</option>
                                    <option value="adjustment">Ajuste</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade *</label>
                                <input type="number" name="quantity" min="1" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo</label>
                                <input type="text" name="reason" placeholder="Ex: Compra, Venda, Correção..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Referência</label>
                                <input type="text" name="reference" placeholder="Ex: NF-001, PO-123..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 px-6 py-4 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeMovementModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script src="assets/js/dashboard.js"></script>
    <script>
        function openMovementModal() {
            document.getElementById('movementModal').classList.remove('hidden');
        }

        function closeMovementModal() {
            document.getElementById('movementModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMovementModal();
            }
        });
    </script>
</body>
</html>
