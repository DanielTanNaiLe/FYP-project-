<?php
include("header.php");
require '../admin_panel/config/dbconnect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="general.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

   <!-- custom css file link  -->

   <style>
    .orders .box-container .box{
   padding:1rem 2rem;
   flex:1 1 40rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.orders .box-container .box p{
   margin:.5rem 0;
   line-height: 1.8;
   font-size: 2rem;
   color:var(--light-color);
}

.orders .box-container .box p span{
   color:var(--main-color);
}
    </style>
</head>
<body>

<section class="orders">

   <h1 class="heading">placed orders</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->bind_param("i", $user_id);
         $select_orders->execute();
         $orders_result = $select_orders->get_result();
         if($orders_result->num_rows > 0){
            while($fetch_orders = $orders_result->fetch_assoc()){
   ?>
   <div class="box">
      <p>placed on : <span><?= $fetch_orders['order_date']; ?></span></p>
      <p>name : <span><?= $fetch_orders['delivered_to']; ?></span></p>
      <p>email : <span><?= $fetch_orders['order_email']; ?></span></p>
      <p>number : <span><?= $fetch_orders['phone_no']; ?></span></p>
      <p>address : <span><?= $fetch_orders['deliver_address']; ?></span></p>
      <p>payment method : <span><?= $fetch_orders['pay_method']; ?></span></p>
      <p>your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>total price : <span>$<?= $fetch_orders['amount']; ?>/-</span></p>
      <p> payment status : <span style="color:<?php if($fetch_orders['pay_status'] == 'Pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['pay_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>

</section>
<?php include("footer.php"); ?>
</body>
</html>