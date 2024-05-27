<?php
require '../admin_panel/config/dbconnect.php';
require __DIR__ . '/mailer.php'; // Include the mailer.php file

if(isset($_POST['submit'])){
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
    $flat_no = mysqli_real_escape_string($conn, $_POST['flat_no']);
    $street_name = mysqli_real_escape_string($conn, $_POST['street_name']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
    
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

    if(mysqli_num_rows($select) > 0){
        $message[] = 'User already exists';
    }else{
        if($password != $cpassword){
            $message[] = 'Confirm password not matched!';
        }elseif($image_size > 2000000){
            $message[] = 'Image size is too large!';
        }else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_query($conn, "INSERT INTO `users`(first_name, last_name, email, password, image, flat_no, street_name, city, state, country, contact_no) VALUES('$first_name', '$last_name', '$email', '$hashed_password', '$image', '$flat_no', '$street_name', '$city', '$state', '$country', '$contact_no')") or die('query failed');

            if($insert){
                move_uploaded_file($image_tmp_name, $image_folder);

                // Get the PHPMailer instance from mailer.php
                $mail = require __DIR__ . '/mailer.php';
                try {
                    //Recipients
                    $mail->setFrom('dtnl0819@gmail.com');
                    $mail->addAddress($email, $first_name . ' ' . $last_name);

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                    $mail->Subject = 'Email Verification';
                    $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

                    $mail->send();
                    
                    // Update database with verification code
                    $update = mysqli_query($conn, "UPDATE `users` SET verification_code = '$verification_code' WHERE email = '$email'") or die('query failed');
                    
                    $message[] = 'Registered successfully! Please check your email to verify your account.';
                    header('location:email-verification.php?email=' . $email);
                } catch (Exception $e) {
                    $message[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
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
   <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="text" name="first_name" placeholder="Enter First Name" class="box" required>
      <input type="text" name="last_name" placeholder="Enter Last Name" class="box" required>
      <input type="email" name="email" placeholder="Enter Email" class="box" required>
      <input type="password" name="password" id="password" placeholder="Enter Password" class="box" required>
      <input type="password" name="cpassword" id="password_confirmation" placeholder="Confirm Password" class="box" required>
      <input type="text" name="flat_no" placeholder="Enter Flat Number" class="box" required>
      <input type="text" name="street_name" placeholder="Enter Street Name" class="box" required>
      <input type="text" name="city" placeholder="Enter City" class="box" required>
      <input type="text" name="state" placeholder="Enter State" class="box" required>
      <input type="text" name="country" placeholder="Enter Country" class="box" required>
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
            } else {
                show.type="password";
            }

            var showConfirm = document.getElementById("password_confirmation");
            if(showConfirm.type=="password"){
                showConfirm.type="text";
            } else {
                showConfirm.type="password";
            }
        }
    </script>
      <p>Already have an account? <a href="customer login.php">Login Now</a></p>
   </form>
</div>
</body>
</html>
