<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="/Neverlonely/Assets/never lonely RAGER FIEND LOGO ICON.png">
        
        <!-- css -->
        <link rel="stylesheet" href="/Neverlonely/Assets/css/sidebar.css">
        <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">

        <!-- css -->
        <script src="/Neverlonely/Assets/javascript/script.js"></script>
        <!-- Chart cdn-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Neverlonely</title>
</head>
<body>
    <?php include 'sidebar.php'; ?>


    <div class="main-content">

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
        </div>
    
        <!-- Charts -->
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>


    </div>
</body>
</html>