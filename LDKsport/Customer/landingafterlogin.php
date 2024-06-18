<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
include '../admin_panel/config/dbconnect.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
 
   header('location:customer login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:customer login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">

</head>
<body>
<?php include("header.php"); ?>
<div class="container">

   <div class="profile">
      <?php
         $select = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select) > 0){
            $fetch = mysqli_fetch_assoc($select);
         }
         if($fetch['image'] == ''){
            echo '<img src="image/default-avatar.png">';
         }else{
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }
      ?>
      <h3><?php echo $fetch['first_name']; ?></h3>
      <a href="customer edit.php" class="btn">update profile</a>
      
      <a href="landingafterlogin.php?logout=<?php echo $user_id; ?>" class="delete-btn">logout</a>
      <p>new <a href="customer login.php">login</a> or <a href="customer register.php">register</a></p>
   </div>

</div>
<?php include("footer.php"); ?>
</body>
</html>