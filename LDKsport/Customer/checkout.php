<?php
// Start session and include necessary files
include("header.php");

require '../admin_panel/config/dbconnect.php';

// Check if user is logged in
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   // Redirect to login page if user is not logged in
   header('location:customer login.php');
   exit();
}

// Process order placement
if(isset($_POST['order'])){
   // Sanitize input data
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   // Check if cart is not empty
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->bind_param("i", $user_id);
   $check_cart->execute();
   $result = $check_cart->get_result();

   if($result->num_rows > 0){
      // Insert order into database
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, delivered_to, phone_no, email, method, deliver_address, total_products, total_price, order_date) VALUES(?,?,?,?,?,?,?,?, NOW())");
      $insert_order->bind_param("issssssd", $user_id, $name, $number, $email, $method, $address, $total_products, $total_price);
      $insert_order->execute();

      // Delete items from cart
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->bind_param("i", $user_id);
      $delete_cart->execute();

      // Set success message
      $_SESSION['message'] = 'Order placed successfully!';
   }else{
      // Set error message if cart is empty
      $_SESSION['message'] = 'Your cart is empty';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="general.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

   <style>
    .display-orders{
        text-align: center;
        padding-bottom: 0;
    }
    
    .display-orders p{
        display: inline-block;
        padding:1rem 2rem;
        margin:1rem .5rem;
        font-size: 2rem;
        text-align: center;
        border:var(--border);
        background-color: var(--white);
        box-shadow: var(--box-shadow);
        border-radius: .5rem;
    }
    
    .display-orders p span{
        color:var(--red);
    }
    
    .display-orders .grand-total{
        margin-top: 1.5rem;
        margin-bottom: 2.5rem;
        font-size: 2.5rem;
        color:var(--light-color);
    }
    
    .display-orders .grand-total span{
        color:var(--red);
    }
    
    .checkout-orders form{
        padding:2rem;
        border:var(--border);
        background-color: var(--white);
        box-shadow: var(--box-shadow);
        border-radius: .5rem;
    }
    
    .checkout-orders form h3{
        border-radius: .5rem;
        background-color: var(--black);
        color:var(--white);
        padding:1.5rem 1rem;
        text-align: center;
        text-transform: uppercase;
        margin-bottom: 2rem;
        font-size: 2.5rem;
    }
    
    .checkout-orders form .flex{
        display: flex;
        flex-wrap: wrap;
        gap:1.5rem;
        justify-content: space-between;
    }
    
    .checkout-orders form .flex .inputBox{
        width: 49%;
    }
    
    .checkout-orders form .flex .inputBox .box{
        width: 100%;
        border:var(--border);
        border-radius: .5rem;
        font-size: 1.8rem;
        color:var(--black);
        padding:1.2rem 1.4rem;
        margin:1rem 0;
        background-color: var(--light-bg);
    }
    
    .checkout-orders form .flex .inputBox span{
        font-size: 1.8rem;
        color:var(--light-color);
    }
    </style>
</head>
<body>
   
<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         // Initialize variables
         $grand_total = 0;
         $cart_items = array();
         // Select items from cart for the current user
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->bind_param("i", $user_id);
         $select_cart->execute();
         $result = $select_cart->get_result();
         if($result->num_rows > 0){
            // Loop through cart items
            while($fetch_cart = $result->fetch_assoc()){
               // Calculate total price
               $subtotal = $fetch_cart['price'] * $fetch_cart['quantity'];
               $grand_total += $subtotal;
               // Construct cart items list
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].')';
      ?>
         <p> <?= htmlspecialchars($fetch_cart['name']); ?> <span>(<?= '$'.htmlspecialchars($fetch_cart['price']).'/- x '. htmlspecialchars($fetch_cart['quantity']); ?>)</span> </p>
      <?php
            }
         }else{
            // Display message if cart is empty
            echo '<p class="empty">Your cart is empty!</p>';
         }
      ?>
         <!-- Hidden fields for total products and total price -->
         <input type="hidden" name="total_products" value="<?= htmlspecialchars(implode(', ', $cart_items)); ?>">
         <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
         <div class="grand-total">Grand Total : <span>$<?= htmlspecialchars($grand_total); ?>/-</span></div>
      </div>

      <h3>Place Your Orders</h3>

      <div class="flex">
         <!-- Form fields for order details -->
         <div class="inputBox">
            <span>Name :</span>
            <input type="text" name="name" placeholder="Enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Phone number :</span>
            <input type="text" name="number" placeholder="Enter your phone number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">Paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Flat no. :</span>
            <input type="text" name="flat" placeholder="e.g. flat no." class="box" required>
         </div>
         <div class="inputBox">
            <span>Street :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="e.g. city name" class="box" required>
         </div>
         <div class="inputBox">
            <span>State :</span>
            <input type="text" name="state" placeholder="e.g. state name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="e.g. country name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Pin code :</span>
            <input type="text" name="pin_code" placeholder="e.g. pin code" class="box" required>
         </div>
      </div>

      <!-- Submit button -->
      <input type="submit" name="order" class="btn" value="Place Order">

   </form>

</section>

</body>
</html>