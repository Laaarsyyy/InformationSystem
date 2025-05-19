<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neverlonely</title>

    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">

    <!-- icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
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
                <a onclick="openLogoutModal()" style="cursor: pointer;">
                    <span class="material-icons">logout</span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <main>
        <div class="manage-products-container">
            <h1>Sales Records</h1>
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
                </tr>
            </thead>
            <tbody>
                <?php
                    include '../config.php';
                    $sql = "SELECT * FROM archived_sales ORDER BY order_date DESC, order_id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0):
                        $rows = [];
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;
                        }
                        // Count rows per order_id
                        $orderRowCounts = [];
                        foreach ($rows as $row) {
                            $orderRowCounts[$row['order_id']] = isset($orderRowCounts[$row['order_id']])
                                ? $orderRowCounts[$row['order_id']] + 1
                                : 1;
                        }
                        $printedOrderIds = [];
                        $orderPrintedCount = [];
                        foreach ($rows as $row) {
                            $order_id = $row['order_id'];
                            if (!isset($orderPrintedCount[$order_id])) {
                                $orderPrintedCount[$order_id] = 1;
                            } else {
                                $orderPrintedCount[$order_id]++;
                            }
                            $isLastRowOfOrder = $orderPrintedCount[$order_id] === $orderRowCounts[$order_id];
                ?>
                    <tr style="<?= $isLastRowOfOrder ? 'border-bottom: 2px solid black;' : '' ?>">
                        <?php if (!in_array($order_id, $printedOrderIds)) { ?>
                            <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= $order_id ?></td>
                            <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= htmlspecialchars($row['customer_name']) ?></td>
                        <?php } ?>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['size'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <?php if (!in_array($order_id, $printedOrderIds)) { ?>
                            <td rowspan="<?= $orderRowCounts[$order_id] ?>">
                                ₱<?= number_format(array_sum(array_column(
                                    array_filter($rows, fn($r) => $r['order_id'] === $order_id),
                                    'total_price'
                                )), 2) ?>
                            </td>
                            <td rowspan="<?= $orderRowCounts[$order_id] ?>"><?= date('Y-m-d h:i A', strtotime($row['order_date'])) ?></td>
                        <?php } ?>
                    </tr>
                <?php
                            $printedOrderIds[] = $order_id;
                        }
                    else:
                ?>
                    <tr><td colspan="7">No archived sales found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
            <!-- Logout Confirmation Modal-->
        <div id="logoutModal" class="modal" style="display: none;">
            <div class="logoutModal-content">
                <span class="close" onclick="closeLogoutModal()">&times;</span>
                <h3>Confirm Logout</h3>
                <p>Are you sure you want to log out?</p>
                <div class="logoutButtons" style="margin-top: 15px;">
                    <button class="confirmLogout" href="../logout.php" onclick="confirmLogout()">Yes, Logout</button>
                    <button class="cancelLogout" onclick="closeLogoutModal()">Cancel</button>
                </div>
            </div>
        </div>

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
    <script src="/Neverlonely/Assets/javascript/sidebar.js"></script>
</body>
</html>