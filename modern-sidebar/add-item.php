<?php
/**
 * Hardware Inventory Management System - Add Item
 * Form to add new hardware items
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get database connection
$pdo = getDBConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $supplier_id = intval($_POST['supplier_id']) ?: null;
    $sku = sanitizeInput($_POST['sku']);
    $barcode = sanitizeInput($_POST['barcode']);
    $unit_price = floatval($_POST['unit_price']);
    $quantity_in_stock = intval($_POST['quantity_in_stock']);
    $minimum_stock_level = intval($_POST['minimum_stock_level']);
    $location = sanitizeInput($_POST['location']);
    
    $errors = [];
    
    // Validation
    if (empty($name)) $errors[] = "Nome é obrigatório";
    if (empty($sku)) $errors[] = "SKU é obrigatório";
    if ($category_id <= 0) $errors[] = "Categoria é obrigatória";
    if ($unit_price < 0) $errors[] = "Preço deve ser positivo";
    if ($quantity_in_stock < 0) $errors[] = "Quantidade deve ser positiva";
    
    // Check if SKU already exists
    if (!empty($sku)) {
        $stmt = $pdo->prepare("SELECT id FROM hardware_items WHERE sku = ? AND id != ?");
        $stmt->execute([$sku, 0]);
        if ($stmt->fetch()) {
            $errors[] = "SKU já existe";
        }
    }
    
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO hardware_items (name, description, category_id, supplier_id, sku, barcode, unit_price, quantity_in_stock, minimum_stock_level, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $description, $category_id, $supplier_id, $sku, $barcode, $unit_price, $quantity_in_stock, $minimum_stock_level, $location]);
            
            $item_id = $pdo->lastInsertId();
            
            // Record stock movement
            if ($quantity_in_stock > 0) {
                $movement_sql = "INSERT INTO stock_movements (hardware_item_id, movement_type, quantity, previous_quantity, new_quantity, reason, user_id) VALUES (?, 'in', ?, 0, ?, 'Item inicial', 1)";
                $movement_stmt = $pdo->prepare($movement_sql);
                $movement_stmt->execute([$item_id, $quantity_in_stock, $quantity_in_stock]);
            }
            
            header('Location: inventory.php?success=added');
            exit;
        } catch(PDOException $e) {
            $errors[] = "Erro ao salvar item: " . $e->getMessage();
        }
    }
}

// Get categories and suppliers
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    $suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY name")->fetchAll();
} catch(PDOException $e) {
    $categories = [];
    $suppliers = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Item - Hardware Inventory</title>
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
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Adicionar Item</h2>
                    <p class="text-gray-600">Cadastre um novo item no inventário</p>
                </div>
                <a href="inventory.php" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>

            <!-- Error Messages -->
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

            <!-- Form -->
            <form method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Item *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                        <input type="text" name="sku" value="<?php echo htmlspecialchars($_POST['sku'] ?? ''); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Category and Supplier -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Categoria e Fornecedor</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="">Selecione uma categoria</option>
                            <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fornecedor</label>
                        <select name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Selecione um fornecedor</option>
                            <?php foreach($suppliers as $supplier): ?>
                            <option value="<?php echo $supplier['id']; ?>" <?php echo ($_POST['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Pricing and Stock -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preço e Estoque</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço Unitário (R$)</label>
                        <input type="number" name="unit_price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['unit_price'] ?? ''); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código de Barras</label>
                        <input type="text" name="barcode" value="<?php echo htmlspecialchars($_POST['barcode'] ?? ''); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade em Estoque</label>
                        <input type="number" name="quantity_in_stock" min="0" value="<?php echo htmlspecialchars($_POST['quantity_in_stock'] ?? '0'); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nível Mínimo de Estoque</label>
                        <input type="number" name="minimum_stock_level" min="0" value="<?php echo htmlspecialchars($_POST['minimum_stock_level'] ?? '5'); ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    
                    <!-- Location -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Localização</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Localização no Estoque</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" 
                               placeholder="Ex: Estoque A1, Prateleira 3" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="inventory.php" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Item
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

    <script src="assets/js/dashboard.js"></script>
</body>
</html>
