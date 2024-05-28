<?php
require '../admin_panel/config/dbconnect.php';

 include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:customer login.php');
    $user_id ='';
}

require '../admin_panel/wishlist_cart.php';

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $cart_id = $_GET['id'];

    // Remove item from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: Addtocart.php");
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
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo $item['cart_id']; ?></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><img src="../uploads/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></td>
                        <td><?php echo htmlspecialchars($item['size_name']); ?></td>
                        <td>
                            <input type="number" min="1" value="<?php echo htmlspecialchars($item['quantity']); ?>" onchange="updateQuantity(<?php echo $item['cart_id']; ?>, this.value)">
                        </td>
                        <td id="price_<?php echo $item['cart_id']; ?>">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <button class="btn-remove" onclick="removeItem(<?php echo $item['cart_id']; ?>)">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total-container">
            <div class="total-box">
                <h4>Total:</h4>
                <h5 class="text-right">$<?php echo number_format($totalAmount, 2); ?></h5>
                <br>
                <a href="checkout.php" class="btn-purchase">Make Purchase</a>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script>
        function updateQuantity(cart_id, quantity) {
            var priceElement = document.getElementById("price_" + cart_id);
            var pricePerItem = <?php echo json_encode($cart_items); ?>.find(item => item.cart_id == cart_id).price;
            var totalPrice = pricePerItem * quantity;
            priceElement.textContent = "$" + totalPrice.toFixed(2);
        }

        function removeItem(cart_id) {
            if (confirm("Are you sure you want to remove this item?")) {
                window.location.href = "Addtocart.php?action=remove&id=" + cart_id;
            }
        }
    </script>
</body>
</html>