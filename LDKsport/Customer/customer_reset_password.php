<?php

require '../admin_panel/config/dbconnect.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:customer login.php');
   exit();
}

if (isset($_POST['reset_password'])) {
   $old_pass = mysqli_real_escape_string($conn, md5($_POST['old_pass']));
   $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
   $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

   $select = mysqli_query($conn, "SELECT password FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
   $fetch = mysqli_fetch_assoc($select);
   $stored_pass = $fetch['password'];

   if (!empty($old_pass) || !empty($new_pass) || !empty($confirm_pass)) {
      if ($old_pass != $stored_pass) {
         $message[] = 'Old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } else {
         mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE user_id = '$user_id'") or die('Query failed');
         $message[] = 'Password updated successfully!';
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
   <title>Reset Password</title>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="form-container">

   <form action="" method="post">
      <?php
         if (isset($message)) {
            foreach ($message as $message) {
               echo '<div class="message">' . $message . '</div>';
            }
         }
      ?>
      <div class="inputBox">
         <span>Old Password:</span>
         <input type="password" name="old_pass" placeholder="Enter previous password" class="box">
         <span>New Password:</span>
         <input type="password" name="new_pass" placeholder="Enter new password" class="box">
         <span>Confirm Password:</span>
         <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">

         <div class="show-password-label">
         <input type="checkbox" id="showpassword" name="showpassword" onclick="myfunction()">
         <span>Show Password</span>
         </div>
      <script type="text/javascript">
         function myfunction(){
            var show = document.getElementById("password");
            if(show.type == "password"){
                show.type = "text";
            } else {
                show.type = "password";
            }
         }
      </script>

      </div>
      <input type="submit" value="Reset Password" name="reset_password" class="btn">
      <a href="customer edit.php" class="btn">Go Back</a>
   </form>

</div>
</body>
</html>
