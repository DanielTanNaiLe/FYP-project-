<?php
ob_start();
include("header.php");
require '../admin_panel/config/dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header('location:customer login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $_SESSION['checkout_details'] = [
        'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
        'number' => filter_var($_POST['number'], FILTER_SANITIZE_STRING),
        'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
        'method' => filter_var($_POST['method'], FILTER_SANITIZE_STRING),
        'address' => 'no. ' . filter_var($_POST['flat'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['street'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['city'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['state'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['country'], FILTER_SANITIZE_STRING) . ' - ' . filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING),
        'total_products' => filter_var($_POST['total_products'], FILTER_SANITIZE_STRING),
        'total_price' => filter_var($_POST['total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
    ];

    // Ensure cart items are set in the session
    $_SESSION['cart'] = [];
    $stmt = $conn->prepare("
        SELECT c.variation_id, c.quantity, c.price 
        FROM cart c
        WHERE c.user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $_SESSION['cart'][] = $row;
    }

    $payment_method = $_SESSION['checkout_details']['method'];
    if ($payment_method == 'Visa') {
        header("Location: visa_payment.php");
    } elseif ($payment_method == 'E-Wallet') {
        header("Location: e_wallet_payment.php");
    } else {
        header("Location: mastercard.php");
    }
    exit();
}

// Fetch cart items for order summary
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT p.product_name, p.product_image, c.quantity, c.price 
    FROM cart c
    JOIN product_size_variation v ON c.variation_id = v.variation_id
    JOIN product p ON v.product_id = p.product_id
    WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$product = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $product[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            margin: auto;
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            max-width: 1200px;
        }

        .checkout_h2 {
            width: 100%;
            background-color: rgb(242, 163, 45);
            margin-top: 120px;
            text-align: center;
            padding: 20px;
            color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form, .order-summary {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form {
            flex: 1 1 60%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .order-summary {
            flex: 1 1 35%;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group input:focus,
        .form-group select:focus {
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
            grid-column: 1 / -1;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .order-summary h4,
        .order-summary h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            text-align: center;
        }

        .order-summary ul {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            list-style: none;
            padding: 0;
            transition: transform 0.3s ease;
        }
        .order-summary ul:hover{
            transform: translateY(-5px);
        }
        .order-summary ul li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
        }

        .order-summary ul li img {
            width: 60px;
            height: 60px;
            margin-right: 10px;
            object-fit: cover;
            border-radius: 50%;
        }

        .order-summary ul li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
<div class="container">
        <h2 class="checkout_h2">Checkout</h2>
        <div class="order-summary">
            <h3>Order Summary</h3>
            <ul>
                <?php foreach ($product as $product): ?>
                    <li>
                        <img src="../uploads/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <span><?php echo htmlspecialchars($product['product_name']); ?></span>
                        <span>(x<?php echo $product['quantity']; ?>)</span>
                        <span>- RM <?php echo number_format($product['price'] * $product['quantity'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <h4>Total: $<?php echo number_format($total_price, 2); ?></h4>
        </div>
        <form action="checkout.php" method="post" class="form">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="number">Phone Number:</label>
                <input type="text" id="number" name="number" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="method">Payment Method:</label>
                <select id="method" name="method" required>
                    <option value="MasterCard">MasterCard</option>
                    <option value="Visa">Visa</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>
            <div class="form-group">
                <label for="flat">Flat No:</label>
                <input type="text" id="flat" name="flat" required>
            </div>
            <div class="form-group">
                <label for="street">Street Name:</label>
                <input type="text" id="street" name="street" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" required>
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>
            </div>
            <div class="form-group">
                <label for="pin_code">Post Code:</label>
                <input type="text" id="pin_code" name="pin_code" required>
            </div>
            <input type="hidden" name="total_products" value="<?php echo count($product); ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <button type="submit" class="btn-primary">Proceed to Payment</button>
    </form>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<p style="color: red; text-align: center;">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
</div>
    <?php include("footer.php"); ?>
</body>
</html>