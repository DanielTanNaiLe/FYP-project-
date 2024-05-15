<?php
require '../admin_panel/config/dbconnect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header("Location: customer login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['pid'];
    $size_id = $_POST['size_name'];
    $quantity = $_POST['Quantity'];
    $price = $_POST['price'];

    // Get the variation_id from product_size_variation table
    $stmt = $conn->prepare("SELECT variation_id FROM product_size_variation WHERE product_id = ? AND size_id = ?");
    $stmt->bind_param("ii", $pid, $size_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $variation = $result->fetch_assoc();

    if ($variation) {
        $variation_id = $variation['variation_id'];

        // Insert into cart table
        $stmt = $conn->prepare("INSERT INTO cart (user_id, variation_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $user_id, $variation_id, $quantity, $price);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Error adding to cart.";
        }
    } else {
        echo "Product variation not found.";
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $cart_id = $_GET['id'];

    // Remove item from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: cart.php");
        exit();
    } else {
        echo "Error removing from cart.";
    }
}

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.cart_id, p.product_name, p.product_image, s.size_name, c.quantity, c.price 
    FROM cart c
    JOIN product_size_variation v ON c.variation_id = v.variation_id
    JOIN products p ON v.product_id = p.product_id
    JOIN sizes s ON v.size_id = s.size_id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$totalAmount = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $totalAmount += $row['quantity'] * $row['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            margin-top: 124px;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            overflow-x: auto;
        }

        h2 {
            text-align: center;
            margin-top: 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td img {
            max-width: 80px;
            height: auto;
            border-radius: 6px;
        }

        .text-center {
            text-align: center;
        }

        .btn-remove {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php include("header.php"); ?>
    <div class="container">
        <h2>Shopping Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Cart ID</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['cart_id']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><img src="../uploads/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></td>
                            <td><?php echo htmlspecialchars($item['size_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <button class="btn-remove" onclick="removeItem(<?php echo $item['cart_id']; ?>)">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Your cart is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td><strong>Total:</strong></td>
                    <td>$<?php echo number_format($totalAmount, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        function removeItem(cart_id) {
            if (confirm("Are you sure you want to remove this item?")) {
                window.location.href = "cart.php?action=remove&id=" + cart_id;
            }
        }
    </script>
</body>
</html>