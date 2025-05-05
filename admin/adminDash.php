<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">

    <!-- icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
    <!-- css -->
    <link rel="stylesheet" href="/Neverlonely/Assets/css/sidebar.css">
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">

    <!-- js -->
    <script src="/Neverlonely/Assets/javascript/script.js"></script>
    
    <!-- Chart cdn-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Neverlonely</title>
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
                    <a href="/Neverlonely/admin/salesRecords.php">
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
            <!-- Cards -->
        <div class="container">
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <h3>Sales<select id="sales-range">
                            <option value="day">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select></h3>
                    </div>
                    <p id="sales-count">0 Sales</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Revenue</h3>
                    </div>
                    <p id="revenue">₱0.01</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Best-Selling Product</h3>
                    </div>
                    <p id="top-product">None</p>
                </div>
            </div>

                    <!-- Charts -->
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <script src="/Neverlonely/Assets/javascript/sidebar.js"></script>
    </main>

</body>
</html>