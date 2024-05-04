<?php

$user_email = $_POST["user_email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Include the dataconnection.php file and store the returned mysqli object in a variable
$mysqli = include __DIR__ . "/dataconnection.php";

// Check if $mysqli is a boolean (indicating an error) or a MySQLi object
if ($mysqli === false) {
    // If $mysqli is false, it means there was an error in the connection
    die("Database connection error.");
}

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE user_email = ?";

$stmt = $mysqli->prepare($sql);

// Check if the prepare operation succeeded
if ($stmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind parameters
$bindResult = $stmt->bind_param("sss", $token_hash, $expiry, $user_email);

if (!$bindResult) {
    die("Binding parameters failed: " . $stmt->error);
}

// Execute the statement
$executeResult = $stmt->execute();

if (!$executeResult) {
    die("Execution of statement failed: " . $stmt->error);
}

// Check for affected rows
if ($stmt->affected_rows) {

    // Assuming the mailer.php returns an instance of PHPMailer
    $mail = require __DIR__ . "/mailer.php";

    // Check if $mail is a boolean (indicating an error) or a PHPMailer object
    if ($mail === false) {
        die("Email setup error.");
    }

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($user_email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Click <a href="http://example.com/reset-password.php?token=$token">here</a> 
    to reset your password.
    END;

    try {
        // Send the email
        $sendResult = $mail->send();
        if (!$sendResult) {
            die("Message could not be sent. Mailer error: {$mail->ErrorInfo}");
        }
        echo "Message sent, please check your inbox.";
    } catch (Exception $e) {
        // Handle mailer errors
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }

} else {
    echo "No rows affected.";
}

?>
