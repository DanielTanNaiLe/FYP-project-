<?php
require '../admin_panel/config/dbconnect.php';
 include("header.php");
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = ''; 
}
   
require '../admin_panel/wishlist_cart.php';

if (isset($_POST['remove_from_wishlist']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Product removed from wishlist');</script>";
    } else {
        echo "<script>alert('Error removing product from wishlist');</script>";
    }
}

// Fetch wishlist items
$stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.product_image, p.price FROM wishlist w JOIN product p ON w.product_id = p.product_id WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wishlist</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>

        .wishlist-container {
            margin-top: 100px;
            max-width: 1200px;
            margin: 100px auto 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .wishlist-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .wishlist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }
        .wishlist-item img {
            max-width: 100px;
        }
        .wishlist-item-info {
            flex: 1;
            margin-left: 20px;
        }
        .wishlist-item-info h3 {
            margin: 0;
            color: #333;
        }
        .wishlist-item-info p {
            margin: 5px 0;
            color: #777;
        }
        .wishlist-item-price {
            color: #e74c3c;
            font-size: 18px;
            margin-right: 20px;
        }
        .wishlist-item-actions button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .wishlist-item-actions button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="wishlist-container">
        <h2>My Wishlist</h2>
        <?php if (count($wishlist_items) > 0): 
             foreach ($wishlist_items as $item): 
             ?>
                <div class="wishlist-item">
                    <img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>">
                    <div class="wishlist-item-info">
                        <h3><?php echo $item['product_name']; ?></h3>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <div class="wishlist-item-price">
                        $<?php echo number_format($item['price'], 2); ?>
                    </div>
                    <div class="wishlist-item-actions">
                        <form action="wishlist.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="remove_from_wishlist">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #777;">Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
    <?php include("footer.php");?>
</body>
</html>