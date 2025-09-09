<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistema Atlas</title>
    <link rel="stylesheet" href="../assets/style.css"/>
    <label class="switch">
      
    <!--dividida -->
    <div class="card-container">
        <div class="container">
            <div class="visual-side">
                <img src="../assets/images/atlas.png">
                <h1 class="slogan">Controle inteligente para estoques eficientes</h1>
            </div>

            <form action="processar_login.php" method="post" class="log-card" aria-label="formulário de login">
                <p class="para">Faça login para continuar gerenciando o estoque da empresa</p>

                <div class="input-group">
                    <label for="username" class="text">Usuário</label>
                    <input id="username" name="username" class="input" type="text" placeholder="Ex: joao.silva" required />

                    <label for="password" class="text">Senha</label>
                    <input id="password" name="password" class="input" type="password" placeholder="Digite a senha" required />
                </div>

                <div class="password-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember" class="label">Lembrar de mim</label>
                    </div>
                    <a href="../esqueci_senha/solicitar_recuperacao.php">Esqueci minha senha</a>
                </div>

                <button type="submit" class="btn">Login</button>
                <p class="no-account">Não tem conta? #Solicite acesso</a></p>
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