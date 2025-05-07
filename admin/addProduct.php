<?php
include '../config.php'; // adjust as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $costing = $_POST['costing'];
    $sizes = $_POST['sizes'];
    $stocks = $_POST['stocks'];

    // Image upload handling
    $image_path = "";
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $image_name = basename($_FILES['product_image']['name']);
        $target_dir = "../Assets/uploads/";
        $target_file = $target_dir . $image_name;

        // Optional: validate image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['product_image']['type'], $allowed_types)) {
            if (move_uploaded_file($image_tmp, $target_file)) {
                $image_path = "/Neverlonely/Assets/uploads/" . $image_name; // Save relative path with correct prefix
            } else {
                die("Failed to upload image.");
            }
        } else {
            die("Only JPG, PNG, and GIF files are allowed.");
        }
    }

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (name, price, costing, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdds", $product_name, $price, $costing, $image_path);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Insert variants
    $stmt = $conn->prepare("INSERT INTO product_variants (product_id, size, stock_quantity) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $stock = $stocks[$i];
        $stmt->bind_param("isi", $product_id, $size, $stock);
        $stmt->execute();
    }
    $stmt->close();

    header("Location: manageProducts.php");
    exit;
}
?>