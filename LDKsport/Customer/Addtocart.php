<?php
require '../admin_panel/config/dbconnect.php';

ob_start();

include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:customer_login.php');
    $user_id = '';
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
        // Add item to cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, variation_id, quantity, price) VALUES (?, ?, ?, (SELECT price FROM product_size_variation WHERE variation_id = ?))");
        $stmt->bind_param("iiii", $user_id, $variation_id, $quantity, $variation_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Update the stock quantity
            $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock - ? WHERE variation_id = ?");
            $update_stock_stmt->bind_param("ii", $quantity, $variation_id);
            $update_stock_stmt->execute();

            header("Location: Addtocart.php");
            exit();
        } else {
            echo "Error adding to cart.";
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

        // Remove item from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Update the stock quantity
            $update_stock_stmt = $conn->prepare("UPDATE product_size_variation SET quantity_in_stock = quantity_in_stock + ? WHERE variation_id = ?");
            $update_stock_stmt->bind_param("ii", $quantity, $variation_id);
            $update_stock_stmt->execute();

            header("Location: Addtocart.php");
            exit();
        } else {
            echo "Error removing from cart.";
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

                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating quantity.']);
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
}

td img {
    max-width: 80px;
    height: auto;
    border-radius: 6px;
}

.text-center {
    text-align: center;
}

.quantity-container {
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-container input[type="number"] {
    width: 50px;
    padding: 5px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 0 10px;
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

.total-container {
    box-sizing: border-box;
    display: block;
    align-items: center;
    position: absolute;
    top: 65%; 
    right: 10%; 
    transform: translateY(-50%);
    width: 300px;
}

.total-box {
    background-color: #f2f2f2;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; 
}

.total-box h4 {
    margin: 0;
    font-size: 1.7em;
    color: #333;
}

.total-box h5 {
    margin: 30px 0;
    text-align: center;
    font-size: 1.5em;
    color: #333;
}

.btn-purchase {
    width: 100%;
    background-color: #2864d1;
    color: #fff;
    border: none;
    margin: auto 18px auto auto;
    padding: 10px 70px;
    cursor: pointer;
    border-radius: 4px;
    font-weight: bold;
    text-transform: uppercase;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.btn-purchase:hover {
    background-color: #218838;
}
.empty-cart-message{
    color: #777;
    text-align: center;
}
   
    </style>
</head>
<body>
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
        <?php if (count($cart_items) > 0): 
             foreach ($cart_items as $item): 
             ?>
                <tr>
                    <td><?php echo $item['cart_id']; ?></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><img src="../uploads/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></td>
                    <td><?php echo htmlspecialchars($item['size_name']); ?></td>
                    <td>
                        <div class="quantity-container">
                            <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, -1)">-</button>
                            <input type="number" min="1" id="quantity_<?php echo $item['cart_id']; ?>" value="<?php echo htmlspecialchars($item['quantity']); ?>" onchange="updateQuantity(<?php echo $item['cart_id']; ?>, 0)">
                            <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 1)">+</button>
                        </div>
                    </td>
                    <td id="price_<?php echo $item['cart_id']; ?>">RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <button class="btn-remove" onclick="removeItem(<?php echo $item['cart_id']; ?>)">Remove</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty-cart-message">Your cart is empty. Start adding some products!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="total-container">
        <div class="total-box">
            <h4>Total:</h4>
            <h5 class="text-right" id="totalAmount">RM <?php echo number_format($totalAmount, 2); ?></h5>
            <?php if (count($cart_items) > 0): ?>
            <br>
            <a href="checkout.php" class="btn-purchase">Make Purchase</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
    var cartItems = <?php echo json_encode($cart_items); ?>;
    var totalAmount = <?php echo $totalAmount; ?>;

    function updateQuantity(cart_id, change) {
        var quantityInput = document.getElementById('quantity_' + cart_id);
        var quantity = parseInt(quantityInput.value);

        if (change !== 0) {
            quantity += change;
            if (quantity < 1) {
                quantity = 1;
            }
            quantityInput.value = quantity;
        }

        var pricePerItem = cartItems.find(item => item.cart_id == cart_id).price;
        var priceElement = document.getElementById('price_' + cart_id);
        var totalPrice = pricePerItem * quantity;
        priceElement.textContent = "RM " + totalPrice.toFixed(2);

        // Send AJAX request to update quantity in database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "Addtocart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'error') {
                    alert(response.message);
                }
            }
        };
        xhr.send("action=update_quantity&cart_id=" + cart_id + "&quantity=" + quantity);

        recalculateTotalAmount();
    }

    function recalculateTotalAmount() {
        totalAmount = cartItems.reduce((sum, item) => {
            var quantity = parseInt(document.getElementById('quantity_' + item.cart_id).value);
            return sum + item.price * quantity;
        }, 0);
        document.getElementById('totalAmount').textContent = "RM " + totalAmount.toFixed(2);
    }

    function removeItem(cart_id) {
        if (confirm("Are you sure you want to remove this item?")) {
            window.location.href = "Addtocart.php?action=remove&id=" + cart_id;
        }
    }
</script>
</body>
</html>