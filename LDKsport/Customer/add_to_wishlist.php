<?php
require '../admin_panel/config/dbconnect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo 'Please log in to add items to your wishlist.';
    exit();
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is already in the wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Product is already in your wishlist.';
    } else {
        // Add product to the wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo 'Product added to your wishlist.';
        } else {
            echo 'Error adding product to wishlist.';
        }
    }
} else {
    echo 'Invalid product ID.';
}
?>
