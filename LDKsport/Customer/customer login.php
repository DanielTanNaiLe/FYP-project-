<?php

if(isset($_GET['password_updated']) && $_GET['password_updated'] === 'true'){
    echo '<div class="message">Password updated successfully. You can now log in with your new password.</div>';
}

include '../admin_panel/config/dbconnect.php';
session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password']; 

   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed: '.mysqli_error($conn));

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $stored_password = $row['password']; 

      if(password_verify($password, $stored_password)){
         $_SESSION['user_id'] = $row['user_id'];
         header('location:landingafterlogin.php');
         exit;
      }else{
         $message[] = 'Incorrect email or password!';
      }
   }else{
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <title>Login</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Login Now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="email" name="email" placeholder="Enter Email" class="box" required>
      <input type="password" name="password" id="password" placeholder="Enter Password" class="box" required>
         
      <div class="show-password-label">
         <input type="checkbox" id="showpassword" name="showpassword" onclick="myfunction()">
         <span>Show Password</span>
      </div>

      <script type="text/javascript">
         function myfunction(){
            var show = document.getElementById("password");
            if(show.type=="password"){
                show.type="text";
            }
            else{
                show.type="password";
            }
         }
      </script>

      <input type="submit" name="submit" value="Login Now" class="btn">
      <p>Don't have an account? <a href="customer register.php">Register Now</a></p>
      <p><a href="forget_password.php">Forgotten your password?</a></p>
   </form>

</div>
</body>
</html>
