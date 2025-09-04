<?php
/**
 * API endpoint for dashboard metrics
 * Returns real-time metrics for dashboard updates
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';
require_once '../includes/functions.php';

try {
    $pdo = getDBConnection();
    
    // Get basic metrics
    $totalItems = getTotalItemsCount($pdo);
    $lowStockCount = getLowStockCount($pdo);
    $inventoryValue = calculateInventoryValue($pdo);
    
    // Get additional metrics
    $stmt = $pdo->query("
        SELECT 
            COUNT(CASE WHEN quantity_in_stock = 0 THEN 1 END) as out_of_stock,
            COUNT(CASE WHEN status = 'active' THEN 1 END) as active_items,
            AVG(unit_price) as avg_price
        FROM hardware_items
    ");
    $additional_metrics = $stmt->fetch();
    
    // Get recent activity count
    $stmt = $pdo->query("
        SELECT COUNT(*) as recent_movements 
        FROM stock_movements 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ");
    $recent_activity = $stmt->fetch();
    
    $response = [
        'success' => true,
        'data' => [
            'totalItems' => $totalItems,
            'lowStock' => $lowStockCount,
            'inventoryValue' => $inventoryValue,
            'outOfStock' => $additional_metrics['out_of_stock'],
            'activeItems' => $additional_metrics['active_items'],
            'avgPrice' => $additional_metrics['avg_price'],
            'recentMovements' => $recent_activity['recent_movements'],
            'lastUpdated' => date('Y-m-d H:i:s')
        ]
    ];
    
} catch(Exception $e) {
    $response = [
        'success' => false,
        'error' => 'Erro ao buscar métricas: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
