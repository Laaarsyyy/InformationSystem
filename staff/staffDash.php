<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav id="sidebar">
        <ul>
            <li class="<?=(basename($_SERVER['PHP_SELF']) == 'Sales.php') ? 'active' : ''; ?>">
                <a href="/Neverlonely/admin/Sales.php">
                    <span class="material-icons">shopping_cart</span>
                    <span>Sales</span>
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
                $sql = "
                    SELECT 
                        o.id AS order_id,
                        o.customer_name,
                        o.order_date,
                        p.name AS product_name,
                        pv.size,
                        oi.quantity,
                        p.price,
                        (p.price * oi.quantity) AS item_total
                    FROM orders o
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN product_variants pv ON oi.variant_id = pv.id
                    JOIN products p ON pv.product_id = p.id
                    ORDER BY o.order_date DESC
                ";

                $result = $conn->query($sql);

                $currentOrderId = null;
                $orderRowCounts = [];

                if ($result->num_rows > 0) {
                    $rows = $result->fetch_all(MYSQLI_ASSOC);

                    foreach ($rows as $row) {
                        $orderRowCounts[$row['order_id']] = isset($orderRowCounts[$row['order_id']]) 
                            ? $orderRowCounts[$row['order_id']] + 1 
                            : 1;
                    }

                    $printedOrderIds = [];
                    $orderPrintedCount = [];

                    foreach ($rows as $row) {
                        $order_id = $row['order_id'];

                        // Track how many rows printed for this order
                        if (!isset($orderPrintedCount[$order_id])) {
                            $orderPrintedCount[$order_id] = 1;
                        } else {
                            $orderPrintedCount[$order_id]++;
                        }

                        // Check if this is the last row for the current order
                        $isLastRowOfOrder = $orderPrintedCount[$order_id] === $orderRowCounts[$order_id];
                        ?>
                        <tr style="<?= $isLastRowOfOrder ? 'border-bottom: 2px solid black;' : '' ?>">
                            <?php if (!in_array($order_id, $printedOrderIds)) { ?>
                                <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= htmlspecialchars($order_id) ?></td>
                                <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= htmlspecialchars($row['customer_name']) ?></td>
                            <?php } ?>

                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['size']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>

                            <?php if (!in_array($order_id, $printedOrderIds)) { ?>
                                <td rowspan="<?= $orderRowCounts[$order_id] ?>">
                                    ₱<?= number_format(array_sum(array_column(
                                        array_filter($rows, fn($r) => $r['order_id'] === $order_id), 
                                        'item_total'
                                    )), 2) ?>
                                </td>
                                <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= date("Y-m-d h:i A", strtotime($row['order_date'])) ?></td>
                                <td rowspan="<?= $orderRowCounts[$order_id] ?>">
                                    <button class="transactionDelete-btn" data-id="<?= $order_id ?>">Delete</button>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                        $printedOrderIds[] = $order_id;
                    }

                } else {
                    echo '<tr><td colspan="8">No transactions yet.</td></tr>';
                }
                ?>

        </tbody>
        </table>
        <!-- -------------TRANSACTION MODAL-------------------------------- -->
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
                            // Fetch variants with stock info
                            $variants = $conn->query("
                                SELECT pv.id, p.name, pv.size, p.price, pv.stock_quantity 
                                FROM product_variants pv 
                                JOIN products p ON pv.product_id = p.id
                            ");

                            while ($v = $variants->fetch_assoc()):
                                if ($v['stock_quantity'] > 0):
                            ?>
                                <option value="<?= $v['id'] ?>" data-price="<?= $v['price'] ?>">
                                    <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['size']) ?>) - ₱<?= number_format($v['price'], 2) ?>
                                </option>
                            <?php else: ?>
                                <option disabled>
                                    <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['size']) ?>) - Out of Stock
                                </option>
                            <?php 
                                endif;
                            endwhile; 
                            ?>
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