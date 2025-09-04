<?php
/**
 * Login Page
 * User authentication interface
 */

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Verify CSRF token
    if (!verifyCSRFToken($csrf_token)) {
        $error_message = 'Token de segurança inválido. Tente novamente.';
    } elseif (empty($username) || empty($password)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } else {
        if (loginUser($username, $password)) {
            $redirect_to = $_GET['redirect'] ?? 'index.php';
            header("Location: $redirect_to");
            exit;
        } else {
            $error_message = 'Usuário ou senha incorretos.';
        }
    }
}

$csrf_token = generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hardware Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body class="bg-gradient-to-br from-cyan-50 to-blue-100 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-cyan-600 to-cyan-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-microchip text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Hardware Inventory</h1>
            <p class="text-gray-600">Faça login para acessar o sistema</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
            <?php if (!empty($error_message)): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                    <p class="text-sm text-red-800"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i>
                        Usuário ou Email
                    </label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                           required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-colors"
                           placeholder="Digite seu usuário ou email">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>
                        Senha
                    </label>
                    <div class="relative">
                        <input type="password" name="password" required id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-colors pr-12"
                               placeholder="Digite sua senha">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                        <span class="ml-2 text-sm text-gray-600">Lembrar-me</span>
                    </label>
                    <a href="forgot-password.php" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                        Esqueceu a senha?
                    </a>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-cyan-600 to-cyan-700 text-white font-semibold py-3 px-4 rounded-lg hover:from-cyan-700 hover:to-cyan-800 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02]">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Entrar
                </button>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3 text-center">Credenciais de demonstração:</p>
                <div class="grid grid-cols-1 gap-2 text-xs">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <strong class="text-gray-700">Admin:</strong> admin / admin123
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <strong class="text-gray-700">Manager:</strong> manager / admin123
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <strong class="text-gray-700">Employee:</strong> employee / admin123
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">
                © 2024 Hardware Inventory System. Todos os direitos reservados.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.querySelector('input[name="username"]');
            if (usernameField) {
                usernameField.focus();
            }
        });
    </script>
</body>
</html>
