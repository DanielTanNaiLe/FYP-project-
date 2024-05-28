<?php

require '../admin_panel/config/dbconnect.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:customer login.php');
   exit();
}

if (isset($_POST['update_profile'])) {
   $update_first_name = mysqli_real_escape_string($conn, $_POST['update_first_name']);
   $update_last_name = mysqli_real_escape_string($conn, $_POST['update_last_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
   $update_flat_no = mysqli_real_escape_string($conn, $_POST['update_flat_no']);
   $update_street_name = mysqli_real_escape_string($conn, $_POST['update_street_name']);
   $update_city = mysqli_real_escape_string($conn, $_POST['update_city']);
   $update_state = mysqli_real_escape_string($conn, $_POST['update_state']);
   $update_country = mysqli_real_escape_string($conn, $_POST['update_country']);
   $update_contact_no = mysqli_real_escape_string($conn, $_POST['update_contact_no']);

   // Update profile details
   mysqli_query($conn, "UPDATE `users` SET first_name = '$update_first_name', last_name = '$update_last_name', email = '$update_email', flat_no = '$update_flat_no', street_name = '$update_street_name', city = '$update_city', state = '$update_state', country = '$update_country', contact_no = '$update_contact_no' WHERE user_id = '$user_id'") or die('Query failed');

   // Update image
   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'uploaded_img/' . $update_image;

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image is too large';
      } else {
         $image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image' WHERE user_id = '$user_id'") or die('Query failed');
         if ($image_update_query) {
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'Image updated successfully!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <!-- custom css file link  -->
</head>
<link rel="stylesheet" href="style.css">
<body>
<div class="update-profile">

   <?php
      $select = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
      if (mysqli_num_rows($select) > 0) {
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <?php
         if ($fetch['image'] == '') {
            echo '<img src="image/default-avatar.png">';
         } else {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
         }
         if (isset($message)) {
            foreach ($message as $message) {
               echo '<div class="message">' . $message . '</div>';
            }
         }
      ?>
    <div class="flex">
         <div class="inputBox">
            <span>First Name:</span>
            <input type="text" name="update_first_name" value="<?php echo $fetch['first_name']; ?>" class="box">
            <span>Last Name:</span>
            <input type="text" name="update_last_name" value="<?php echo $fetch['last_name']; ?>" class="box">
            <span>Email:</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Contact Number:</span>
            <input type="text" name="update_contact_no" value="<?php echo $fetch['contact_no']; ?>" class="box">
            <span>Update Your Picture:</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>

         <div class="inputBox">
            <span>Flat Number:</span>
            <input type="text" name="update_flat_no" value="<?php echo $fetch['flat_no']; ?>" class="box">
            <span>Street Name:</span>
            <input type="text" name="update_street_name" value="<?php echo $fetch['street_name']; ?>" class="box">
            <span>City:</span>
            <input type="text" name="update_city" value="<?php echo $fetch['city']; ?>" class="box">
            <span>State:</span>
            <input type="text" name="update_state" value="<?php echo $fetch['state']; ?>" class="box">
            <span>Country:</span>
             <input type="text" name="update_country" value="<?php echo $fetch['country']; ?>" class="box">
         </div>
      </div>
      <input type="submit" value="Update Profile" name="update_profile" class="btn">
      <a href="landingafterlogin.php" class="delete-btn">Go Back</a>
      <a href="resetpassword.php" class="btn">Reset Password</a>
   </form>

</div>
</body>
</html>
