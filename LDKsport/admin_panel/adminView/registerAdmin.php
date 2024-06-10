<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include '../config/dbconnect.php';
require __DIR__ . '../vendor/autoload.php'; // Adjusted path to autoload.php

session_start();

$admin_id = $_SESSION['admin_id'];
$admin_role = $_SESSION['admin_role']; // Get admin role from session


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    try {
        // Check if the email already exists in the database
        $check_email = $conn->prepare("SELECT * FROM `admin` WHERE admin_email = ?");
        $check_email->execute([$email]);
        $existing_email = $check_email->fetch(PDO::FETCH_ASSOC);

        if ($existing_email) {
            $msg = 'Email already registered. Please use another email.';
            $msgType = 'error';
        } else {
            $random_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()'), 0, 8);

            $insert_user = $conn->prepare("INSERT INTO `admin` (admin_name, admin_email, password, role) VALUES (?, ?, ?, 'admin')");
            $insert_user->execute([$name, $email, sha1($random_password)]);

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fyp93345@gmail.com';
            $mail->Password = 'ytpw zgxn pddn pzvs'; // Use environment variables or a secure method for storing credentials
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('fyp93345@gmail.com', 'Noreply');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = 'Hello ' . $name . ',<br>Your Admin new password for LogIn is: ' . $random_password . '<br>You can reset the password while you login the account.';

            $mail->SMTPDebug = 0;

            if ($mail->send()) {
                $msg = 'Email sent successfully.';
                $msgType = 'success';
                $_SESSION['email'] = $email;
            } else {
                $msg = 'Error sending email: ' . $mail->ErrorInfo;
                $msgType = 'error';
            }
        }
    } catch (Exception $e) {
        $msg = 'An error occurred: ' . $e->getMessage();
        $msgType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
<?php
      include __DIR__ . "./adminHeader.php";
      include __DIR__ . "./superadmin_sidebar.php";
?>


    <section class="form-container">

        <form action="" method="post">
            <h3>Register Admin</h3>
            <input type="text" name="name" required placeholder="Enter username" maxlength="255" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="email" name="email" required placeholder="Enter email" maxlength="255" class="box">
            <input type="submit" value="Register" class="update-btn" name="submit">
        </form>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/admin_script.js"></script>
    <script>
        <?php if (isset($msg)): ?>
            Swal.fire({
                icon: '<?= $msgType; ?>',
                title: '<?= $msgType === "success" ? "Success" : "Error"; ?>',
                text: '<?= $msg; ?>'
            });
        <?php endif; ?>
    </script>

</body>

</html>
