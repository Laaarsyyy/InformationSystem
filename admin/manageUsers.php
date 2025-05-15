<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>

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
                            <button class="btn btn-edit" onclick="openEditUserModal(<?= $user['id'] ?>, '<?= $user['username'] ?>', '<?= $user['role'] ?>')">Edit</button>
                            <button class="btn btn-delete" onclick="openDeleteModal(<?= $user['id'] ?>)">Delete</button>
                        </td>
                        <tr class="line"></tr>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4">No users found.</td></tr>
                    <?php endif; ?>
            </tbody>
        </table>

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
    
    <!-- Add User Modal -->
    <div id="addUserModal" class="modal" style="display: none;">
    <div class="addUserModal-content">
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
            <button class="addUserbtn" type="submit">Add User</button>
        </form>
    </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditUserModal()">&times;</span>
            <h2>Edit User</h2>
            <form action="editUser.php" method="POST">
                <input type="hidden" name="user_id" id="editUserId">
                <label>Username:
                    <input type="text" name="username" id="editUsername" required>
                </label>
                <label>New Password (leave blank to keep current):
                    <input type="password" name="password">
                </label>
                <label>Role:
                    <select name="role" id="editRole" required>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </label>
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="confirmDeleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h3>Are you sure you want to delete this user?</h3>
        <form id="deleteUserForm" method="POST" action="deleteUser.php">
            <input type="hidden" name="user_id" id="deleteUserId">
            <div class="modal-buttons">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            </div>
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

    <!-- Edit User Modal JS -->
    <script>
    function openEditUserModal(id, username, role) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editRole').value = role;
        document.getElementById('editUserModal').style.display = 'block';
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('editUserModal');
        if (event.target == modal) {
            closeEditUserModal();
        }
    }
    </script>

    <!-- Delete User Modal JS -->
    <script>
function openDeleteModal(userId) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('confirmDeleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('confirmDeleteModal').style.display = 'none';
}

// Optional: close when clicking outside the modal
window.onclick = function(event) {
    const modal = document.getElementById('confirmDeleteModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
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
</body>
</html>