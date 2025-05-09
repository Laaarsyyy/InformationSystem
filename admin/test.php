<?php
include '../config.php';

$result = $conn->query("
    SELECT 
        o.id AS order_id,
        p.name AS product_name,
        p.price AS variant_price,
        oi.quantity,
        (oi.quantity * pv.price) AS line_total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN product_variants pv ON oi.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    WHERE DATE(o.order_date) = CURDATE()
");

while ($row = $result->fetch_assoc()) {
    echo "Order ID: {$row['order_id']} | Product: {$row['product_name']} | Price: ₱{$row['variant_price']} | Qty: {$row['quantity']} | Line Total: ₱{$row['line_total']}<br>";
}
?>