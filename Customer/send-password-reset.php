<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Include the dataconnection.php file and store the returned mysqli object in a variable
$mysqli = include __DIR__ . "/dataconnection.php";

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

// Check if the prepare operation succeeded
if ($stmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind parameters
$stmt->bind_param("sss", $token_hash, $expiry, $email);

// Execute the statement
$stmt->execute();

// Check for affected rows
if ($stmt->affected_rows) {

    // Assuming the mailer.php returns an instance of PHPMailer
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://example.com/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {
        // Send the email
        $mail->send();
        echo "Message sent, please check your inbox.";
    } catch (Exception $e) {
        // Handle mailer errors
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }

} else {
    echo "No rows affected.";
}

?>
