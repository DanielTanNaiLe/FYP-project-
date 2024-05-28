<?php
include("header.php");
require '../admin_panel/config/dbconnect.php';

session_start();

if (!isset($_SESSION['otp']) || !isset($_SESSION['checkout_details'])) {
    header("Location: checkout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $enteredOtp = $_POST['otp'];

    if ($enteredOtp == $_SESSION['otp']) {
        $checkout_details = $_SESSION['checkout_details'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, order_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("isssssd", 
            $user_id, 
            $checkout_details['name'], 
            $checkout_details['email'], 
            $checkout_details['number'], 
            $checkout_details['address'], 
            $checkout_details['method'], 
            $checkout_details['total_price']
        );
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert order details
        foreach ($_SESSION['cart'] as $item) {
            $stmt = $conn->prepare("
                INSERT INTO order_details (order_id, product_name, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("isid", $order_id, $item['product_name'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Unset session variables
        unset($_SESSION['otp']);
        unset($_SESSION['checkout_details']);
        unset($_SESSION['cart']);

        $success = "Payment successful and order placed!";
    } else {
        $error = "Invalid OTP. Please try again.";
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
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            margin: 90px auto 50px auto;
            padding: 30px;
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
        <?php if (isset($error)) { ?>
            <div class="message" style="color: red;"><?php echo $error; ?></div>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <div class="message" style="color: green;"><?php echo $success; ?></div>
        <?php } ?>
    </div>
</body>
</html>