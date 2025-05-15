<?php
include '../config.php';

$sql = "
    SELECT 
        MONTH(order_date) AS month,
        SUM(oi.quantity) AS total_sold
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    GROUP BY month
";

$result = $conn->query($sql);

$data = array_fill(0, 12, 0); // Initialize months Jan-Dec with 0

while ($row = $result->fetch_assoc()) {
    $index = (int)$row['month'] - 1;
    $data[$index] = (int)$row['total_sold'];
}

echo json_encode($data);
?>
