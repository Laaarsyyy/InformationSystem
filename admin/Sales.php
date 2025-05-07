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

    <div class="bodyLogo">
        <img src="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png" alt="">
    </div>

    <main>
        <div class="manage-products-container">
            <h1>Sales Records</h1>
            <button onclick="openTransactionModal()" class="createTransac-btn">Create Transaction</button>
        </div>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Order-ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "
                        SELECT
                            o.id AS order_id,
                            o.customer_name,
                            p.name AS product_name,
                            pv.size,
                            oi.quantity,
                            (p.price * oi.quantity) AS total_amount,
                            o.order_date AS created_at,
                            oi.id AS order_item_id
                        FROM order_items oi
                        JOIN orders o ON oi.order_id = o.id
                        JOIN product_variants pv ON oi.variant_id = pv.id
                        JOIN products p ON pv.product_id = p.id
                    ";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr style="border-bottom: 1px solid black; padding-top: 50px;">
                        <td><?= htmlspecialchars($row['order_id']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['size']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td>₱<?= number_format($row['total_amount'], 2) ?></td>
                        <td><?= date("Y-m-d h:i A", strtotime($row['created_at'])) ?></td>
                        <td><button class="transactionDelete-btn" data-id="<?= $row['order_id'] ?>">Delete</button></td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr><td colspan="8">No transactions yet.</td></tr>
                    <?php endif; ?>
            </tbody>
        </table>
        <!-- --------------------------------TRANSACTION MODAL-------------------------------- -->
        <div id="transactionModal" class="transaction-modal" style="display: none;">
            <div class="transaction-modal-content">
                <span class="transaction-close" onclick="closeTransactionModal()">&times;</span>
                <h2>Create Transaction</h2>
                <form action="saveTransaction.php" method="POST" onsubmit="return validateTransaction()">
                    <label style="border-bottom: 3px solid black;">Customer Name:
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
                <div class="transaction-footer">
                    <h3>Total: ₱<span id="transaction-total">0.00</span></h3>
                    <div class="transactionFooter-btns">
                        <button class="transactionAddItem" type="button" onclick="addTransactionItem()">Add Another Item</button>
                        <button class="transactionSubmit" type="submit">Submit Transaction</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </main> 
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');

    if (toggleButton && sidebar) {
        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('close');
            toggleButton.classList.toggle('rotate');
        });
    }
});
    </script>
    <script src="/Neverlonely/Assets/javascript/script.js"></script>
</body>
</html>