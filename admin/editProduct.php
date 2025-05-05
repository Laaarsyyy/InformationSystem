<?php
require '../config.php';

// Update existing variant
$id = $_POST['variant_id'];
$size = $_POST['size'];
$stock = $_POST['stock_quantity'];
$product_id = $_POST['product_id'];

$stmt = $conn->prepare("UPDATE product_variants SET size = ?, stock_quantity = ? WHERE id = ?");
$stmt->bind_param("sii", $size, $stock, $id);
$stmt->execute();

// Insert any new variants
if (!empty($_POST['new_sizes']) && !empty($_POST['new_stocks'])) {
    $new_sizes = $_POST['new_sizes'];
    $new_stocks = $_POST['new_stocks'];

    $insert = $conn->prepare("INSERT INTO product_variants (product_id, size, stock_quantity) VALUES (?, ?, ?)");

    for ($i = 0; $i < count($new_sizes); $i++) {
        $s = $new_sizes[$i];
        $q = $new_stocks[$i];
        $insert->bind_param("isi", $product_id, $s, $q);
        $insert->execute();
    }
}

header("Location: manageProducts.php");
exit;