<?php
session_start();
$conn = new mysqli("localhost", "root", "", "data");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Neverlonely</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            <img src="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png" alt="">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add New Product</h2>
            <form action="addProduct.php" method="POST" enctype="multipart/form-data">
                <label>Product Name
                    <input type="text" name="product_name" required>
                </label>
                
                <label class="inputProduct">Product Image
                    <input type="file" name="product_image" accept="image/*" id="inputProduct-btn">
                    <label for="inputProduct-btn">
                    <span class="material-icons">upload</span>Upload Image</label>
                </label>

                <div id="variant-container">
                    <div class="variant-group">
                        <label>Size and Stocks</label>
                        <input type="text" name="sizes[]" placeholder="Size (e.g. S)" required>
                        <input type="number" name="stocks[]" placeholder="Stock Quantity" required>
                        <button type="button" onclick="removeLastVariant()" class="removeSize-btn" style="display: none;">
                            <span class="material-icons">cancel</span></button>
                    </div>
                </div>

                <button type="button" onclick="addVariant()" class="addSize-btn">Add Another Size</button>
                <br><br>
                <button type="submit" class="modal-addProduct-btn">Add Product</button>
            </form>
        </div>
    </div>
</div>

<table class="products-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Size</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php
$products = $conn->query("SELECT * FROM products");
while ($product = $products->fetch_assoc()):
    $product_id = $product['id'];
    $variants = $conn->query("SELECT * FROM product_variants WHERE product_id = $product_id");
    $variant_count = $variants->num_rows;
    $first = true;
    $row_index = 0;
    while ($variant = $variants->fetch_assoc()):
        $row_index++;
?>
<tr>
    <?php if ($first): ?>
        <td rowspan="<?= $variant_count ?>">
            <strong><?= htmlspecialchars($product['name']) ?></strong><br>
            <?php if (!empty($product['image'])): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" 
                    alt="<?= htmlspecialchars($product['name']) ?>" 
                    style="width: 200px; height: auto; margin-top: 5px;">
            <?php endif; ?>
        </td>
    <?php $first = false; endif; ?>

    <td><?= htmlspecialchars($variant['size']) ?></td>
    <td><?= $variant['stock_quantity'] ?> pcs</td>
    <td>
        <button class="btn btn-edit" onclick="openEditModal(<?= $variant['id'] ?>)">Edit</button>
        <a href="deleteVariant.php?id=<?= $variant['id'] ?>" onclick="return confirm('Delete this size?')" class="btn btn-delete">Delete</a>
    </td>
</tr>

<?php 
    // Insert separator line after the last variant of the current product
    if ($row_index === $variant_count): ?>
        <tr>
            <td colspan="4" style="border-bottom: 2px solid #ccc;"></td>
        </tr>
<?php 
    endif;
endwhile; endwhile; ?>
</tbody>
</table>
</main>
<script src="/Neverlonely/Assets/javascript/script.js"></script>
</body>
</html>