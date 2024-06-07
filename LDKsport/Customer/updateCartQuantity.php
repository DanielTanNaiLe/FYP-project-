<?php
// updateCartQuantity.php

// Include the database connection file
include_once '../admin_panel/config/dbconnect.php';;

// Check if the necessary POST data is set
if(isset($_POST['variation_id']) && isset($_POST['quantity'])) {
    $variation_id = $_POST['variation_id'];
    $quantity = $_POST['quantity'];

    // Prepare and execute SQL statement to update the stock quantity
    $stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock - ? WHERE variation_id = ?");
    $stmt->bind_param("ii", $quantity, $variation_id);

    if ($stmt->execute()) {
        // Stock quantity updated successfully
        echo "Stock quantity updated successfully.";
    } else {
        // Error updating stock quantity
        echo "Error updating stock quantity: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If variation_id or quantity is not set in POST data
    echo "Invalid request.";
}
?>
