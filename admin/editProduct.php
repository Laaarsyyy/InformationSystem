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

// Insert any new variants added
if (!empty($_POST['extra_sizes']) && !empty($_POST['extra_stocks'])) {
    $extra_sizes = $_POST['extra_sizes'];
    $extra_stocks = $_POST['extra_stocks'];

    $insert = $conn->prepare("INSERT INTO product_variants (product_id, size, stock_quantity) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($extra_sizes); $i++) {
        $size = $extra_sizes[$i];
        $stock = $extra_stocks[$i];

        if (!empty($size) && !empty($stock)) {
            $insert->bind_param("isi", $product_id, $size, $stock);
            $insert->execute();
        }
    }
}

header("Location: manageProducts.php");
exit;
?>
