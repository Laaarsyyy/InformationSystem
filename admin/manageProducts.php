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
    <div class="manage-products-container">
        <h1>Manage Products</h1>
        <button class="add-product-btn" onclick="openModal()">Add New Product</button>
    </div>

    <div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add New Product</h2>
        <form action="addProduct.php" method="POST">
            <label>Product Name
                <input type="text" name="product_name" required>
            </label>

            <div id="variant-container">
                <div class="variant-group">
                    <label>Size and Stocks
                        <input type="text" name="sizes[]" placeholder="Size (e.g. S)" required>
                        <input type="number" name="stocks[]" placeholder="Stock Quantity" required>
                    </label>

                </div>
            </div>

            <button type="button" onclick="addVariant()">+ Add Another Size</button>
            <br><br>
            <button type="submit" class="modal-addProduct-btn">Add Product</button>
        </form>
    </div>
</div>

    <table class="products-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Variants (Size - Stock)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $products = $conn->query("SELECT * FROM products");
        while ($product = $products->fetch_assoc()):
            $product_id = $product['id'];
            $product_name = htmlspecialchars($product['name']);
            $variants = $conn->query("SELECT * FROM product_variants WHERE product_id = $product_id");
        ?>
        <tr>
            <td><?= $product_name ?></td>
            <td>
                <?php while ($v = $variants->fetch_assoc()): ?>
                    <span><?= htmlspecialchars($v['size']) ?> - <?= $v['stock_quantity'] ?> pcs</span><br>
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
<script src="/Neverlonely/Assets/javascript/script.js"></script>
</body>
</html>