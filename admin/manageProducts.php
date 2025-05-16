<?php
require '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neverlonely</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/Neverlonely/Assets/css/sidebar.css">
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">
    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">
</head>
<body>
    <nav id="sidebar">
        <ul>
            <li>
                <span class="logo"><img src="/Neverlonely/Assets/NEVER LONELY DISTRESSED LOGO black.png" alt=""></span>
                <button onclick=toggleSidebar() id="toggle-btn">
                    <span class="material-icons">keyboard_double_arrow_right</span>
                </button>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'adminDash.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/adminDash.php">
                    <span class="material-icons">dashboard</span>
                    <span>Home</span>
                </a>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'manageProducts.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/manageProducts.php">
                    <span class="material-icons">inventory_2</span>
                    <span>Manage Products</span>
                </a>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'salesRecords.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/Sales.php">
                    <span class="material-icons">shopping_cart</span>
                    <span>Sales</span>
                </a>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'generateRecords.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/generateRecords.php">
                    <span class="material-icons">bar_chart</span>
                    <span>Generate Records</span>
                </a>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'manageUsers.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/manageUsers.php">
                    <span class="material-icons">group</span>
                    <span>Manage Users</span>
                </a>
            </li>

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'archives.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/archives.php">
            <span class="material-icons">archive
            </span>
                    <span>Archive</span>
                </a>
            </li>

            <li>
                <a onclick="openLogoutModal()">
                    <span class="material-icons">logout</span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="bodyLogo">
        <img src="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png" alt="">
    </div>

    <main>
        <div class="manage-products-container">
            <h1>Manage Products</h1>
            <button class="add-product-btn" onclick="openModal()">Add New Product</button>
        </div>
            <!-- ADD PRODUCT Modal-->
        <div id="productModal" class="modal">
            <div class="modal-content">
                <img src="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png" alt="">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add New Product</h2>
                <form action="addProduct.php" method="POST" enctype="multipart/form-data">
                    <label>Product Name
                        <input type="text" name="product_name" required style="border: 1px solid var(--text); background-color: transparent;">
                    </label>
                    
                    <label class="inputProduct">Product Image
                        <input type="file" name="product_image" accept="image/*" id="inputProduct-btn">
                        <label for="inputProduct-btn">
                        <span class="material-icons">upload</span>Upload Image</label>
                    </label>

                    <div class="priceCosting">
                        <label for="">Price</label>
                        <input type="number" name="price" required style="border: 1px solid var(--text); background-color: transparent;">

                        <label for="">Costing</label>
                        <input type="number" name="costing" required style="border: 1px solid var(--text); background-color: transparent;">
                    </div>

                    <div id="variant-container">
                        <div class="variant-group">
                            <label>Size and Stocks</label>
                            <input type="text" name="sizes[]" placeholder="Size (e.g. S)" required class="slim-input">
                            <input type="number" name="stocks[]" placeholder="Stock Quantity" required class="slim-input" style="margin-left: 10px;">
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
                                        style="width: 200px; height: auto; margin-top: 5px;"><br>
                                <?php endif; ?> 
                                <button onclick="showConfirmModal('deleteProduct.php?id=<?= $product_id ?>', 'Delete this product and all its variants?')" class="btn btn-deleteProduct">Delete Product</button>
                            </td>
                            
                        <?php $first = false; endif; ?>

                        <td id="size-<?= $variant['id'] ?>"><?= htmlspecialchars($variant['size']) ?></td>
                        <td id="stock-<?= $variant['id'] ?>" style="<?= $variant['stock_quantity'] == 0 ? 'color: red;' : '' ?>">
                                <?= $variant['stock_quantity'] == 0 ? 'Out of Stock' : $variant['stock_quantity'] . ' pcs' ?>
                        </td>
                        <td>
                            <button class="btn btn-edit" onclick="openEditModal(<?= $variant['id'] ?>, '<?= $variant['size'] ?>', <?= $variant['stock_quantity'] ?>, <?= $product['id'] ?>)">Edit</button>
                            <button onclick="showConfirmModal('deleteVariant.php?id=<?= $variant['id'] ?>', '<?= htmlspecialchars($variant['size']) ?>')" 
                                class="btn btn-delete">Delete</button>
                        </td>
                    </tr>

                    <?php 
                        if ($row_index === $variant_count): ?>
                            <tr>
                                <td colspan="4" style="border-bottom: 2px solid #ccc;"></td>
                            </tr>
                    <?php 
                        endif;
                    endwhile; endwhile; ?>
                </tbody>
        </table>    
        
        <!-- Delete Modal-->
        <div id="confirmDeleteModal" class="modal">
            <div class="deleteModal-content">
                <p id="confirmText">Are you sure you want to delete?</p>
                <div class="modal-actions">
                    <button id="confirmDeleteBtn" class="btn btn-delete">Yes, Delete</button>
                    <button onclick="closeConfirmModal()" class="btn btn-cancel">Cancel</button>
                </div>
            </div>
        </div>


        <!-- Edit Modal-->
        <div id="editModal" class="editModal" style="display: none;">
            <div class="editModal-content">
                <img src="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png" alt="">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Product</h2>
                <form id="editProductForm" method="POST" action="editProduct.php">
                    <input type="hidden" name="variant_id" id="editVariantId">
                    <input type="hidden" name="product_id" id="editProductId">

                    <!-- Existing variant being edited -->
                    <label>Size
                        <input type="text" name="size" id="editSize" required>
                    </label>
                    <label>Stock
                        <input type="number" name="stock_quantity" id="editStock" required>
                    </label>

                    <!-- Container for additional variants -->
                    <div id="extra-variants-container"></div>
                    <!-- Add another size button -->
                    <div class="editModal-btns">
                        <button type="button" onclick="addExtraVariant()" class="addSize-btn">Add Another Size</button>
                        <button type="submit" class="modal-editProduct-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="logoutModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h3>Confirm Logout</h3>
                <p>Are you sure you want to log out?</p>
                <div style="margin-top: 15px;">
                <button href="../logout.php" onclick="confirmLogout()">Yes, Logout</button>
                <button onclick="closeLogoutModal()">Cancel</button>
                </div>
            </div>
        </div>

    </main>

        <!--Logout Confirmation Modal JS -->
    <script>
        function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'block';
        }

        function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
        window.location.href = '../logout.php'; // Adjust path if needed
        }
    </script>

    <script src="/Neverlonely/Assets/javascript/script.js"></script>
    <script src="/Neverlonely/Assets/javascript/sidebar.js"></script>
</body>
</html>