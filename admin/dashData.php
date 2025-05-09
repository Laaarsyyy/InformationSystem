
<?php
include '../config.php';

$range = $_GET['range'] ?? 'day';

$condition = '';
switch ($range) {
    case 'week':
        $condition = "WHERE o.order_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        break;
    case 'month':
        $condition = "WHERE o.order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        break;
    case 'day':
    default:
        $condition = "WHERE DATE(o.order_date) = CURDATE()";
        break;
}

// Total sales
$salesQuery = $conn->query("
    SELECT SUM(oi.quantity) AS total_sales
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    $condition
");
$total_sales = $salesQuery->fetch_assoc()['total_sales'] ?? 0;

// Revenue
$revenueQuery = $conn->query("
    SELECT SUM(oi.quantity * p.price) AS total_revenue
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    JOIN orders o ON o.id = oi.order_id
    $condition
");

$total_revenue = $revenueQuery->fetch_assoc()['total_revenue'] ?? 0;

// Best-selling product
$topProductQuery = $conn->query("
    SELECT p.name, SUM(oi.quantity) AS total_sold
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN product_variants pv ON oi.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    $condition
    GROUP BY p.name
    ORDER BY total_sold DESC
    LIMIT 1
");

$top_product = $topProductQuery->num_rows > 0 ? $topProductQuery->fetch_assoc()['name'] : "None";

echo json_encode([
    'sales' => $total_sales,
    'revenue' => round((float)$total_revenue, 2),
    'top_product' => $top_product
]);
?>