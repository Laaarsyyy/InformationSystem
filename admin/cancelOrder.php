<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['order_id'] ?? 0);

if ($order_id > 0) {
    // Get all items in the order
    $query = "SELECT variant_id, quantity FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Restore stock for each variant
    while ($row = $result->fetch_assoc()) {
        $variant_id = $row['variant_id'];
        $quantity = $row['quantity'];

        $update = $conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $variant_id);
        $update->execute();
    }

    // Delete the order items and the order itself
    $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
    $conn->query("DELETE FROM orders WHERE id = $order_id");

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
}
?>
