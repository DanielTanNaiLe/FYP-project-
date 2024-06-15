<?php
session_start();
require '../admin_panel/config/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: customer_login.php');
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $walletID = filter_var($_POST['walletID'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $user_id = $_SESSION['user_id'];

    if ($walletID && $amount > 0) {
        // Check user's current balance
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS balance FROM e_wallet_balance WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_balance = $row['balance'];
        $stmt->close();

        // Check if user has enough balance
        if ($current_balance >= $amount) {
            $conn->begin_transaction();

            try {
                // Deduct the amount from the balance
                $negative_amount = -$amount; // Use a variable for the negative amount
                $stmt = $conn->prepare("INSERT INTO e_wallet_balance (user_id, amount, transaction_date) VALUES (?, ?, NOW())");
                $stmt->bind_param("id", $user_id, $negative_amount);
                $stmt->execute();
                $stmt->close();

                // Process the order as usual
                $checkout_details = $_SESSION['checkout_details'];
                $cart_items = $_SESSION['cart'];

                $stmt = $conn->prepare("
                    INSERT INTO orders (user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, order_date, order_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 0)
                ");
                $stmt->bind_param(
                    "isssssd",
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
                $stmt->close();

                // Insert order items into the database
                foreach ($cart_items as $item) {
                    $stmt = $conn->prepare("
                        INSERT INTO order_details (order_id, variation_id, quantity, price)
                        VALUES (?, ?, ?, ?)
                    ");
                    $stmt->bind_param(
                        "iiid",
                        $order_id,
                        $item['variation_id'],
                        $item['quantity'],
                        $item['price']
                    );
                    $stmt->execute();
                    $stmt->close();
                }

                // Clear cart and session variables
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();

                unset($_SESSION['cart']);
                unset($_SESSION['checkout_details']);

                $conn->commit();

                // Redirect to the order confirmation page
                header("Location: order.php?order_id=$order_id");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Failed to process payment. Please try again.";
            }
        } else {
            $error = "Insufficient balance.";
        }
    } else {
        $error = "Invalid payment details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Wallet Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        #message {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>E-Wallet Payment</h2>
        <form id="paymentForm" method="POST">
            <label for="walletID">Wallet ID:</label>
            <input type="text" id="walletID" name="walletID" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($_SESSION['checkout_details']['total_price']) ?>" required>
            
            <label for="verificationCode">Verification Code:</label>
            <input type="text" id="verificationCode" name="verificationCode" placeholder="Enter the 6-digit code" required>
            
            <button type="submit">Pay</button>
        </form>
        <div id="message">
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
