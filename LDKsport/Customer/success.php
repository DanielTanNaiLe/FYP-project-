<?php
session_start();

if (!isset($_SESSION['order_id'])) {
    header("Location: mainpage.php");
    exit();
}

$order_id = $_SESSION['order_id'];
$order_details = $_SESSION['order_details'];
$checkout_details = $_SESSION['checkout_details'];

// Unset session variables
unset($_SESSION['order_id']);
unset($_SESSION['order_details']);
unset($_SESSION['checkout_details']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="general.css">
    <style>
        body {
            height: 100%;
            margin: 150px auto auto auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .icon{
            position: relative;
            font-size: 4em;
            color: green;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h3 {
            font-size: 30px;
            text-align: center;
            color: #333;
        }

        .order-summary {
            margin-top: 20px;
        }

        .order-summary h4 {
            margin-bottom: 15px;
            color: #333;
        }

        .order-summary p {
            margin: 8px 0;
            color: #555;
        }

        .btn-home {
            display: block;
            width: 93%;
            background-color: #2864d1;
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-home:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fa fa-check" aria-hidden="true"></i>
        </div>
        <h3>Order Successful!</h3>
        <div class="order-summary">
            <h4>Thank you for your purchase!</h4>
            <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($checkout_details['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($checkout_details['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($checkout_details['number']); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($checkout_details['address']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($checkout_details['method']); ?></p>
            <p><strong>Total Amount:</strong> RM <?php echo htmlspecialchars($checkout_details['total_price']); ?></p>
        </div>
        <a href="mainpage.php" class="btn-home">Go to Home</a>
    </div>
</body>
</html>
