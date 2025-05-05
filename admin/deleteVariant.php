<?php
require '../config.php'; // Adjust path if needed

if (isset($_GET['id'])) {
    $variant_id = intval($_GET['id']);

    // Delete the variant from the database
    $stmt = $conn->prepare("DELETE FROM product_variants WHERE id = ?");
    $stmt->bind_param("i", $variant_id);

    if ($stmt->execute()) {
        // Optional: You can add a success message via session or query param
        header("Location: manageProducts.php");
    } else {
        echo "Error deleting variant.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No variant ID specified.";
}
