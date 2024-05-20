<?php
include("header.php");
require '../admin_panel/config/dbconnect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:customer login.php');
    exit();
}

// Process order placement
if (isset($_POST['order'])) {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    $address = 'flat no. ' . filter_var($_POST['flat'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['street'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['city'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['state'], FILTER_SANITIZE_STRING) . ', ' . filter_var($_POST['country'], FILTER_SANITIZE_STRING) . ' - ' . filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);
    $total_products = filter_var($_POST['total_products'], FILTER_SANITIZE_STRING);
    $total_price = filter_var($_POST['total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Check if cart is not empty
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->bind_param("i", $user_id);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();

    if ($cart_result->num_rows > 0) {
        // Insert order details into the orders table
        $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $insert_order->bind_param("isssssd", $user_id, $name, $email, $number, $address, $method, $total_price);
        $insert_order->execute();

        if ($insert_order->affected_rows > 0) {
            // Clear cart after order placement
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->bind_param("i", $user_id);
            $delete_cart->execute();

            $_SESSION['message'] = 'Order placed successfully!';
        } else {
         $_SESSION['message'] = 'Failed to place order. Please try again.';
        }
    } else {
      $_SESSION['message'] = 'Your cart is empty.';
    }
}

// Fetch cart items for order summary
$stmt = $conn->prepare("
    SELECT p.product_name, c.quantity, c.price 
    FROM cart c
    JOIN product_size_variation v ON c.variation_id = v.variation_id
    JOIN product p ON v.product_id = p.product_id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_amount = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['quantity'] * $row['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="general.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>

body {
    background-color: #f9f9f9;
}

.container {
    background-color: #fff;
    margin: 100px auto 50px auto;
    padding: 20px;
    width: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h3 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
}

.btn-primary {
    width: 100%;
    background-color: #2864d1;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #218838;
}

.order-summary {
    margin-top: 30px;
}

.order-summary h4 {
    margin-bottom: 10px;
}

.order-summary table {
    width: 100%;
    border-collapse: collapse;
}

.order-summary th, .order-summary td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
}
    </style>
</head>
<body>
    <div class="container">
        <h3>Checkout</h3>
        <?php
        if (isset($_SESSION['message'])) {
                echo '<p style="color: red; text-align: center;">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
        }
        ?>
        <form action="checkout.php" method="post">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="number">Your Number:</label>
                <input type="text" id="number" name="number" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="method">Payment Method:</label>
                <select id="method" name="method" required>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paypal">PayPal</option>
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
                <label for="pin_code">Pin Code:</label>
                <input type="text" id="pin_code" name="pin_code" required>
            </div>
            <input type="hidden" name="total_products" value="<?php echo htmlspecialchars(json_encode($cart_items)); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_amount); ?>">
            <button type="submit" name="order" class="btn-primary">Place Order</button>
        </form>
        <div class="order-summary">
            <h4>Order Summary</h4>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4>Total Amount: $<?php echo number_format($total_amount, 2); ?></h4>
        </div>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>