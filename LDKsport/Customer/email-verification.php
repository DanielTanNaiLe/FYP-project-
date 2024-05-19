<?php
require '../admin_panel/config/dbconnect.php';

// Function to send verification email
function sendVerificationEmail($conn, $email) {
    // Generate new verification code
    $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

    // Update verification code in the database
    $sql = "UPDATE users SET verification_code = '$verification_code' WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Send email with the new verification code
        $subject = 'Email Verification';
        $message = "<p>Your new verification code is: <b>$verification_code</b></p>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: dtnl0819@gmail.com" . "\r\n"; // Change this to your email address

        if (mail($email, $subject, $message, $headers)) {
            return true; // Email sent successfully
        } else {
            return false; // Email sending failed
        }
    } else {
        return false; // Database update failed
    }
}

if (isset($_POST["verify_email"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $verification_code = mysqli_real_escape_string($conn, $_POST["verification_code"]);

    // Mark email as verified
    $sql = "UPDATE users SET email_verified_at = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0) {
        die("Verification code failed.");
    }

    echo "<p>You can login now.</p>";
    exit();
}

// Resend verification email
if (isset($_POST["resend_verification"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    if (sendVerificationEmail($conn, $email)) {
        echo "<p>New verification code sent successfully.</p>";
    } else {
        echo "<p>Failed to send new verification code. Please try again later.</p>";
    }
}
?>
<style>
    /* Add some basic styling to the form */
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }

    .form-container {
        max-width: 300px;
        margin: 40px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
        display: flex;
        flex-direction: column;
    }

    input[type="text"],
    input[type="hidden"] {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #3e8e41;
    }

    .error {
        color: red;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .success {
        color: green;
        font-size: 12px;
        margin-bottom: 10px;
    }
</style>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* CSS styles remain unchanged */
    </style>
</head>
<body>
<div class="form-container">
    <form method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" required>
        <input type="text" name="verification_code" placeholder="Enter verification code" required />
        <input type="submit" name="verify_email" value="Verify Email">
        <input type="submit" name="resend_verification" value="Resend Verification Code">
    </form>
</div>
</body>
</html>
