<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

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

                        // taga check kung ilang row meron
                        if (!isset($orderPrintedCount[$order_id])) {
                            $orderPrintedCount[$order_id] = 1;
                        } else {
                            $orderPrintedCount[$order_id]++;
                        }

                        // ichechecck if eto na yung last row 
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
                                    <div class="actionBtns">
                                        <button class="cancel-btn" data-id="<?= $order_id ?>">Cancel Order</button>
                                        <button class="transactionArchive-btn" data-id="<?= $order_id ?>">Archive</button>
                                    </div>
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
        <!-- ----------TRANSACTION MODAL------------------ -->
        <div id="transactionModal" class="transaction-modal" style="display: none;">
            <div class="transaction-modal-content">
                <span class="transaction-close" onclick="closeTransactionModal()">&times;</span>
                <h2>Create Transaction</h2>
                <form id="transactionForm">
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

        <!-- Error Modal -->
        <div id="errorModal" class="modal" style="display: none;">
            <div class="errorModal-content">
                <span class="transaction-close" onclick="closeErrorModal()">&times;</span>
                <h3>Error</h3>
                <p id="errorModalMessage">Something went wrong.</p>
                <div style="margin-top: 15px;">
                    <button class="errorModal-btn" onclick="closeErrorModal()">Go back</button>
                </div>
            </div>
        </div>

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

        
                    <!-- Archive Confirmation Modal-->
        <div id="archiveConfirmModal" class="modal" style="display: none;">
            
            <div class="archiveModal-content">
                <span class="transaction-close" onclick="closeArchiveModal()">&times;</span>
                <h3>Confirm Archive</h3>
                <p>Are you sure you want to archive this sale?</p>
                <div class="archiveButtons" style="margin-top: 15px;">
                    <button class="archiveYes" id="confirmArchiveBtn">Yes, Archive</button>
                    <button class="archiveNo" onclick="closeArchiveModal()">Cancel</button>
                </div>
            </div>
        </div>

            <!-- Confirm Cancel Order mMdal -->
        <div id="cancelConfirmModal" class="modal" style="display: none;">
            <div class="archiveModal-content">
                <span class="transaction-close" onclick="document.getElementById('cancelConfirmModal').style.display='none'">&times;</span>
                <h3>Confirm Cancellation</h3>
                <p>Are you sure you want to cancel this order?</p>
                <div class="cancelBtnsModal">
                    <button class="confirmCancel" id="confirmCancelBtn">Yes, Cancel</button>
                    <button class="cancelNo" onclick="document.getElementById('cancelConfirmModal').style.display='none'">No</button>
                </div>
            </div>
        </div>

        <!-- Cancel Order Success Modal -->
        <div id="cancelSuccessModal" class="modal" style="display: none;">
            <div class="archiveModal-content">
                <h3>Order Cancelled</h3>
                <p>The order has been cancelled and stock restored.</p>
                <div class="cancelBtnsModal">
                    <button class="confirmCancel" id="closeSuccessBtn" style="margin-left: 50px;">OK</button>
                </div>
            </div>
        </div>

        <!-- Archive Success Modal -->
        <div id="archiveSuccessModal" class="modal" style="display: none;">
            <div class="errorModal-content">
                <span class="transaction-close" onclick="closeArchiveSuccessModal()">&times;</span>
                <h3>Success</h3>
                <p>Sale archived successfully!</p>
                <div style="margin-top: 15px;">
                    <button class="errorModal-btn" onclick="closeArchiveSuccessModal()">OK</button>
                </div>
            </div>
        </div>
        <!-- Sale Success Modal -->
        <div id="saleSuccessModal" class="modal" style="display: none;">
            <div class="errorModal-content">
                <span class="transaction-close" onclick="closeSaleSuccessModal()">&times;</span>
                <h3>Success</h3>
                <p>Paldo na naman!</p>
                <div style="margin-top: 15px;">
                    <button class="errorModal-btn" onclick="closeSaleSuccessModal()">OK</button>
                </div>
            </div>
        </div>
    </main> 

    <script>
        // Function to show success modal
        function showSaleSuccessModal() {
            const modal = document.getElementById('saleSuccessModal');
            if (modal) {
                modal.style.display = 'block';
                console.log('Success modal should be visible now');
            } else {
                console.error('Success modal element not found');
            }
        }

        // Function to close success modal
        function closeSaleSuccessModal() {
            const modal = document.getElementById('saleSuccessModal');
            if (modal) {
                modal.style.display = 'none';
                window.location.reload();
            }
        }

        document.querySelector('form[action="saveTransaction.php"]').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('saveTransaction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success === true) {
                    showSaleSuccessModal();
                } else {
                    showErrorModal(data.error || 'An error occurred while saving the transaction');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('An error occurred while processing your request');
            });
        });

        // Function to show error modal
        function showErrorModal(message) {
            const errorModal = document.getElementById('errorModal');
            document.getElementById('errorModalMessage').innerText = message;
            errorModal.style.display = 'block';
        }

        // Function to close error modal
        function closeErrorModal() {
            document.getElementById('errorModal').style.display = 'none';
        }

        // Initialize quantity inputs to 1
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('input[name="quantities[]"]');
            quantityInputs.forEach(input => {
                input.value = 1;
            });
        });
    </script>

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


    <script>
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('saveTransaction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);

                if (data.success === true) {
                    const successModal = document.getElementById('saleSuccessModal');
                    successModal.style.display = 'block';

                    const okButton = successModal.querySelector('.errorModal-btn');
                    okButton.onclick = function () {
                        successModal.style.display = 'none';
                        window.location.reload();
                    };
                } else {
                    showErrorModal(data.error || 'An error occurred while saving the transaction');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('An error occurred while processing your request');
            });
        });

    </script>

    <!-- Archive Transaction Modal -->
    <script>
        let selectedOrderId = null;

        document.querySelectorAll('.transactionArchive-btn').forEach(button => {
            button.addEventListener('click', function () {
                selectedOrderId = this.dataset.id;
                document.getElementById('archiveConfirmModal').style.display = 'block';
            });
        });

        document.getElementById('confirmArchiveBtn').addEventListener('click', function () {
            if (selectedOrderId) {
                fetch('archiveSale.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: selectedOrderId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showArchiveSuccessModal();
                    } else {
                        alert('Failed to archive sale.');
                    }
                })
                .catch(error => console.error('Error:', error));

                closeArchiveModal();
            }
        });

        function closeArchiveModal() {
            document.getElementById('archiveConfirmModal').style.display = 'none';
        }

        function showArchiveSuccessModal() {
            document.getElementById('archiveSuccessModal').style.display = 'block';
        }
        function closeArchiveSuccessModal() {
            document.getElementById('archiveSuccessModal').style.display = 'none';
            location.reload(); // reload after closing modal
        }

    </script>

        <!--Sidebar JS (it doesn't work if it is in js file) -->
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

        <!-- Cancel Order JS -->
    <script>
        let orderToCancel = null;

        // Open confirmation modal
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function () {
                orderToCancel = this.getAttribute('data-id');
                document.getElementById('cancelConfirmModal').style.display = 'block';
            });
        });

        // Confirm cancel
        document.getElementById('confirmCancelBtn').addEventListener('click', function () {
            if (orderToCancel) {
                fetch('cancelOrder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order_id: orderToCancel })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = document.querySelector(`.cancel-btn[data-id="${orderToCancel}"]`).closest('tr');
                        if (row) row.remove();

                        // Show modal
                        document.getElementById('cancelSuccessModal').style.display = 'block';
                    } else {
                        alert("Failed to cancel: " + (data.error || "Unknown error"));
                    }
                });

                document.getElementById('cancelConfirmModal').style.display = 'none';
            }
        });

        function closeCancelModal() {
            document.getElementById('cancelConfirmModal').style.display = 'none';
        }
    </script>

        <!-- Close Cancel order modal-->
    <script>
        function closeCancelSuccessModal() {
            document.getElementById('closeSuccessBtn').addEventListener('click', function () {
                location.reload(); // refresh the page
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const closeBtn = document.getElementById('closeSuccessBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    location.reload();
                });
            }
        });
    </script>

    <script>
        function showSaleSuccessModal() {
            document.getElementById('saleSuccessModal').style.display = 'block';
        }
        function closeSaleSuccessModal() {
            document.getElementById('saleSuccessModal').style.display = 'none';
            location.reload();
        }
    </script>
    <script src="/Neverlonely/Assets/javascript/script.js"></script>
</body>
</html>

