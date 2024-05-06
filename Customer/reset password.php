<?php
include 'dataconnection.php';
session_start();

// Check if email is verified (based on the session variable set in verify_otp.php)
if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
    // Redirect user to verify OTP if email is not verified
    header('location: verify_otp.php');
    exit();
}

$message = ''; // Define $message variable to avoid undefined variable error

if (isset($_POST['reset_password_submit'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

    // Check if the two passwords match
    if ($password === $confirm_password) {
        // Hash the new password before storing it in the database
        $hashed_password = sha1($password); // Consider using password_hash()

        // Update the user's password in the database
        $update_password = $conn->prepare("UPDATE users SET password = ? WHERE user_email = ?");
        if ($update_password->execute([$hashed_password, $_SESSION['email']])) {
            // If the update is successful, show an alert and redirect to customer register.php
            echo "<script>alert('Password reset successful!'); window.location.href='customer register.php';</script>";
            exit();
        } else {
            $message = 'Failed to update password.';
        }
    } else {
        $message = 'Passwords do not match.';
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type=password] {
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
    </style>
</head>
<body>
<?php include 'dataconnection.php'; ?>
    <div class="container"> 
        <h2>Reset Password</h2>
        <?php if ($message !== ''): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <input type="password" name="password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit" name="reset_password_submit">Reset Password</button>
        </form>
    </div>
</body>
</html>