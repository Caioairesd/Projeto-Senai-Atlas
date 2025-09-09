<?php

require_once '../config/conexao.php';
include '../assets/sidebar.php';

echo '<link rel="stylesheet" href="../assets/style.css">';

switch ($_SESSION['perfil']) {

    case '1':
        include 'dashboard_admin.php';
        break;
    case '2':
        include 'dashboard_vendedor.php';
        break;
    case '3':
        include 'dashboard_estoquista.php';
        break;
    default:
        echo "<p>Permissão inválida.</p>";
}
?>
