<?php
/**
 * Authentication Middleware
 * Add authentication checks to existing pages
 */

require_once 'includes/auth.php';

// Add authentication to all pages except login
$current_page = basename($_SERVER['PHP_SELF']);
$public_pages = ['login.php', 'logout.php', 'forgot-password.php'];

if (!in_array($current_page, $public_pages)) {
    requireAuth();
    
    // Admin-only pages
    $admin_pages = ['users.php'];
    if (in_array($current_page, $admin_pages)) {
        requireRole('admin');
    }
    
    // Manager+ pages
    $manager_pages = ['reports.php', 'categories.php', 'suppliers.php'];
    if (in_array($current_page, $manager_pages)) {
        requireRole('manager');
    }
}
?>
