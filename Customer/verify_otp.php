<?php
include 'dataconnection.php';
session_start();

if(isset($_POST['verify_otp_submit'])) {
    $otp_entered = $_POST['otp'];
    $otp_entered = filter_var($otp_entered, FILTER_SANITIZE_STRING);

    // Check if the OTP entered by the user matches the one stored in the database
    $select_user = $conn->prepare("SELECT * FROM users WHERE user_email = ? AND otp = ?");
    $select_user->execute([$_SESSION['email'], $otp_entered]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0) {
        // If OTP is correct, allow user to reset password
        $_SESSION['email_verified'] = true; // Set a session variable to indicate email verification
        header('location: reset password.php');
        exit();
    } else {
        $message = 'Invalid OTP';
    }
}

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type=text] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #5cb85c;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .message {
            color: #d9534f;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <?php if(isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form action="" method="post">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp_submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>