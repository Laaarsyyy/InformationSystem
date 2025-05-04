<?php
session_start();
$conn = new mysqli("localhost", "root", "", "data");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Neverlonely</title>
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">
    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">
</head>
<body>
<?php include 'sidebar.php'; ?>

<main class="main-content">
    <div class="container">
        <h1>Manage Products</h1>
        <a href="addProduct.php" class="add-product-btn"> Add New Product</a>
    </div>
    <table class="products-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Variants (Size - Stock)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = $conn->query("SELECT * FROM products");
                while ($product = $products->fetch_assoc()):
                    $product_id = $product['id'];
                    $variants = $conn->query("SELECT * FROM product_variants WHERE product_id = $product_id");
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>
                        <?php while ($v = $variants->fetch_assoc()): ?>
                            <?= htmlspecialchars($v['size']) ?> - <?= $v['stock_quantity'] ?> pcs<br>
                        <?php endwhile; ?>
                    </td>
                    <td>
                        <a href="editProduct.php?id=<?= $product_id ?>" class="btn btn-edit">Edit</a>
                        <a href="deleteProduct.php?id=<?= $product_id ?>" onclick="return confirm('Delete this product?')" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
</main>

</body>
</html>