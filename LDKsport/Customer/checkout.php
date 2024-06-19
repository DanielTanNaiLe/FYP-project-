<?php
ob_start(); // Start output buffering

include("header.php");
require '../admin_panel/config/dbconnect.php';

// Ensure discount session variables are reset before a new checkout session
unset($_SESSION['discount_applied']);
unset($_SESSION['discount_amount']);

if (!isset($_SESSION['user_id'])) {
    header('location:customer login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['promocode'])) {
        // Promocode validation and application
        $promocode = filter_var($_POST['promocode'], FILTER_SANITIZE_STRING);
        
        // Check if the promocode is valid
        $stmt = $conn->prepare("SELECT * FROM promocode WHERE code = ? AND stock > 0 AND created_at <= NOW()");
        $stmt->bind_param("s", $promocode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $promocode_data = $result->fetch_assoc();
            $discount = $promocode_data['discount'];
            $new_total_price = $_SESSION['total_price'] - $discount;

            // Update the stock and usage count of the promocode
            $update_stmt = $conn->prepare("UPDATE promocode SET stock = stock - 1 WHERE id = ?");
            $update_stmt->bind_param("i", $promocode_data['id']);
            $update_stmt->execute();

            $_SESSION['total_price'] = $new_total_price;
            $_SESSION['discount_applied'] = true;
            $_SESSION['discount_amount'] = $discount;
        } else {
            $_SESSION['message'] = 'Invalid or expired promocode, or already used.';
        }
    } else {
        // Sanitize and save checkout details
        $_SESSION['checkout_details'] = [
            'name' => filter_var($_POST['First_name'], FILTER_SANITIZE_STRING) . ' ' . filter_var($_POST['Last_name'], FILTER_SANITIZE_STRING),
            'number' => filter_var($_POST['number'], FILTER_SANITIZE_STRING),
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'method' => filter_var($_POST['method'], FILTER_SANITIZE_STRING),
            'address' => 'no. ' . filter_var($_POST['flat'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['street'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['city'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['state'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['country'], FILTER_SANITIZE_STRING) . ' - ' . filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING),
            'total_products' => filter_var($_POST['total_products'], FILTER_SANITIZE_STRING),
            'total_price' => filter_var($_POST['total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        ];

        // Ensure cart items are set in the session
        $_SESSION['cart'] = [];
        $stmt = $conn->prepare("SELECT c.variation_id, c.quantity, c.price FROM cart c WHERE c.user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $_SESSION['cart'][] = $row;
        }

        // Clear discount session variables
        unset($_SESSION['discount_applied']);
        unset($_SESSION['discount_amount']);

        // Redirect based on the payment method
        $payment_method = $_SESSION['checkout_details']['method'];
        if ($payment_method == 'credit_card') {
            header("Location: mastercard.php");
        } elseif ($payment_method == 'E-Wallet') {
            header("Location: e_wallet_payment.php");
        } else {
            header("Location: mastercard.php");
        }
        exit();
    }
}

// Fetch cart items for order summary
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

// Calculate total price with discount if applicable
if (isset($_SESSION['discount_applied']) && $_SESSION['discount_applied']) {
    $discounted_price = $total_price - $_SESSION['discount_amount'];
} else {
    $discounted_price = $total_price;
}

$_SESSION['total_price'] = $total_price;
$_SESSION['discounted_total_price'] = $discounted_price;

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
        /* Styles for checkout page */
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
            display: flex;
            flex-direction: column;
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

        .order-summary h4 {
            margin: 20px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 24px;
            color: #333;
            background-color: #f2f2f2;
        }

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
        }

        .order-summary ul li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .order-summary ul li:hover {
            transform: translateY(-5px);
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
            <?php foreach ($product as $item): ?>
                <li>
                    <img src="../admin_panel/uploads/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></span>
                    <span> - RM <?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <h4>Total: RM <?php echo isset($_SESSION['discount_applied']) && $_SESSION['discount_applied'] ? number_format($total_price - $_SESSION['discount_amount'], 2) : number_format($total_price, 2); ?></h4>
        <?php if (isset($_SESSION['discount_applied']) && $_SESSION['discount_applied']): ?>
            <h4>Discount Applied: - RM <?php echo number_format($_SESSION['discount_amount'], 2); ?></h4>
            <h4>New Total: RM <?php echo number_format($total_price - $_SESSION['discount_amount'], 2); ?></h4>
        <?php endif; ?>

        <!-- Promocode form -->
        <form id="promocode-form" action="checkout.php" method="post" style="display: flex; flex-direction: column; align-items: center;">
            <div class="form-group" style="width: 100%;">
                <label for="promocode">Enter Promocode:</label>
                <input type="text" id="promocode" name="promocode" style="width: 100%; margin-bottom: 10px;" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Apply Promocode</button>
        </form>

        <?php if (isset($_SESSION['message'])): ?>
            <p style="color: red;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>
    </div>
    <form action="checkout.php" method="post" class="form">
        <div class="form-group">
            <label for="First_name">First Name</label>
            <input type="text" name="First_name" id="First_name" value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Last_name">Last Name</label>
            <input type="text" name="Last_name" id="Last_name" value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="number">Phone Number</label>
            <input type="text" name="number" id="number" value="<?php echo htmlspecialchars($user_data['contact_no']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="method">Payment Method</label>
            <select name="method" id="method" required>
                <option value="credit_card">Credit card</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
        </div>
        <div class="form-group">
            <label for="flat">Flat No.</label>
            <input type="text" name="flat" id="flat" value="<?php echo htmlspecialchars($user_data['flat_no']); ?>" required>
        </div>
        <div class="form-group">
            <label for="street">Street Name</label>
            <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($user_data['street_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($user_data['city']); ?>" required>
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($user_data['state']); ?>" required>
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($user_data['country']); ?>" required>
        </div>
        <div class="form-group">
            <label for="pin_code">Pin Code</label>
            <input type="text" name="pin_code" id="pin_code" required>
        </div>
        <input type="hidden" name="total_products" value="<?php echo htmlspecialchars($total_products); ?>">
        <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
        <button type="submit" class="btn-primary">Place Order</button>
    </form>
</div>
</body>
</html>

<?php
include("footer.php");
?>
