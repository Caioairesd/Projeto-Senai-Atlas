<?php
/**
 * Logout Handler
 * Destroy user session and redirect to login
 */

require_once 'includes/auth.php';

logoutUser();
header('Location: login.php?message=logged_out');
exit;
?>
