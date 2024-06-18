<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/../admin_panel/config/dbconnect.php";

if ($mysqli->connect_error) {
    die("Database connection error: " . $mysqli->connect_error);
}

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$result = $stmt->execute();

if ($result === false) {
    die("Execute failed: " . $stmt->error);
}

if ($stmt->affected_rows) {
    // Your code for sending the email
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("dtnl0819@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Click <a href="http://localhost/FYP-project-/LDKsport/Customer/reset-password.php?token=$token">here</a> 
    to reset your password.
 
    END;

    // Send email
    if ($mail->send()) {
        echo "<script>
                alert('Message sent, please check your inbox.');
                window.location.href = 'customer login.php';
              </script>";
    } else {
        echo "<script>
                alert('Message cannot be sent.');
                window.location.href = 'customer login.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No rows affected.');
            window.location.href = 'customer login.php';
          </script>";
}

?>
