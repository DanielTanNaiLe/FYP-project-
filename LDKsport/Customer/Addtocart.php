<?php
session_start();
$connect = mysqli_connect("localhost","root","","ldksport");
if(isset($_POST['add_to_cart'])){
    if(isset($_GET['product_id'])){
        $prod_id = $_GET['product_id'];
        
        if(isset($_SESSION['cart'])){
            $session_array_id = array_column($_SESSION['cart'], 'product_id','size_id');

            if(!in_array($prod_id, $session_array_id)){

                $session_array = array(
                    'product_id' => $prod_id,
                    "product_name" => $_POST['product_name'],
                    "size_name" => $_POST['size_name'],
                    "price" => $_POST['price'],
                    "product_image" => $_POST['product_image'],
                    "Quantity" => $_POST['Quantity'],  
                );
                $_SESSION['cart'][] = $session_array;
            }
        } else {
           $session_array = array(
             'product_id' => $prod_id,
             "product_name" => $_POST['product_name'],
             "size_name" => $_POST['size_name'],
             "price" => $_POST['price'],
             "product_image" => $_POST['product_image'],
             "Quantity" => $_POST['Quantity'],
           );
             
           $_SESSION['cart'][] = $session_array;
        }
    }else{
       $session_array = array(
         'product_id' => $prod_id,
         "product_name" => $_POST['product_name'],
         "size_id" => $_GET['size_id'],
         "size_name" => $_POST['size_name'],
         "price" => $_POST['price'],
         "product_image" => $_POST['product_image'],
         "Quantity" => $_POST['Quantity'],
       );
         
       $_SESSION['cart'][] = $session_array;
    }
}
// Function to remove item from the cart
function removeFromCart($prod_id) {
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['product_id'] == $prod_id) {
                unset($_SESSION['cart'][$key]); // Remove item from session cart
                return true; // Return true if item is removed successfully
            }
        }
    }
    return false; // Return false if item is not found in the cart
}

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $prod_id = $_GET['id'];
    if (removeFromCart($prod_id)) {
        header("Location: Addtocart.php"); // Redirect to shopping cart page after removal
        exit();
    }
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
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
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
                    <th>ID</th>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize total variable
                $totalAmount = 0;
                
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $key => $value) {
                        $prod_id = isset($value['product_id']) ? $value['product_id'] : "";
                        $product_image = isset($value['product_image']) ? $value['product_image'] : "";
                        $product_name = isset($value['product_name']) ? $value['product_name'] : "";
                        $size_name = isset($value['size_name']) ? $value['size_name'] : "";
                        $quantity = isset($value['Quantity']) ? $value['Quantity'] : "";
                        $price = isset($value['price']) ? $value['price'] : "";

                        // Calculate subtotal for each item
                        $subtotal = (int)$quantity * (float)$price;
                        // Add subtotal to total
                        $totalAmount += $subtotal;

                        echo "
                        <tr>
                            <td>$prod_id</td>
                            <td><img src='$product_image' alt='$product_name'></td>
                            <td>$product_name</td>
                            <td>$size_name</td>
                            <td>$quantity</td>
                            <td>$$price</td>
                            <td>
                                <button class='btn-remove' onclick='removeItem($prod_id)'>Remove</button>
                            </td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Your cart is empty</td></tr>";
                }
                ?>
            </tbody>
            <!-- Display total amount in table footer -->
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td><strong>Total:</strong></td>
                    <td>$<?php echo $totalAmount; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        function removeItem($prod_id) {
            if (confirm("Are you sure you want to remove this item?")) {
                window.location.href = "Addtocart.php?action=remove&id=" + $prod_id;
            }
        }
    </script>
</body>
</html>