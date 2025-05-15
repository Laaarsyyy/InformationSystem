<?php
include '../config.php';

$id = $_POST['user_id'];
$username = $_POST['username'];
$role = $_POST['role'];
$password = $_POST['password'];

// Update with or without password
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $hashedPassword, $role, $id);
} else {
    $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $id);
}

if ($stmt->execute()) {
    header("Location: manageUsers.php");
} else {
    echo "Failed to update user.";
}
?>
