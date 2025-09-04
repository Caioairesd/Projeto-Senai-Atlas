<?php
/**
 * API endpoint for stock analytics data
 * Returns analytics data for charts and reports
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';
require_once '../includes/functions.php';

$action = $_GET['action'] ?? '';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

try {
    $pdo = getDBConnection();
    $response = ['success' => true];
    
    switch($action) {
        case 'category_distribution':
            $stmt = $pdo->query("
                SELECT 
                    c.name,
                    COUNT(hi.id) as item_count,
                    SUM(hi.quantity_in_stock * hi.unit_price) as total_value
                FROM categories c
                LEFT JOIN hardware_items hi ON c.id = hi.category_id AND hi.status = 'active'
                GROUP BY c.id, c.name
                HAVING item_count > 0
                ORDER BY total_value DESC
            ");
            $response['data'] = $stmt->fetchAll();
            break;
            
        case 'stock_trend':
            $stmt = $pdo->prepare("
                SELECT 
                    DATE(created_at) as date,
                    movement_type,
                    SUM(quantity) as total_quantity,
                    COUNT(*) as movement_count
                FROM stock_movements 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY DATE(created_at), movement_type
                ORDER BY date ASC
            ");
            $stmt->execute([$start_date, $end_date]);
            $response['data'] = $stmt->fetchAll();
            break;
            
        case 'low_stock_prediction':
            // Predict items that will run out of stock in next 30 days
            $stmt = $pdo->query("
                SELECT 
                    hi.name,
                    hi.sku,
                    hi.quantity_in_stock,
                    hi.minimum_stock_level,
                    COALESCE(avg_usage.daily_usage, 0) as daily_usage,
                    CASE 
                        WHEN COALESCE(avg_usage.daily_usage, 0) > 0 
                        THEN FLOOR(hi.quantity_in_stock / avg_usage.daily_usage)
                        ELSE 999
                    END as days_until_empty
                FROM hardware_items hi
                LEFT JOIN (
                    SELECT 
                        hardware_item_id,
                        AVG(quantity) as daily_usage
                    FROM stock_movements 
                    WHERE movement_type = 'out' 
                    AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY hardware_item_id
                ) avg_usage ON hi.id = avg_usage.hardware_item_id
                WHERE hi.status = 'active'
                AND hi.quantity_in_stock > 0
                HAVING days_until_empty <= 30 AND days_until_empty > 0
                ORDER BY days_until_empty ASC
                LIMIT 20
            ");
            $response['data'] = $stmt->fetchAll();
            break;
            
        case 'value_analysis':
            $stmt = $pdo->query("
                SELECT 
                    'A' as category,
                    COUNT(*) as item_count,
                    SUM(quantity_in_stock * unit_price) as total_value
                FROM hardware_items 
                WHERE status = 'active' 
                AND (quantity_in_stock * unit_price) >= (
                    SELECT PERCENTILE_CONT(0.8) WITHIN GROUP (ORDER BY quantity_in_stock * unit_price)
                    FROM hardware_items WHERE status = 'active'
                )
                UNION ALL
                SELECT 
                    'B' as category,
                    COUNT(*) as item_count,
                    SUM(quantity_in_stock * unit_price) as total_value
                FROM hardware_items 
                WHERE status = 'active' 
                AND (quantity_in_stock * unit_price) < (
                    SELECT PERCENTILE_CONT(0.8) WITHIN GROUP (ORDER BY quantity_in_stock * unit_price)
                    FROM hardware_items WHERE status = 'active'
                )
                AND (quantity_in_stock * unit_price) >= (
                    SELECT PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY quantity_in_stock * unit_price)
                    FROM hardware_items WHERE status = 'active'
                )
                UNION ALL
                SELECT 
                    'C' as category,
                    COUNT(*) as item_count,
                    SUM(quantity_in_stock * unit_price) as total_value
                FROM hardware_items 
                WHERE status = 'active' 
                AND (quantity_in_stock * unit_price) < (
                    SELECT PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY quantity_in_stock * unit_price)
                    FROM hardware_items WHERE status = 'active'
                )
            ");
            $response['data'] = $stmt->fetchAll();
            break;
            
        case 'supplier_performance':
            $stmt = $pdo->query("
                SELECT 
                    s.name as supplier_name,
                    COUNT(hi.id) as item_count,
                    SUM(hi.quantity_in_stock) as total_stock,
                    SUM(hi.quantity_in_stock * hi.unit_price) as total_value,
                    AVG(hi.unit_price) as avg_price
                FROM suppliers s
                LEFT JOIN hardware_items hi ON s.id = hi.supplier_id AND hi.status = 'active'
                GROUP BY s.id, s.name
                HAVING item_count > 0
                ORDER BY total_value DESC
            ");
            $response['data'] = $stmt->fetchAll();
            break;
            
        default:
            $response = [
                'success' => false,
                'error' => 'Ação não especificada ou inválida'
            ];
    }
    
} catch(Exception $e) {
    $response = [
        'success' => false,
        'error' => 'Erro ao buscar dados: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
