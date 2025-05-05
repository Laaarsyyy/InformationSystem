<?php
include '../config.php'; // your DB connection file

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // First delete variants
    $conn->query("DELETE FROM product_variants WHERE product_id = $product_id");

    // Then delete the product
    $conn->query("DELETE FROM products WHERE id = $product_id");

    exit();
}