<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['product_name'];

    // Optional: add description handling if you add it later
    $sizes = $_POST['sizes'];
    $stocks = $_POST['stocks'];

    // Insert the product first
    $stmt = $conn->prepare("INSERT INTO products (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Insert the variants
    $stmt = $conn->prepare("INSERT INTO product_variants (product_id, size, stock_quantity) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $stock = $stocks[$i];
        $stmt->bind_param("isi", $product_id, $size, $stock);
        $stmt->execute();
    }
    $stmt->close();

    header("Location: manageProducts.php");
    exit();
}
?>