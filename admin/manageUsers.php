<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
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
            <h1>Manage Users</h1>
            <button class="createTransac-btn">Add User</button>
        </div>

        <table class="products-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include '../config.php';
                    $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                    if ($result->num_rows > 0):
                        while ($user = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= date('Y-m-d h:i A', strtotime($user['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-edit" data-id="<?= $user['id'] ?>">Edit</button>
                            <button class="btn btn-delete" data-id="<?= $user['id'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4">No users found.</td></tr>
                    <?php endif; ?>
            </tbody>
        </table>
    </main>
    
    <!-- Add User Modal -->
    <div id="addUserModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAddUserModal()">&times;</span>
        <h2>Add New User</h2>
        <form action="addUser.php" method="POST">
            <label>Username:
                <input type="text" name="username" required>
            </label>
            <label>Password:
                <input type="password" name="password" required>
            </label>
            <label>Role:
                <select name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </label>
            <button type="submit">Add User</button>
        </form>
    </div>
    </div>

    <!-- Add User Modal JS -->
    <script>
    const addUserModal = document.getElementById('addUserModal');
    const addUserBtn = document.querySelector('.createTransac-btn');

    addUserBtn.addEventListener('click', () => {
        addUserModal.style.display = 'block';
    });

    function closeAddUserModal() {
        addUserModal.style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target == addUserModal) {
            closeAddUserModal();
        }
    }
    </script>
</body>
</html>