<?php
session_start();

if (isset($_POST['add_to_wishlist']) && isset($_POST['pid'])) {
    if ($user_id == '') {
        header('location:customer login.php');
    } else {
        $product_id = $_POST['pid'];

        // Check if the product is already in the wishlist
        $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Product not in wishlist, add it
            $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();

            // Inform the user
            $_SESSION['message'] = 'Product added to wishlist successfully';
        } else {
            // Product already in wishlist, inform the user
            $_SESSION['message'] = 'Product is already in wishlist';
        }
    }
}

if (isset($_POST['add_to_cart'])) {
    if ($user_id == '') {
        header('location:customer login.php');
    } else {
        $pid = $_POST['pid'];
        $size_id = $_POST['size_name'];
        $quantity = $_POST['Quantity'];
        $price = $_POST['price'];

        // Check if the product already exists in the cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND variation_id IN (SELECT variation_id FROM product_size_variation WHERE product_id = ? AND size_id = ?)");
        $stmt->bind_param("iii", $user_id, $pid, $size_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = 'Product is already added to cart!';
        } else {
            // Get the variation_id from product_size_variation table
            $stmt = $conn->prepare("SELECT variation_id FROM product_size_variation WHERE product_id = ? AND size_id = ?");
            $stmt->bind_param("ii", $pid, $size_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $variation = $result->fetch_assoc();

            if ($variation) {
                $variation_id = $variation['variation_id'];

                // Insert into cart table
                $stmt = $conn->prepare("INSERT INTO cart (user_id, variation_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $user_id, $variation_id, $quantity, $price);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $_SESSION['message'] = 'Product added to cart successfully';
                } else {
                    $_SESSION['message'] = 'Error adding to cart';
                }
            } else {
                $_SESSION['message'] = 'Product variation not found';
            }
        }
    }
}