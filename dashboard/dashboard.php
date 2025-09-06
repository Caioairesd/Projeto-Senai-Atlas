<?php 
require_once '../config/conexao.php';
require_once '../assets/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Estoque de Jogos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">

            <!-- Main Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto">
                <!-- Header -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <i class="fas fa-bell text-gray-400 text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                            </div>
                            <div class="flex items-center">
                                <img src="/placeholder.svg?height=32&width=32" alt="Avatar" class="w-8 h-8 rounded-full">
                                <span class="ml-2 text-gray-700">Admin</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Dashboard Content -->
                <main class="p-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100">
                                    <i class="fas fa-gamepad text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total de Jogos</p>
                                    <p class="text-2xl font-semibold text-gray-900">1,247</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100">
                                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Vendas do Mês</p>
                                    <p class="text-2xl font-semibold text-gray-900">R$ 45.230</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                                    <p class="text-2xl font-semibold text-gray-900">23</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100">
                                    <i class="fas fa-users text-purple-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Clientes Ativos</p>
                                    <p class="text-2xl font-semibold text-gray-900">892</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Tables Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Sales Chart -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vendas dos Últimos 7 Dias</h3>
                            <div class="h-64 flex items-end justify-between space-x-2">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 120px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Seg</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 80px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Ter</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 160px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Qua</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 100px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Qui</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 140px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Sex</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 200px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Sáb</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 bg-blue-500 rounded-t" style="height: 180px;"></div>
                                    <span class="text-xs text-gray-600 mt-2">Dom</span>
                                </div>
                            </div>
                        </div>

                        <!-- Top Games -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jogos Mais Vendidos</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/placeholder.svg?height=40&width=40" alt="Game" class="w-10 h-10 rounded">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Cyberpunk 2077</p>
                                            <p class="text-xs text-gray-500">PC, PS5, Xbox</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">156 vendas</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/placeholder.svg?height=40&width=40" alt="Game" class="w-10 h-10 rounded">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">FIFA 24</p>
                                            <p class="text-xs text-gray-500">PC, PS5, Xbox</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">142 vendas</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/placeholder.svg?height=40&width=40" alt="Game" class="w-10 h-10 rounded">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">GTA V</p>
                                            <p class="text-xs text-gray-500">PC, PS5, Xbox</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">128 vendas</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/placeholder.svg?height=40&width=40" alt="Game" class="w-10 h-10 rounded">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Minecraft</p>
                                            <p class="text-xs text-gray-500">PC, Mobile</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">115 vendas</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity and Low Stock -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Activity -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Atividade Recente</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Nova venda: The Last of Us Part II</p>
                                        <p class="text-xs text-gray-500">2 minutos atrás</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Estoque atualizado: Call of Duty MW3</p>
                                        <p class="text-xs text-gray-500">15 minutos atrás</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Alerta: Estoque baixo - Spider-Man 2</p>
                                        <p class="text-xs text-gray-500">1 hora atrás</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Novo jogo adicionado: Baldur's Gate 3</p>
                                        <p class="text-xs text-gray-500">2 horas atrás</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alert -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Alertas de Estoque Baixo</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Spider-Man 2</p>
                                            <p class="text-xs text-gray-500">PS5</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-red-600">2 unidades</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Hogwarts Legacy</p>
                                            <p class="text-xs text-gray-500">PC</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-yellow-600">5 unidades</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Elden Ring</p>
                                            <p class="text-xs text-gray-500">Xbox Series X</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-yellow-600">7 unidades</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
</body>

</html>