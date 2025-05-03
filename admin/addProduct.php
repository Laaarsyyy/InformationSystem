<?php
session_start();
$conn = new mysqli("localhost", "root", "", "data");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sizes = $_POST['size']; // array
    $stocks = $_POST['stock']; // array

    // Insert into products table
    $stmt = $conn->prepare("INSERT INTO products (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();
    $product_id = $stmt->insert_id;

    // Insert into product_variants
    $variant_stmt = $conn->prepare("INSERT INTO product_variants (product_id, size, stock_quantity) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $stock = $stocks[$i];
        $variant_stmt->bind_param("isi", $product_id, $size, $stock);
        $variant_stmt->execute();
    }

    header("Location: manageProducts.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="/Neverlonely/Assets/css/style.css">
    <script>
        function addSizeField() {
            const container = document.getElementById('size-container');
            const html = `
                <div class="variant-row">
                    <input type="text" name="size[]" placeholder="Size (e.g., S)" required>
                    <input type="number" name="stock[]" placeholder="Stock" required>
                    <button type="button" onclick="this.parentNode.remove()">❌</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<main class="main-content">
    <div class="container">
        <h1>Add New Product</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Product Name" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>

            <h3>Size Variants</h3>
            <div id="size-container">
                <div class="variant-row">
                    <input type="text" name="size[]" placeholder="Size (e.g., S)" required>
                    <input type="number" name="stock[]" placeholder="Stock" required>
                </div>
            </div>
            <button type="button" onclick="addSizeField()">➕ Add More Size</button><br><br>

            <button type="submit">Save Product</button>
        </form>
    </div>
</main>
</body>
</html>
