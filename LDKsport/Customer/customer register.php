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
    } else {
        if($password != $cpassword){
            $message[] = 'Confirm password not matched!';
        } elseif($image_size > 2000000){
            $message[] = 'Image size is too large!';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$^&*+=])[A-Za-z\d@#$^&*+=]{8,20}$/', $password)) {
            $message[] = 'Password must be 8-20 characters long, contain upper and lower case letters, a number, and a special character from @#$^&*+= (excluding %).';
        } else {
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

:root{
   --blue:#3498db;
   --dark-blue:#2980b9;
   --red:#e74c3c;
   --dark-red:#c0392b;
   --black:#333;
   --white:#fff;
   --light-bg:#f9f9f9;
   --box-shadow:0 5px 10px rgba(0,0,0,.1);
}

*{
   font-family: 'Poppins', sans-serif;
   margin: 0; 
   padding: 0;
   box-sizing: border-box;
   outline: none; 
   border: none;
   text-decoration: none;
}

*::-webkit-scrollbar{
   width: 10px;
}

*::-webkit-scrollbar-track{
   background-color: transparent;
}

*::-webkit-scrollbar-thumb{
   background-color: var(--blue);
}

.btn,
.delete-btn{
   width: 100%;
   border-radius: 5px;
   padding: 12px 20px;
   color: var(--white);
   text-align: center;
   cursor: pointer;
   font-size: 18px;
   margin-top: 10px;
   transition: background-color 0.3s;
}

.btn{
   background-color: var(--blue);
}

.btn:hover{
   background-color: var(--dark-blue);
}

.delete-btn{
   background-color: var(--red);
}

.delete-btn:hover{
   background-color: var(--dark-red);
}

.message{
   margin: 10px 0;
   padding: 10px;
   background-color: var(--red);
   color: var(--white);
   font-size: 16px;
   border-radius: 5px;
   text-align: center;
}

.form-container{
   min-height: 100vh;
   background-color: var(--light-bg);
   display: flex;
   align-items: center;
   justify-content: center;
   padding: 20px;
}

.form-container form{
   padding: 30px;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   text-align: center;
   width: 700px;
   border-radius: 5px;
}

.form-container form h3{
   margin-bottom: 20px;
   font-size: 24px;
   color: var(--black);
   text-transform: uppercase;
}

.form-container form .box{
   width: 100%;
   border-radius: 5px;
   padding: 12px 14px;
   font-size: 16px;
   color: var(--black);
   margin: 10px 0;
   background-color: var(--light-bg);
   border: 1px solid #ddd;
}

.form-container form .form-row{
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 20px;
}

.form-container form .form-column{
   display: flex;
   flex-direction: column;
}

.form-container form p{
   margin-top: 15px;
   font-size: 16px;
   color: var(--black);
}

.form-container form p a{
   color: var(--red);
   transition: color 0.3s;
}

.form-container form p a:hover{
   text-decoration: underline;
   color: var(--dark-red);
}

.wrapper {
   margin-top: 20px;
}

.requirement-list {
   list-style: none;
   padding: 0;
}

.requirement-list li {
   margin-bottom: 10px;
   display: flex;
   align-items: center;
}

.requirement-list i {
   margin-right: 10px;
}

.requirement-list .fa-check {
   color: green;
}

.requirement-list .fa-circle {
   color: red;
}

@media (max-width: 800px){
   .form-container form{
      width: 100%;
   }
   .form-container form .form-row{
      grid-template-columns: 1fr;
   }
}

</style>
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
      <div class="form-row">
          <div class="form-column">
              <input type="text" name="first_name" placeholder="Enter First Name" class="box" required>
              <input type="text" name="last_name" placeholder="Enter Last Name" class="box" required>
              <input type="email" name="email" placeholder="Enter Email" class="box" required>
              <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
          </div>
          <div class="form-column">
              <input type="text" name="flat_no" placeholder="Enter Flat Number" class="box" required>
              <input type="text" name="street_name" placeholder="Enter Street Name" class="box" required>
              <input type="text" name="city" placeholder="Enter City" class="box" required>
              <input type="text" name="state" placeholder="Enter State" class="box" required>
              <input type="text" name="country" placeholder="Enter Country" class="box" required>
              <input type="text" name="contact_no" placeholder="Enter Contact Number" class="box" required>
          </div>
      </div>
      <div class="wrapper">
          <div class="pass-field">
              <input type="password" name="password" id="password" placeholder="Enter Password" class="box" required>
          </div>
          <div class="content">
              <p>Password must contain:</p>
              <ul class="requirement-list">
                  <li><i class="fa-solid fa-circle"></i><span>At least 8 characters length</span></li>
                  <li><i class="fa-solid fa-circle"></i><span>At least 1 number (0...9)</span></li>
                  <li><i class="fa-solid fa-circle"></i><span>At least 1 lowercase letter (a...z)</span></li>
                  <li><i class="fa-solid fa-circle"></i><span>At least 1 special symbol (@#$^&*+=)</span></li>
                  <li><i class="fa-solid fa-circle"></i><span>At least 1 uppercase letter (A...Z)</span></li>
              </ul>
          </div>
      </div>
      <input type="password" name="cpassword" id="password_confirmation" placeholder="Confirm Password" class="box" required>
      <div class="show-password-label">
          <input type="checkbox" id="showpassword" name="showpassword" onclick="togglePasswordVisibility()">
          <span>Show password</span>
      </div>
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="customer login.php">Login Now</a></p>
   </form>
</div>

<script type="text/javascript">
    const passwordInput = document.querySelector(".pass-field input");
    const requirementList = document.querySelectorAll(".requirement-list li");

    const requirements = [
        { regex: /.{8,}/, index: 0 },
        { regex: /[0-9]/, index: 1 },
        { regex: /[a-z]/, index: 2 },
        { regex: /[^A-Za-z0-9]/, index: 3 },
        { regex: /[A-Z]/, index: 4 }
    ];

    passwordInput.addEventListener("keyup", (e) => {
        requirements.forEach(item => {
            const isValid = item.regex.test(e.target.value);
            const requirementItem = requirementList[item.index];
            if (isValid) {
                requirementItem.classList.add("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-check";
            } else {
                requirementItem.classList.remove("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-circle";
            }
        });
    });

    function togglePasswordVisibility() {
        var password = document.getElementById("password");
        var confirmPassword = document.getElementById("password_confirmation");
        if (password.type === "password") {
            password.type = "text";
            confirmPassword.type = "text";
        } else {
            password.type = "password";
            confirmPassword.type = "password";
        }
    }
</script>
</body>
</html>



