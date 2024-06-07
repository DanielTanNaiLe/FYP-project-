<?php
// updateCartQuantity.php

// Include the database connection file
include_once "../config/dbconnect.php";

// Check if the request is valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_quantity' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    // Fetch the cart item details
    $stmt = $conn->prepare("SELECT variation_id, quantity FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $variation_id = $item['variation_id'];
        $current_quantity = $item['quantity'];

        // Check stock availability
        $stock_stmt = $conn->prepare("SELECT quantity_in_stock FROM product_size_variation WHERE variation_id = ?");
        $stock_stmt->bind_param("i", $variation_id);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        $stock = $stock_result->fetch_assoc();

        if ($stock && $stock['quantity_in_stock'] >= ($new_quantity - $current_quantity)) {
            // Update the cart quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
            $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Update the stock quantity
                $update_stock_qty = $new_quantity - $current_quantity;
                $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock - ? WHERE variation_id = ?");
                $update_stock_stmt->bind_param("ii", $update_stock_qty, $variation_id);
                $update_stock_stmt->execute();

                echo json_encode(['status' => 'success']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating quantity.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Not enough stock available.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cart item not found.']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit();
}
?>
