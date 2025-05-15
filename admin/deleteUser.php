<?php
include '../config.php';

$user_id = $_POST['user_id'];

// Prevent deleting yourself (optional)
session_start();
if ($_SESSION['user_id'] == $user_id) {
    echo "You cannot delete your own account.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: manageUsers.php");
} else {
    echo "Failed to delete user.";
}
?>
