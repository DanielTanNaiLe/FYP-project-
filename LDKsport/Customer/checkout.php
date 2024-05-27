<?php
include("header.php");
require '../admin_panel/config/dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header('location:customer_login.php');
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

    header("Location: mastercard.php");
    exit();
}

// Fetch cart items for order summary
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT p.product_name, c.quantity, c.price 
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
            margin: 90px auto 50px auto;
            padding: 30px;
        }

        .checkout_h3 {
            width: 97%;
            background-color: rgb(242, 163, 45);
            text-align: center;
            margin-top: 0;
            padding: 20px;
            color: #333;
        }

        .form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
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

        .order-summary {
            margin-top: 30px;
        }

        .order-summary h4 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            text-align: center;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-summary th,
        .order-summary td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        .order-summary th {
            background-color: #f2f2f2;
        }

        .order-summary tfoot tr td {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h3>Checkout</h3>
    <div class="container">
    <h2>Checkout</h2>
    <form action="checkout.php" method="post">
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
            <input type="text" id="method" name="method" value="MasterCard" readonly required>
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

        <h3>Order Summary</h3>
        <ul>
            <?php foreach ($product as $product): ?>
                <li><?php echo htmlspecialchars($product['product_name']); ?> (x<?php echo $product['quantity']; ?>) - $<?php echo number_format($product['price'], 2); ?></li>
            <?php endforeach; ?>
        </ul>
        <h4>Total: $<?php echo number_format($total_price, 2); ?></h4>

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