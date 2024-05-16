<?php
require '../admin_panel/config/dbconnect.php';
 include("header.php");
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = ''; 
}

if(isset($_POST['add_to_wishlist']) && isset($_POST['pid'])) {
    $product_id = $_POST['pid'];

    // Check if the product is already in the wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0) {
        // Product not in wishlist, add it
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

         // Inform the user
         echo "<script>alert('Product added to wishlist successfully');</script>";
}else {
    // Product already in wishlist, inform the user
    echo "<script>alert('Product is already in wishlist');</script>";
  }
}
   
?>