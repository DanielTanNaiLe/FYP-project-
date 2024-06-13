<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include_once "../config/dbconnect.php";
require __DIR__ . '/../vendor/autoload.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['admin_name'];
    $email = $_POST['admin_email'];

    try {
        // Check if the email already exists in the database
        $query = "SELECT * FROM `admin` WHERE admin_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing_email = $result->fetch_assoc();

        if ($existing_email) {
            $_SESSION['msg'] = 'Email already registered. Please use another email.';
            $_SESSION['msgType'] = 'error';
        } else {
            // Generate a random password
            $random_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'), 0, 8);
            // Hash the password
            $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);

            // Insert the new admin into the database
            $query = "INSERT INTO `admin` (admin_name, admin_email, password, role) VALUES (?, ?, ?, 'admin')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            $stmt->execute();

            // Send the email with the new password
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0; // Disable verbose debug output for production
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'fyp93345@gmail.com';
                $mail->Password = 'ytpw zgxn pddn pzvs'; // Remove spaces in the password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('fyp93345@gmail.com', 'Noreply');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body = 'Hello ' . $name . ',<br>Your Admin new password for Login is: ' . $random_password . '<br>You can reset the password while you login the account.';

                if ($mail->send()) {
                    $_SESSION['msg'] = 'Email sent successfully.';
                    $_SESSION['msgType'] = 'success';
                } else {
                    $_SESSION['msg'] = 'Error sending email: ' . $mail->ErrorInfo;
                    $_SESSION['msgType'] = 'error';
                }
            } catch (Exception $e) {
                $_SESSION['msg'] = 'Error sending email: ' . $mail->ErrorInfo;
                $_SESSION['msgType'] = 'error';
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = 'An error occurred: ' . $e->getMessage();
        $_SESSION['msgType'] = 'error';
    }
    header('Location: ../dashboard.php');
    exit();
}
?>
