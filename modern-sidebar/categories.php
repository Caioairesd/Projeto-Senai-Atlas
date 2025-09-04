<?php
/**
 * Hardware Inventory Management System - Categories Management
 * Manage hardware categories
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get database connection
$pdo = getDBConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = sanitizeInput($_POST['name']);
                $description = sanitizeInput($_POST['description']);
                $icon = sanitizeInput($_POST['icon']);
                
                if (!empty($name)) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO categories (name, description, icon) VALUES (?, ?, ?)");
                        $stmt->execute([$name, $description, $icon]);
                        $success_message = "Categoria adicionada com sucesso!";
                    } catch(PDOException $e) {
                        $error_message = "Erro ao adicionar categoria: " . $e->getMessage();
                    }
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $name = sanitizeInput($_POST['name']);
                $description = sanitizeInput($_POST['description']);
                $icon = sanitizeInput($_POST['icon']);
                
                if (!empty($name) && $id > 0) {
                    try {
                        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ?, icon = ? WHERE id = ?");
                        $stmt->execute([$name, $description, $icon, $id]);
                        $success_message = "Categoria atualizada com sucesso!";
                    } catch(PDOException $e) {
                        $error_message = "Erro ao atualizar categoria: " . $e->getMessage();
                    }
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                if ($id > 0) {
                    try {
                        // Check if category has items
                        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM hardware_items WHERE category_id = ?");
                        $stmt->execute([$id]);
                        $count = $stmt->fetch()['count'];
                        
                        if ($count > 0) {
                            $error_message = "Não é possível excluir categoria com itens associados.";
                        } else {
                            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                            $stmt->execute([$id]);
                            $success_message = "Categoria excluída com sucesso!";
                        }
                    } catch(PDOException $e) {
                        $error_message = "Erro ao excluir categoria: " . $e->getMessage();
                    }
                }
                break;
        }
    }
}

// Get categories with item counts
try {
    $categories = $pdo->query("
        SELECT c.*, COUNT(hi.id) as item_count 
        FROM categories c 
        LEFT JOIN hardware_items hi ON c.id = hi.category_id AND hi.status = 'active'
        GROUP BY c.id 
        ORDER BY c.name
    ")->fetchAll();
} catch(PDOException $e) {
    $categories = [];
}

// Available icons
$available_icons = [
    'cpu' => 'fas fa-microchip',
    'memory' => 'fas fa-memory',
    'gpu' => 'fas fa-tv',
    'storage' => 'fas fa-hdd',
    'motherboard' => 'fas fa-server',
    'power' => 'fas fa-plug',
    'case' => 'fas fa-desktop',
    'peripheral' => 'fas fa-keyboard',
    'network' => 'fas fa-network-wired',
    'cooling' => 'fas fa-fan'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Hardware Inventory</title>
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
            <a href="categories.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Categorias</h2>
                    <p class="text-gray-600">Gerencie as categorias de hardware</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button onclick="openAddModal()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Categoria
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

            <?php if (isset($error_message)): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                    <p class="text-sm text-red-800"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($categories as $category): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                                <i class="<?php echo $available_icons[$category['icon']] ?? 'fas fa-tag'; ?> text-cyan-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo $category['item_count']; ?> itens</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <button onclick="editCategory(<?php echo htmlspecialchars(json_encode($category)); ?>)" 
                                    class="p-2 text-gray-400 hover:text-cyan-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($category['item_count'] == 0): ?>
                            <button onclick="deleteCategory(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name']); ?>')" 
                                    class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($category['description'])): ?>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($category['description']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <form id="categoryForm" method="POST">
                    <div class="p-6">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 mb-4">Nova Categoria</h3>
                        
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="categoryId">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                                <input type="text" name="name" id="categoryName" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                                <textarea name="description" id="categoryDescription" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ícone</label>
                                <select name="icon" id="categoryIcon" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <?php foreach($available_icons as $key => $icon): ?>
                                    <option value="<?php echo $key; ?>"><?php echo ucfirst($key); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 px-6 py-4 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <form id="deleteForm" method="POST">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Confirmar Exclusão</h3>
                        </div>
                        
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        
                        <p class="text-gray-600 mb-4">Tem certeza que deseja excluir a categoria <strong id="deleteName"></strong>?</p>
                        <p class="text-sm text-red-600">Esta ação não pode ser desfeita.</p>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 px-6 py-4 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Excluir
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
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Nova Categoria';
            document.getElementById('formAction').value = 'add';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryName').value = '';
            document.getElementById('categoryDescription').value = '';
            document.getElementById('categoryIcon').value = 'cpu';
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function editCategory(category) {
            document.getElementById('modalTitle').textContent = 'Editar Categoria';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categoryDescription').value = category.description || '';
            document.getElementById('categoryIcon').value = category.icon || 'cpu';
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function deleteCategory(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
