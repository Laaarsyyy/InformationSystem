<?php
include '../config.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

// Optional: Check if username already exists
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Username already exists.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    header("Location: manageUsers.php");
} else {
    echo "Error adding user.";
}
?>
