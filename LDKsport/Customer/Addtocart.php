<?php
require '../admin_panel/config/dbconnect.php';

ob_start();

include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:customer_login.php');
    exit();
}

require '../admin_panel/wishlist_cart.php';

// Handle adding to cart (Example logic, adjust based on your actual add to cart process)
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart' && isset($_POST['variation_id']) && isset($_POST['quantity'])) {
    $variation_id = $_POST['variation_id'];
    $quantity = $_POST['quantity'];

    // Check stock availability
    $stock_stmt = $conn->prepare("SELECT quantity_in_stock FROM product_size_variation WHERE variation_id = ?");
    $stock_stmt->bind_param("i", $variation_id);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $stock = $stock_result->fetch_assoc();

    if ($stock && $stock['quantity_in_stock'] >= $quantity) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Add item to cart
            $stmt = $conn->prepare("INSERT INTO cart (user_id, variation_id, quantity, price) VALUES (?, ?, ?, (SELECT price FROM product_size_variation WHERE variation_id = ?))");
            $stmt->bind_param("iiii", $user_id, $variation_id, $quantity, $variation_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Update the stock quantity
                $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock - ? WHERE variation_id = ?");
                $update_stock_stmt->bind_param("ii", $quantity, $variation_id);
                $update_stock_stmt->execute();

                if ($update_stock_stmt->affected_rows > 0) {
                    // Commit transaction
                    $conn->commit();
                    header("Location: Addtocart.php");
                    exit();
                } else {
                    throw new Exception("Error updating stock quantity.");
                }
            } else {
                throw new Exception("Error adding to cart.");
            }
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            echo $e->getMessage();
        }
    } else {
        echo "Not enough stock available.";
    }
}

// Handle cart item removal
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $cart_id = $_GET['id'];

    // Fetch the cart item details
    $stmt = $conn->prepare("SELECT variation_id, quantity FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $variation_id = $item['variation_id'];
        $quantity = $item['quantity'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // Remove item from cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $cart_id, $user_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Update the stock quantity
                $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock + ? WHERE variation_id = ?");
                $update_stock_stmt->bind_param("ii", $quantity, $variation_id);
                $update_stock_stmt->execute();

                if ($update_stock_stmt->affected_rows > 0) {
                    // Commit transaction
                    $conn->commit();
                    header("Location: Addtocart.php");
                    exit();
                } else {
                    throw new Exception("Error updating stock quantity.");
                }
            } else {
                throw new Exception("Error removing from cart.");
            }
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            echo $e->getMessage();
        }
    }
}

// Handle quantity update via AJAX
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    // Fetch the cart item details
    $stmt = $conn->prepare("SELECT variation_id, quantity FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $variation_id = $item['variation_id'];
        $current_quantity = $item['quantity'];

        // Check stock availability
        $stock_stmt = $conn->prepare("SELECT quantity_in_stock FROM product_size_variation WHERE variation_id = ?");
        $stock_stmt->bind_param("i", $variation_id);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        $stock = $stock_result->fetch_assoc();

        if ($stock && $stock['quantity_in_stock'] >= ($new_quantity - $current_quantity)) {
            // Start transaction
            $conn->begin_transaction();

            try {
                // Update the cart quantity
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
                $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Update the stock quantity
                    $update_stock_qty = $new_quantity - $current_quantity;
                    $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock - ? WHERE variation_id = ?");
                    $update_stock_stmt->bind_param("ii", $update_stock_qty, $variation_id);
                    $update_stock_stmt->execute();

                    if ($update_stock_stmt->affected_rows > 0) {
                        // Commit transaction
                        $conn->commit();
                        echo json_encode(['status' => 'success']);
                    } else {
                        throw new Exception("Error updating stock quantity.");
                    }
                } else {
                    throw new Exception("Error updating cart quantity.");
                }
            } catch (Exception $e) {
                // Rollback transaction
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Not enough stock available.']);
        }
    }
    exit();
}

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.cart_id, p.product_name, p.product_image, s.size_name, c.quantity, c.price, v.quantity_in_stock
    FROM cart c
    JOIN product_size_variation v ON c.variation_id = v.variation_id
    JOIN product p ON v.product_id = p.product_id
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
body {
    background-color: #f9f9f9;
}

.container {
    background-color: #fff;
    margin: auto;
    height: 70%;
    padding: 20px;
    overflow-x: auto;
    position: relative;
}

h2 {
    width: 90%;
    background-color: #F2A32D;
    text-align: center;
    margin-left: 45px;
    margin-top: 130px;
    padding: 20px;
    color: #333;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

table {
    width: 65%;
    margin-left: 3.5%;
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
    color: #333;
}

td {
    background-color: #fff;
    color: #666;
}

.btn {
    padding: 10px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

.btn-remove {
    background-color: #dc3545;
}

.btn-remove:hover {
    background-color: #c82333;
}

.btn-update {
    background-color: #28a745;
}

.btn-update:hover {
    background-color: #218838;
}

.total {
    text-align: right;
    padding: 10px;
    font-size: 18px;
    font-weight: bold;
}

.container .shopping-cart tbody .cart-item {
    position: relative;
    overflow-x: auto;
}

.quantity-input {
    width: 60px;
    padding: 5px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 4px;
}

#remove, #update, #checkout {
    display: flex;
    justify-content: center;
    margin-top: 15px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Shopping Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="shopping-cart">
                <?php foreach ($cart_items as $item): ?>
                    <tr class="cart-item">
                        <td><?php echo $item['product_name']; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($item['product_image']); ?>" alt="Product Image" width="50" height="50"></td>
                        <td><?php echo $item['size_name']; ?></td>
                        <td>
                            <input type="number" class="quantity-input" data-cart-id="<?php echo $item['cart_id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['quantity_in_stock']; ?>">
                        </td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                        <td>
                            <form method="GET" action="Addtocart.php" id="remove">
                                <input type="hidden" name="id" value="<?php echo $item['cart_id']; ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" class="btn btn-remove">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">
            Total Amount: $<?php echo number_format($totalAmount, 2); ?>
        </div>
        <div id="checkout">
            <a href="checkout.php" class="btn btn-update">Proceed to Checkout</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.quantity-input').on('change', function() {
                var cartId = $(this).data('cart-id');
                var quantity = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: 'Addtocart.php',
                    data: {
                        action: 'update_quantity',
                        cart_id: cartId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
include("footer.php");
ob_end_flush();
?>
