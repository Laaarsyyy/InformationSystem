<<<<<<< HEAD:login.php
<?php
session_start();

$conn = new mysqli("localhost", "root", "", "data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect
            if ($user['role'] === 'admin') {
                header("Location: admin/adminDash.php");
                exit();
            } else {
                header("Location: staff_dashboard.php");
                exit();
            }
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ User not found.";
    }

    $stmt->close();
}

$conn->close();
?>
=======
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
                header("Location: adminDash.php");
            } else {
                header("Location: staffDash.php");
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
>>>>>>> 01ca0da11dfa4aec4cf2995c90f698cb8f17e2a3:Neverlonely/login.php
