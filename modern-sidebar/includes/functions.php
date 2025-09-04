<?php
/**
 * Common Functions
 * Hardware Inventory Management System
 */

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format currency for display
 */
function formatCurrency($amount) {
    return 'R$ ' . number_format($amount, 2, ',', '.');
}

/**
 * Format date for display
 */
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Format datetime for display
 */
function formatDateTime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

/**
 * Get stock status based on quantity and minimum level
 */
function getStockStatus($quantity, $minimum) {
    if ($quantity <= 0) {
        return ['status' => 'out_of_stock', 'class' => 'text-red-600', 'text' => 'Sem Estoque'];
    } elseif ($quantity <= $minimum) {
        return ['status' => 'low_stock', 'class' => 'text-yellow-600', 'text' => 'Estoque Baixo'];
    } else {
        return ['status' => 'in_stock', 'class' => 'text-green-600', 'text' => 'Em Estoque'];
    }
}

/**
 * Generate unique SKU
 */
function generateSKU($category_name, $item_name) {
    $category_code = strtoupper(substr($category_name, 0, 3));
    $item_code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $item_name), 0, 8));
    $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    return $category_code . '-' . $item_code . '-' . $random;
}

/**
 * Calculate total inventory value
 */
function calculateInventoryValue($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT SUM(quantity_in_stock * unit_price) as total_value 
            FROM hardware_items 
            WHERE status = 'active'
        ");
        $result = $stmt->fetch();
        return $result['total_value'] ?? 0;
    } catch(PDOException $e) {
        return 0;
    }
}

/**
 * Get low stock items count
 */
function getLowStockCount($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT COUNT(*) as count 
            FROM hardware_items 
            WHERE quantity_in_stock <= minimum_stock_level 
            AND status = 'active'
        ");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(PDOException $e) {
        return 0;
    }
}

/**
 * Get total items count
 */
function getTotalItemsCount($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT COUNT(*) as count 
            FROM hardware_items 
            WHERE status = 'active'
        ");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch(PDOException $e) {
        return 0;
    }
}

/**
 * Get recent stock movements
 */
function getRecentMovements($pdo, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT sm.*, hi.name as item_name, u.full_name as user_name
            FROM stock_movements sm
            LEFT JOIN hardware_items hi ON sm.hardware_item_id = hi.id
            LEFT JOIN users u ON sm.user_id = u.id
            ORDER BY sm.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        return [];
    }
}
?>
