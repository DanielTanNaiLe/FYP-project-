<?php

require '../admin_panel/config/dbconnect.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:customer_login.php');
   exit();
}

if (isset($_POST['reset_password'])) {
   $old_pass_input = $_POST['old_pass'];
   $new_pass_input = $_POST['new_pass'];
   $confirm_pass_input = $_POST['confirm_pass'];

   $select = mysqli_query($conn, "SELECT password FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
   $fetch = mysqli_fetch_assoc($select);
   $stored_pass = $fetch['password'];

   if (!empty($old_pass_input) && !empty($new_pass_input) && !empty($confirm_pass_input)) {
      if (!password_verify($old_pass_input, $stored_pass)) {
         $message[] = 'Old password not matched!';
      } elseif ($new_pass_input !== $confirm_pass_input) {
         $message[] = 'Confirm password not matched!';
      } else {
         $new_hashed_pass = password_hash($confirm_pass_input, PASSWORD_BCRYPT);
         mysqli_query($conn, "UPDATE `users` SET password = '$new_hashed_pass' WHERE user_id = '$user_id'") or die('Query failed');
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

<?php
   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed');
   if (mysqli_num_rows($select) > 0) {
      $fetch = mysqli_fetch_assoc($select);
   }
?>

   <form action="" method="post">
      <?php
         if (isset($message)) {
            foreach ($message as $msg) {
               echo '<div class="message">' . $msg . '</div>';
            }
         }
      ?>
      <div class="inputBox">
         <span>Old Password:</span>
         <input type="password" name="old_pass" id="old_pass" placeholder="Enter previous password" class="box">
         <span>New Password:</span>
         <input type="password" name="new_pass" id="new_pass" placeholder="Enter new password" class="box">
         <span>Confirm Password:</span>
         <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm new password" class="box">
      </div>

      <div class="show-password-label">
         <input type="checkbox" id="showpassword" name="showpassword" onclick="togglePasswordVisibility()">
         <span>Show Password</span>
      </div>

      <input type="submit" value="Reset" name="reset_password" class="btn">
      <a href="customer edit.php" class="btn">Go Back</a>
   </form>

</div>

<script type="text/javascript">
   function togglePasswordVisibility() {
      var oldPass = document.getElementById("old_pass");
      var newPass = document.getElementById("new_pass");
      var confirmPass = document.getElementById("confirm_pass");

      if (oldPass.type === "password") {
         oldPass.type = "text";
         newPass.type = "text";
         confirmPass.type = "text";
      } else {
         oldPass.type = "password";
         newPass.type = "password";
         confirmPass.type = "password";
      }
   }
</script>

</body>   
</html>