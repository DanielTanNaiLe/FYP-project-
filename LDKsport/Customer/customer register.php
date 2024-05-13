<?php

require '../admin_panel/config/dbconnect.php';

if(isset($_POST['submit'])){
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
    $user_address = mysqli_real_escape_string($conn, $_POST['user_address']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);

    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

    if(mysqli_num_rows($select) > 0){
        $message[] = 'User already exists';
    }else{
        if($password != $confirm_password){
            $message[] = 'Confirm password not matched!';
        }elseif($image_size > 2000000){
            $message[] = 'Image size is too large!';
        }else{
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
          
            $insert = mysqli_query($conn, "INSERT INTO `users`(first_name, last_name, email, password, image, user_address, contact_no) VALUES('$first_name', '$last_name', '$email', '$hashed_password', '$image', '$user_address', '$contact_no')") or die('query failed');

            if($insert){
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully!';
                header('location:customer login.php');
            }else{
                $message[] = 'Registration failed!';
            }
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
   <title>Register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="first_name" placeholder="Enter First Name" class="box" required>
      <input type="text" name="last_name" placeholder="Enter Last Name" class="box" required>
      <input type="email" name="email" placeholder="Enter Email" class="box" required>
      <input type="password" name="password" id="password" placeholder="Enter Password" class="box" required>
      <input type="password" name="cpassword" id="password_confirmation" placeholder="Confirm Password" class="box" required>
      <input type="text" name="user_address" placeholder="Enter Address" class="box" required>
      <input type="text" name="contact_no" placeholder="Enter Contact Number" class="box" required>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" name="submit" value="Register Now" class="btn">
         
      <div class="show-password-label">
        <input type="checkbox" id="showpassword" name="showpassword" onclick="myfunction()">

        <span>Show password</span>
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
            
            var show = document.getElementById("password_confirmation");
            if(show.type=="password"){
                show.type="text";
            }
            else{
                show.type="password";
            }

        }
    </script>
         
      <p>Already have an account? <a href="customer login.php">Login Now</a></p>
   </form>

</div>

</body>
</html>
