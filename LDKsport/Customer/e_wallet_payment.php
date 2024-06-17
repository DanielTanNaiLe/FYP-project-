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
    $verificationCode = filter_var($_POST['verificationCode'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING); // Retrieve the description
    $user_id = $_SESSION['user_id'];
    $amount = $_SESSION['checkout_details']['total_price']; // Use the session value for amount

    if ($amount > 0 && !empty($description)) { // Check that description is not empty
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
                $stmt = $conn->prepare("INSERT INTO e_wallet_balance (user_id, amount, description, transaction_date) VALUES (?, ?, ?, NOW())");
                if (!$stmt) {
                    throw new Exception($conn->error);
                }
                $stmt->bind_param("isd", $user_id, $negative_amount, $description); // Bind the description here

                if (!$stmt->execute()) {
                    throw new Exception("Error inserting into e_wallet_balance: " . $stmt->error);
                }
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

                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
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
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                    $stmt->close();
                }

                // Clear cart and session variables
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                $stmt->close();

                unset($_SESSION['cart']);
                unset($_SESSION['checkout_details']);

                $conn->commit();

                // Redirect to the order confirmation page
                header("Location: order.php?order_id=$order_id");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Transaction failed: " . $e->getMessage());
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
        input, .amount-display {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .amount-display {
            background-color: #f0f0f0;
            color: #333;
            text-align: center;
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
           <label for="amount">Amount:</label>
           <div class="amount-display">RM <?= htmlspecialchars($_SESSION['checkout_details']['total_price']) ?></div>
    
           <label for="description">Description:</label>
           <input type="text" id="description" name="description" placeholder="Enter payment description" required>
    
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
