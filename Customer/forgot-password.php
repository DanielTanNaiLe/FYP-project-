<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

include 'dataconnection.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$message = ''; 

if(isset($_POST['forgot_password_submit'])){
    $email = $_POST['email'];
    
    try {

        $check_email = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
        $check_email->execute([$email]);
        $user = $check_email->fetch(PDO::FETCH_ASSOC);      

        if(!$user) {
            $message = 'Email address not found.';
        } else {

            $otp = rand(100000, 999999);


            $update_otp = $conn->prepare("UPDATE users SET otp = ? WHERE user_email = ?");
            $update_otp->execute([$otp, $email]);


            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = '1211209005@student.mmu.edu.my'; 
            $mail->Password = 'dxpn.9Sd'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('1211209005@student.mmu.edu.my', 'Noreply');
            $mail->addAddress($email); 

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = 'Your OTP for password reset is: ' . $otp;

            $mail->SMTPDebug = 2;

            if($mail->send()) {
                $message = 'Email sent successfully.';

                $_SESSION['email'] = $email;
                header('Location: animation.php');
                exit();
            } else {
                $message = 'Error sending email: ' . $mail->ErrorInfo;

            }
        }
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Forgot Password</title>
    <style>
      .container {
         display: flex;
         justify-content: space-between;
         align-items: stretch;
         margin: 0 auto;
      }

      .contact-form {
         width: 40%; 
         padding: 20px; 
      }

      .box {
         width: calc(50% - 20px); 
         margin-bottom: 10px; 
         padding: 10px; 
      }

      .btn {
         width: calc(100% - 20px); 
         padding: 10px; 
      }

      @media (max-width: 768px) {
         .container {
            flex-direction: column;
         }

         .contact-form, .map-container {
            width: 100%;
         }
      }
        input[type=email] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: red;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #d9534f;
        }
        .message {
            color: #d9534f;
            margin-bottom: 20px;
        }
        .back-to-login p:hover {
            transform: scaleY(1.1);
        }
        .back-to-login a {
            color: black; 
            text-decoration: none;
        }
        input[type=email] {
            padding: 10px;
            margin-bottom: 20px;
            border: none; 
            border-bottom: 1px solid #ddd; 
            border-radius: 0; 
            outline: none; 
        }
        input[type=email]::placeholder {
            color: transparent; 
        }
        input[type=email]:focus::placeholder {
            color: #ccc; 
        }
        #animation-container {
            width: 600px; 
            height: 300px; 
        }
               .container {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            margin: 20px;
        }

        .contact-form {
            width: 45%; /* Adjust width as needed */
            padding: 20px; /* Add padding to maintain spacing */
        }

        #animation-container {
            width: 45%; /* Adjust width as needed */
            padding: 20px; /* Add padding to maintain spacing */
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .contact-form, #animation-container {
                width: 100%;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.12/lottie.min.js"></script>
</head>
<body>
   <div class="contact-form">
   <form action="" method="post">
        <h2>Forgot Password</h2>
        <?php if(!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <p class="">Please enter your email</p>
        <br></br>
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="forgot_password_submit">Send OTP</button>
   </form>
   <p class="back-to-login"><a href="customer register.php">Don't have account? Register now</a></p>
   <div id="animation-container"></div>
   </div>
<script>    
var animation = bodymovin.loadAnimation
    ({
        container: document.getElementById('animation-container'), 
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'images/Animation - 1714535466056.json' 
    });
</script>

</body>
</html>