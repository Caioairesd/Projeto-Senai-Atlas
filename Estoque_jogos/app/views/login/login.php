<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistema Atlas</title>
    <link rel="stylesheet" href="../../../public/assets/style.css" />
</head>

<body>

    <!-- Botão de alternância de tema -->
    <label class="switch">
        <span class="sun"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g fill="#ffd43b">
                    <circle r="5" cy="12" cx="12"></circle>
                    <path
                        d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z">
                    </path>
                </g>
            </svg></span>
        <span class="moon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                <path
                    d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z">
                </path>
            </svg></span>
        <input type="checkbox" class="input">
        <span class="slider"></span>
    </label>

    <!-- Tela dividida -->
    <div class="card-container">
        <div class="container">
            <div class="visual-side">
                <img src="../../../public/assets/images/Logo."" alt="Logo Atlas" class="logo" />
                <h1 class="slogan">Controle inteligente para estoques eficientes</h1>
            </div>

            <form action="" method="post" class="log-card" aria-label="Formulário de login">
                <p class="heading">Acesso ao Sistema Atlas</p>
                <p class="para">Faça login para continuar gerenciando o estoque da empresa</p>

                <div class="input-group">
                    <label for="username" class="text">Usuário</label>
                    <input id="username" name="username" class="input" type="text" placeholder="Ex: joao.silva" required
                        aria-label="Usuário" />

                    <label for="password" class="text">Senha</label>
                    <input id="password" name="password" class="input" type="password" placeholder="Digite a senha"
                        required aria-label="Senha" />
                </div>

                <div class="password-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember" class="label">Lembrar de mim</label>
                    </div>
                    <a href="#" class="forget-password">Esqueci a senha</a>
                </div>

                <button type="submit" class="btn" aria-label="Entrar no sistema Atlas">Login</button>
                <p class="no-account">Não tem conta? <a href="#" class="link">Solicite acesso</a></p>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>© 2025 Atlas Sistemas. Todos os direitos reservados.</p>
        <p>Versão 1.0.0</p>
    </footer>

    <script>
        document.querySelector('.switch input').addEventListener('change', function () {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>

</html>