<?php
/**
 * User Management Page
 * Manage system users (Admin only)
 */

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require admin access
requireRole('admin');

$pdo = getDBConnection();
$current_user = getCurrentUser();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrf_token)) {
        $error_message = 'Token de segurança inválido.';
    } else {
        $action = $_POST['action'] ?? '';
        
        switch($action) {
            case 'add_user':
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $full_name = sanitizeInput($_POST['full_name']);
                $role = $_POST['role'];
                $password = $_POST['password'];
                
                $errors = [];
                
                if (empty($username)) $errors[] = "Usuário é obrigatório";
                if (empty($email)) $errors[] = "Email é obrigatório";
                if (empty($full_name)) $errors[] = "Nome completo é obrigatório";
                if (!in_array($role, ['admin', 'manager', 'employee'])) $errors[] = "Função inválida";
                if (strlen($password) < 6) $errors[] = "Senha deve ter pelo menos 6 caracteres";
                
                // Check if username/email already exists
                if (empty($errors)) {
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    if ($stmt->fetch()) {
                        $errors[] = "Usuário ou email já existe";
                    }
                }
                
                if (empty($errors)) {
                    try {
                        $password_hash = hashPassword($password);
                        $stmt = $pdo->prepare("INSERT INTO users (username, email, full_name, role, password_hash) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $email, $full_name, $role, $password_hash]);
                        $success_message = "Usuário criado com sucesso!";
                    } catch(PDOException $e) {
                        $error_message = "Erro ao criar usuário: " . $e->getMessage();
                    }
                }
                break;
                
            case 'edit_user':
                $user_id = intval($_POST['user_id']);
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $full_name = sanitizeInput($_POST['full_name']);
                $role = $_POST['role'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                if ($user_id > 0) {
                    try {
                        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, role = ?, is_active = ? WHERE id = ?");
                        $stmt->execute([$username, $email, $full_name, $role, $is_active, $user_id]);
                        $success_message = "Usuário atualizado com sucesso!";
                    } catch(PDOException $e) {
                        $error_message = "Erro ao atualizar usuário: " . $e->getMessage();
                    }
                }
                break;
                
            case 'reset_password':
                $user_id = intval($_POST['user_id']);
                $new_password = generateRandomPassword();
                
                if ($user_id > 0) {
                    try {
                        $password_hash = hashPassword($new_password);
                        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                        $stmt->execute([$password_hash, $user_id]);
                        $success_message = "Senha resetada! Nova senha: $new_password";
                    } catch(PDOException $e) {
                        $error_message = "Erro ao resetar senha: " . $e->getMessage();
                    }
                }
                break;
        }
    }
}

// Get all users
try {
    $users = $pdo->query("SELECT * FROM users ORDER BY full_name")->fetchAll();
} catch(PDOException $e) {
    $users = [];
}

$csrf_token = generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Hardware Inventory</title>
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
                        <span class="hidden md:block font-medium"><?php echo htmlspecialchars($current_user['full_name']); ?></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</a>
                    </div>
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
            <a href="reports.php" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chart-bar"></i>
                <span class="font-medium">Relatórios</span>
            </a>
            <a href="users.php" class="flex items-center space-x-3 px-4 py-3 text-white bg-cyan-600 rounded-lg">
                <i class="fas fa-users"></i>
                <span class="font-medium">Usuários</span>
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gerenciar Usuários</h2>
                    <p class="text-gray-600">Administre os usuários do sistema</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button onclick="openAddUserModal()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Usuário
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

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Função</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-cyan-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                            <div class="text-sm text-gray-500">@<?php echo htmlspecialchars($user['username']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php 
                                        switch($user['role']) {
                                            case 'admin': echo 'bg-red-100 text-red-800'; break;
                                            case 'manager': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'employee': echo 'bg-green-100 text-green-800'; break;
                                        }
                                        ?>">
                                        <?php 
                                        switch($user['role']) {
                                            case 'admin': echo 'Administrador'; break;
                                            case 'manager': echo 'Gerente'; break;
                                            case 'employee': echo 'Funcionário'; break;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $user['is_active'] ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo formatDate($user['created_at']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" 
                                                class="text-cyan-600 hover:text-cyan-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="resetPassword(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')" 
                                                class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <form id="userForm" method="POST">
                    <div class="p-6">
                        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 mb-4">Novo Usuário</h3>
                        
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" id="formAction" value="add_user">
                        <input type="hidden" name="user_id" id="userId">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                                <input type="text" name="full_name" id="userFullName" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Usuário *</label>
                                <input type="text" name="username" id="userUsername" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="userEmail" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Função *</label>
                                <select name="role" id="userRole" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="employee">Funcionário</option>
                                    <option value="manager">Gerente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            
                            <div id="passwordField">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Senha *</label>
                                <input type="password" name="password" id="userPassword" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            
                            <div id="activeField" class="hidden">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" id="userActive" class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                    <span class="ml-2 text-sm text-gray-700">Usuário ativo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 px-6 py-4 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
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

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <form method="POST">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-key text-yellow-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Resetar Senha</h3>
                        </div>
                        
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="hidden" name="action" value="reset_password">
                        <input type="hidden" name="user_id" id="resetUserId">
                        
                        <p class="text-gray-600 mb-4">Tem certeza que deseja resetar a senha do usuário <strong id="resetUserName"></strong>?</p>
                        <p class="text-sm text-yellow-600">Uma nova senha será gerada automaticamente.</p>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 px-6 py-4 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                            Resetar Senha
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
        function openAddUserModal() {
            document.getElementById('modalTitle').textContent = 'Novo Usuário';
            document.getElementById('formAction').value = 'add_user';
            document.getElementById('userId').value = '';
            document.getElementById('userFullName').value = '';
            document.getElementById('userUsername').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userRole').value = 'employee';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = true;
            document.getElementById('passwordField').classList.remove('hidden');
            document.getElementById('activeField').classList.add('hidden');
            document.getElementById('userModal').classList.remove('hidden');
        }

        function editUser(user) {
            document.getElementById('modalTitle').textContent = 'Editar Usuário';
            document.getElementById('formAction').value = 'edit_user';
            document.getElementById('userId').value = user.id;
            document.getElementById('userFullName').value = user.full_name;
            document.getElementById('userUsername').value = user.username;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role;
            document.getElementById('userPassword').required = false;
            document.getElementById('passwordField').classList.add('hidden');
            document.getElementById('activeField').classList.remove('hidden');
            document.getElementById('userActive').checked = user.is_active == 1;
            document.getElementById('userModal').classList.remove('hidden');
        }

        function resetPassword(userId, userName) {
            document.getElementById('resetUserId').value = userId;
            document.getElementById('resetUserName').textContent = userName;
            document.getElementById('resetPasswordModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUserModal();
                closeResetPasswordModal();
            }
        });
    </script>
</body>
</html>
