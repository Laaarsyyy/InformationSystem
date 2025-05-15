<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "data");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] =$user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/adminDash.php");
            } else {
                header("Location: staff/staffSales.php");
            }
            exit();
        } else {
            echo "Incorrect Password.";
        } 
    }else {
        echo "User Not Found";
    }

    $stmt->close();
    $conn->close();

}
?>
