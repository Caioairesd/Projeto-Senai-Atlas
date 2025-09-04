<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"/>

    <nav class="sidebar">
        <ul class="menu">
            <!-- Cadastrar -->
            <li class="dropdown">
                <a href="#">Cadastrar</a>
                <ul class="dropdown-menu">
                    <li><a href="../criar/criar_cliente.php">Criar Cliente</a></li>
                    <li><a href="../criar/criar_fornecedor.php">Criar Fornecedor</a></li>
                    <li><a href="../criar/criar_funcionario.php">Criar Funcionário</a></li>
                    <li><a href="../criar/criar_produto.php">Criar Produto</a></li>
                </ul>
            </li>

            <!-- Editar -->
            <li class="dropdown">
                <a href="#">Editar</a>
                <ul class="dropdown-menu">
                    <li><a href="../editar/editar_cliente.php">Editar Cliente</a></li>
                    <li><a href="../editar/editar_fornecedor.php">Editar Fornecedor</a></li>
                    <li><a href="../editar/editar_funcionario.php">Editar Funcionário</a></li>
                    <li><a href="../editar/editar_produto.php">Editar Produto</a></li>
                </ul>
            </li>

            <!-- Excluir -->
            <li class="dropdown">
                <a href="#">Excluir</a>
                <ul class="dropdown-menu">
                    <li><a href="../excluir/excluir_cliente.php">Excluir Cliente</a></li>
                    <li><a href="../excluir/excluir_fornecedor.php">Excluir Fornecedor</a></li>
                    <li><a href="../excluir/excluir_funcionario.php">Excluir Funcionário</a></li>
                    <li><a href="../excluir/excluir_produto.php">Excluir Produto</a></li>
                </ul>
            </li>

            <!-- Visualizar -->
            <li class="dropdown">
                <a href="#">Visualizar</a>
                <ul class="dropdown-menu">
                    <li><a href="../visualizar/visualizar_cliente.php">Visualizar Cliente</a></li>
                    <li><a href="../visualizar/visualizar_fornecedor.php">Visualizar Fornecedor</a></li>
                    <li><a href="../visualizar/visualizar_funcionario.php">Visualizar Funcionário</a></li>
                    <li><a href="../visualizar/visualizar_produto.php">Visualizar Produto</a></li>
                    <li><a href="../visualizar/detalhes_cliente.php">Detalhes Cliente</a></li>
                    <li><a href="../visualizar/detalhes_fornecedor.php">Detalhes Fornecedor</a></li>
                    <li><a href="../visualizar/detalhes_produto.php">Detalhes Produto</a></li>
                </ul>
            </li>

            <!-- Logout -->
            <li class="logout-item">
                <form action="../Login/logout.php" method="post">
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
    </body>

</html>