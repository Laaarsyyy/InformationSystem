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

        // Prepare statement to insert into order_items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, variant_id, quantity) VALUES (?, ?, ?)");

        for ($i = 0; $i < count($variant_ids); $i++) {
            $variant_id = $variant_ids[$i];
            $quantity = $quantities[$i];

            $stmt->bind_param("iii", $order_id, $variant_id, $quantity);
            $stmt->execute();

            // Also update the stock in product_variants table
            $update = $conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $update->bind_param("ii", $quantity, $variant_id);
            $update->execute();
            $update->close();
        }

        $stmt->close();
        $conn->commit();

        header("Location: Sales.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Transaction failed: " . $e->getMessage());
    }
}
?>