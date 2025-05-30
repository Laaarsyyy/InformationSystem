<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = $_POST['customer_name'];
    $variant_ids = $_POST['variant_ids'];
    $quantities = $_POST['quantities'];

    // Validate inputs
    if (empty($customer_name) || empty($variant_ids) || empty($quantities)) {
        die("Missing required fields.");
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name) VALUES (?)");
        $stmt->bind_param("s", $customer_name);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Prepare statements
        $insertItemStmt = $conn->prepare("INSERT INTO order_items (order_id, variant_id, quantity) VALUES (?, ?, ?)");
        $stockCheckStmt = $conn->prepare("
            SELECT pv.stock_quantity, p.name, pv.size 
            FROM product_variants pv 
            JOIN products p ON pv.product_id = p.id 
            WHERE pv.id = ?
        ");
        $updateStockStmt = $conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ?");

        for ($i = 0; $i < count($variant_ids); $i++) {
            $variant_id = $variant_ids[$i];
            $quantity = $quantities[$i];

            // Check stock
            $stockCheckStmt->bind_param("i", $variant_id);
            $stockCheckStmt->execute();
            $result = $stockCheckStmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row || $row['stock_quantity'] < $quantity) {
                $conn->rollback();
                echo json_encode([
                    'success' => false,
                    'error' => 'Insufficient stock for ' . $row['name'] . ' (' . $row['size'] . ')'
                ]);
                exit;
            }


            // Insert into order_items
            $insertItemStmt->bind_param("iii", $order_id, $variant_id, $quantity);
            $insertItemStmt->execute();

            // Update stock
            $updateStockStmt->bind_param("ii", $quantity, $variant_id);
            $updateStockStmt->execute();
        }

        // Cleanup
        $insertItemStmt->close();
        $stockCheckStmt->close();
        $updateStockStmt->close();

        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Transaction completed successfully'
        ]);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'error' => 'Transaction failed: ' . $e->getMessage()
        ]);
        exit;
    }
}
?>
