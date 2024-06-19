<?php
require '../admin_panel/config/dbconnect.php';
require 'mailer.php'; // Ensure this includes the PHPMailer library

session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['checkout_details'])) {
    header("Location: checkout.php");
    exit();
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['otp'])) {
        $enteredOtp = $_POST['otp'];

        if ($enteredOtp == $_SESSION['otp']) {
            $checkout_details = $_SESSION['checkout_details'];
            $user_id = $_SESSION['user_id'];
            $amount = isset($_SESSION['discounted_total_price']) ? $_SESSION['discounted_total_price'] : $_SESSION['checkout_details']['total_price'];
            $checkout_details['total_price'] = $amount;

            // Insert into orders table
            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, order_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("isssssd", 
                $user_id, 
                $checkout_details['name'], 
                $checkout_details['email'], 
                $checkout_details['number'], 
                $checkout_details['address'], 
                $checkout_details['method'], 
                $checkout_details['total_price']
            );
            
            if ($stmt->execute() === false) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }

            $order_id = $stmt->insert_id;

            // Insert order details if cart is set and is an array
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $stmt = $conn->prepare("
                    INSERT INTO order_details (order_id, variation_id, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ");
                
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                foreach ($_SESSION['cart'] as $item) {
                    $stmt->bind_param("iiid", $order_id, $item['variation_id'], $item['quantity'], $item['price']);
                    
                    if ($stmt->execute() === false) {
                        die('Execute failed: ' . htmlspecialchars($stmt->error));
                    }
                }

                // Clear cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                $stmt->bind_param("i", $user_id);
                
                if ($stmt->execute() === false) {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
            }

            // Set session variables for success page
            $_SESSION['order_id'] = $order_id;
            $_SESSION['order_details'] = $_SESSION['cart'];
            $_SESSION['checkout_details'] = $checkout_details;

            // Unset OTP and cart from session
            unset($_SESSION['otp']);
            unset($_SESSION['cart']);

            // Redirect to success page
            header("Location: success.php");
            exit();
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['resend_otp'])) {
        // Generate new OTP and save it in the session
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        // Code to send the new OTP to the user's email
        $email = $_SESSION['checkout_details']['email'];
        if (sendOtp($email, $otp)) {
            $success = "A new OTP has been sent to your email.";
        } else {
            $error = "Failed to send OTP. Please check your email settings or try again later.";
        }
    }
}

function sendOtp($email, $otp) {
    global $mail; // Use the globally configured PHPMailer instance

    try {
        $mail->setFrom('liangyuel44@gmail.com', 'LDK Sport');
        $mail->addAddress($email);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your OTP code is: $otp";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        body {
            height: 100%;
            margin: 250px auto auto auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional */
            border-radius: 8px; /* Optional */
        }

        h3 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group input:focus {
            border-color: #2864d1;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            background-color: #2864d1;
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-secondary {
            width: 100%;
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>OTP Verification</h3>
        <form action="otp_verification.php" method="post">
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn-primary">Submit</button>
        </form>
        <form action="otp_verification.php" method="post">
            <button type="submit" name="resend_otp" class="btn-secondary">Resend OTP</button>
        </form>
        <?php if ($error) { ?>
            <div class="message" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="message" style="color: green;"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>
    </div>
</body>
</html>
