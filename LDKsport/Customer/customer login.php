<?php
session_start();

// Check if password has been updated
if(isset($_GET['password_updated']) && $_GET['password_updated'] === 'true'){
    echo '<div class="message">Password updated successfully. You can now log in with your new password.</div>';
}

include '../admin_panel/config/dbconnect.php';

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']); // Password in plain text

   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $stored_password = $row['password']; // Password hash retrieved from the database

      // Verify password using password_verify function
      if(password_verify($password, $stored_password)){
         $_SESSION['user_id'] = $row['user_id'];
         $_SESSION['login_success'] = true; // Set login success session variable
         header('location:landingafterlogin.php');
         exit; // Terminate script after redirection
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
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>login now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }

      // Check for login success session variable
      if(isset($_SESSION['login_success']) && $_SESSION['login_success'] === true){
         echo '<div class="alert-message">Successfully logged in!</div>';
         unset($_SESSION['login_success']); // Unset login success session variable
      }
      ?>
      <input type="email" name="email" placeholder="enter email" class="box" required>
      <input type="password" name="password" placeholder="enter password" class="box" required>
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account? <a href="customer register.php">regiser now</a></p>
      <p><a href="forget_password.php">forgotten your password?</a><p>
   </form>

</div>
</body>
</html>
