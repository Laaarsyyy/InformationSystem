<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['order_id'] ?? 0);

if ($order_id > 0) {
    // Fetch the order and items
    $query = "
        SELECT o.id, o.customer_name, p.name AS product_name, pv.size, oi.quantity,
               (oi.quantity * p.price) AS total_price, o.order_date
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN product_variants pv ON oi.variant_id = pv.id
        JOIN products p ON pv.product_id = p.id
        WHERE o.id = $order_id
    ";

    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO archived_sales (order_id, customer_name, product_name, size, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssids",
            $row['id'],
            $row['customer_name'],
            $row['product_name'],
            $row['size'],
            $row['quantity'],
            $row['total_price'],
            $row['order_date']
        );
        $stmt->execute();
    }

    // Delete the archived order and related items
    $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
    $conn->query("DELETE FROM orders WHERE id = $order_id");

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
