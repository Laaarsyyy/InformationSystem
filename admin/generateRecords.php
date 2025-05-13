<?php
session_start();
require '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
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
            <h1>Records</h1>
        </div>

        <table class="products-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Orders</th>
                    <th>Total Quantity Sold</th>
                    <th>Total Revenue</th>
                    <th>Profit</th>
                </tr>
            </thead>

            <tbody>
                <?php
            $sql = "
                SELECT 
                    DATE_FORMAT(o.order_date, '%Y-%m') AS month,
                    COUNT(DISTINCT o.id) AS total_orders,
                    SUM(oi.quantity) AS total_items_sold,
                    SUM(p.price * oi.quantity) AS total_revenue,
                    SUM(p.costing * oi.quantity) AS total_cost,
                    SUM((p.price - p.costing) * oi.quantity) AS total_profit
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN product_variants pv ON oi.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                GROUP BY month
                ORDER BY month DESC
            ";

            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= date("F Y", strtotime($row['month'] . "-01")) ?></td>
                <td><?= $row['total_orders'] ?></td>
                <td><?= $row['total_items_sold'] ?></td>
                <td>₱<?= number_format($row['total_revenue'], 2) ?></td>
                <td>₱<?= number_format($row['total_profit'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <script src="/Neverlonely/Assets/javascript/script.js"></script>
    <script src="/Neverlonely/Assets/javascript/sidebar.js"></script>
</body>
</html>