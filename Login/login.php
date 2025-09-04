<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistema Atlas</title>
    <link rel="stylesheet" href="../assets/stylelogin.css" />
</head>

<body>
    <!-- Botão de tema -->
    <label class="switch">
        <input type="checkbox" class="input" id="toggle-theme">
        <span class="slider"></span>
    </label>

    <div class="card-container">
        <div class="container">
            <!-- Lado visual -->
            <div class="visual-side">
                <img src="../assets/images/Logo.png" alt="Logo Atlas">
            </div>

            <!-- Formulário -->
            <form action="processar_login.php" method="post" class="log-card" aria-label="formulário de login">
                <p class="para">Faça login para continuar gerenciando o estoque da empresa</p>

                <div class="input-group">
                    <label for="username" class="text">Usuário</label>
                    <input id="username" name="username" class="input" type="text" placeholder="Ex: joao.silva" required>

                    <label for="password" class="text">Senha</label>
                    <input id="password" name="password" class="input" type="password" placeholder="Digite a senha" required>
                </div>

                <div class="password-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" class="label">Lembrar de mim</label>
                    </div>
                    <a href="recuperar_senha.php" class="forgot-link">Esqueci a senha</a>
                </div>

                <button type="submit" class="btn">Login</button>
                <p class="no-account">
                    Não tem conta?
                    <a href="solicitar_acesso.php">Solicite acesso</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>© 2025 Atlas Sistemas. Todos os direitos reservados.</p>
        <p>Versão 1.0.0</p>
    </footer>

    <!-- Script de tema -->
    <script>
        document.getElementById('toggle-theme').addEventListener('change', function () {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>

</html>
