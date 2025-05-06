<?php include '../config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width='device-width', initial-scale=1.0">
    <title>Neverlonely</title>

    <!-- icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">

    <!-- css -->
    <link rel="stylesheet" href="/Neverlonely/Assets/css/sidebar.css">
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">

    <!-- js -->
    <script src="/Neverlonely/Assets/javascript/sidebar.js"></script>
    <script src="/Neverlonely/Assets/javascript/script.js"></script>
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

            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'Sales.php') ? 'active' : ''; ?>">
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

            <li>
                <a href="/Neverlonely/logout.php">
                    <span class="material-icons">logout</span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <main>
        <div class="manage-products-container">
            <h1>Sales Records</h1>
            <button onclick="openTransactionModal()" class="createTransac-btn">Create Transaction</button>
        </div>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Order-ID</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Customer</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Core Logo</td>
                    <td>XL</td>
                    <td>3</td>
                    <td>₱1740</td>
                    <td>Lars Anthony Listerio</td>
                    <td>3:40pm</td>
                    <td>Delete</td>
                </tr>
            </tbody>
        </table>
        <!-- --------------------------------TRANSACTION MODAL-------------------------------- -->
        <div id="transactionModal" class="transaction-modal" style="display: none;">
        <div class="transaction-modal-content">
            <span class="transaction-close" onclick="closeTransactionModal()">&times;</span>
            <h2>Create Transaction</h2>
            <form action="saveTransaction.php" method="POST" onsubmit="return validateTransaction()">
            <label>Customer Name:
                <input type="text" name="customer_name" required>
            </label>

            <div id="transaction-items">
                <div class="transaction-item-group">
                <label>Product Variant:
                    <select name="variant_ids[]" onchange="updateSubtotal(this)">
                        <?php
                            $variants = $conn->query("SELECT pv.id, p.name, pv.size, p.price FROM product_variants pv 
                                                    JOIN products p ON pv.product_id = p.id");
                            while ($v = $variants->fetch_assoc()):
                            ?>
                            <option value="<?= $v['id'] ?>" data-price="<?= $v['price'] ?>">
                                <?= $v['name'] ?> (<?= $v['size'] ?>) - ₱<?= $v['price'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </label>

                <label>Quantity:
                    <input type="number" name="quantities[]" min="1" value="1" oninput="updateSubtotal(this)" required>
                </label>

                <p class="transaction-subtotal-text">Subtotal: ₱<span class="transaction-subtotal">0.00</span></p>

                <button type="button" class="transaction-removeSize-btn" onclick="removeTransactionItem(this)">Remove</button>
                </div>
            </div>

            <button type="button" onclick="addTransactionItem()">Add Another Item</button>

            <h3>Total: ₱<span id="transaction-total">0.00</span></h3>

            <button type="submit">Submit Transaction</button>
            </form>
        </div>
</div>
    </main> 
</body>
</html>