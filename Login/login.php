<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistema Atlas</title>
    <link rel="stylesheet" href="../assets/style.css"/> <!-- Link para o CSS personalizado -->
    
    <!-- Switch de tema (modo claro/escuro) -->
    <label class="switch">
        <!-- Input checkbox para alternar entre temas -->
        <input type="checkbox" class="input">
        <span class="slider"></span>
    </label>
</head>

<body>
    <!-- Container principal do card de login -->
    <div class="card-container">
        <div class="container">
            <!-- Lado visual com logo e slogan -->
            <div class="visual-side">
                <img src="../assets/images/atlas.jpg" alt="Logo do Sistema Atlas"> <!-- Logo da empresa -->
                <h1 class="slogan">Controle inteligente para estoques eficientes</h1> <!-- Slogan -->
            </div>

            <!-- Formulário de login -->
            <form action="processar_login.php" method="post" class="log-card" aria-label="formulário de login">
                <p class="para">Faça login para continuar gerenciando o estoque da empresa</p> <!-- Texto introdutório -->

                <!-- Grupo de campos de entrada -->
                <div class="input-group">
                    <label for="username" class="text">Usuário</label> <!-- Rótulo do campo de usuário -->
                    <input id="username" name="username" class="input" type="text" placeholder="Ex: joao.silva" required /> <!-- Campo de usuário -->

                    <label for="password" class="text">Senha</label> <!-- Rótulo do campo de senha -->
                    <input id="password" name="password" class="input" type="password" placeholder="Digite a senha" required /> <!-- Campo de senha -->
                </div>

                <!-- Grupo para opções de senha -->
                <div class="password-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" /> <!-- Checkbox "Lembrar de mim" -->
                        <label for="remember" class="label">Lembrar de mim</label> <!-- Rótulo do checkbox -->
                    </div>
                    <a href="../esqueci_senha/solicitar_recuperacao.php">Esqueci minha senha</a> <!-- Link para recuperação de senha -->
                </div>

                <button type="submit" class="btn">Login</button> <!-- Botão de submit -->
                
                <!-- Mensagem para usuários sem conta -->
                <p class="no-account">Não tem conta? #Solicite acesso</p> <!-- Nota: O # provavelmente deveria ser um link -->
            </form>
        </div>
    </div>

    <!-- Rodapé da página -->
    <footer class="footer">
        <p>© 2025 Atlas Sistemas. Todos os direitos reservados.</p> <!-- Direitos autorais -->
        <p>Versão 1.0.0</p> <!-- Versão do sistema -->
    </footer>

    <!-- Script para alternar entre temas claro e escuro -->
    <script>
        document.querySelector('.switch input').addEventListener('change', function () {
            document.body.classList.toggle('dark-mode'); // Alterna a classe dark-mode no body
        });
    </script>
</body>
</html>
