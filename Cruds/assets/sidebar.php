<?php
// sidebar.php

$opcoes_menu = [
  "Funcionários" => [
    "../visualizar/visualizar_funcionario.php" => "Visualizar",
    "../editar/editar_funcionario.php" => "Editar"
  ],
  "Fornecedores" => [
    "../visualizar/visualizar_fornecedor.php" => "Visualizar",
    "../editar/editar_fornecedor.php" => "Editar"
  ],
  // Adicione outras categorias aqui
];
?>

<style>
  .sidebar-container {
    display: flex;
    height: 100vh;
    font-family: var(--font);
    background-color: var(--bg-light);
    color: var(--text);
  }

  .sidebar {
    width: 240px;
    background-color: var(--primary);
    color: white;
    padding: 20px;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  }

  .sidebar .logo {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
    color: white;
  }

  .sidebar .menu {
    list-style: none;
    padding: 0;
  }

  .sidebar .menu-item > a {
    display: block;
    padding: 12px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    cursor: pointer;
  }

  .sidebar .menu-item > a:hover {
    background-color: var(--primary-dark);
  }

  .sidebar .submenu {
    list-style: none;
    padding-left: 15px;
    margin-top: 5px;
    display: none;
  }

  .sidebar .submenu li a {
    display: block;
    padding: 8px;
    color: var(--bg-white);
    text-decoration: none;
    font-size: 14px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  .sidebar .submenu li a:hover {
    background-color: var(--accent);
    color: white;
  }

  .sidebar .logout {
    margin-top: auto;
  }

  .sidebar .logout button {
    width: 100%;
    padding: 10px;
    background-color: var(--danger);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .sidebar .logout button:hover {
    background-color: var(--danger-dark);
  }

  .main-content {
    flex: 1;
    padding: 40px;
    background-color: var(--bg-light);
  }
</style>

<div class="sidebar-container">
  <nav class="sidebar">
    <div class="logo">Painel</div>
    <ul class="menu">
      <?php foreach ($opcoes_menu as $categoria => $links): ?>
        <li class="menu-item">
          <a class="toggle-submenu"><?= htmlspecialchars($categoria) ?></a>
          <ul class="submenu">
            <?php foreach ($links as $href => $label): ?>
              <li><a href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($label) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
      <?php endforeach; ?>
      <li class="menu-item logout">
        <form action="../Login/logout.php" method="post">
          <button type="submit">🚪 Logout</button>
        </form>
      </li>
    </ul>
  </nav>

  <main class="main-content">
    <h1>Bem-vindo, Caio!</h1>
    <p>Este é o conteúdo principal da sua página.</p>
  </main>
</div>

<script>
  document.querySelectorAll('.toggle-submenu').forEach(item => {
    item.addEventListener('click', function () {
      const submenu = this.nextElementSibling;
      submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    });
  });
</script>
