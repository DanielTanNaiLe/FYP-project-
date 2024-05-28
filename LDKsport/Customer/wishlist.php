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
        $_SESSION['message'] = 'Product removed from wishlist';
    } else {
        $_SESSION['message'] = 'Error removing product on wishist';
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .wishlist-container {
            margin: 60px auto;
            background-color: #fff;
            padding: 30px;
            height: 45%;
        }
        .wishlist-container h2 {
            background-color: #F2A32D;
            text-align: center;
            margin: 0 0 20px 0;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            border-radius: 5px;
        }
        .wishlist-item-info {
            flex: 1;
            margin-left: 20px;
        }
        .wishlist-item-info h3 {
            margin: 0;
            color: #333;
            font-size: 20px;
        }
        .wishlist-item-info p {
            margin: 5px 0;
            color: #777;
        }
        .wishlist-item-price {
            color: #e74c3c;
            font-size: 18px;
        }
        .wishlist-item-actions button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }
        .wishlist-item-actions button:hover {
            background-color: #c0392b;
        }
        .wishlist-item-actions a {
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .wishlist-item-actions a:hover {
            background-color: #2980b9;
        }
        .alert-container{
            background: #ffdb9b;
            padding: 20px 40px;
            min-width: 420px;
            position: absolute;
            right: 0px;
            top: 135px;
            overflow: hidden;
            border-radius: 4px;
            border-left: 8px solid #ffa502;
            cursor: pointer;
        }
        .alert-container.show {
            animation: show_slide 1s ease forwards;
        }
        @keyframes show_slide {
            0% {
                transform: translateX(100%);
            }
            40% {
                transform: translateX(-10%);
            }
            80% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-10%);
            }
        }
        .alert-container.hide{
            display: none;
        }
        .alert-container .alert {
            padding: 0 20px;
            font-size: 18px;
            color: #ce8500;
        }
        .alert-container:hover{
            background: #ffc766;
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
                    <a href="product_details.php?pid=<?php echo $item['product_id']; ?>">
                        <img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>">
                    </a>
                    <div class="wishlist-item-info">
                        <h3><?php echo $item['product_name']; ?></h3>
                        <p class="wishlist-item-price">Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <div class="wishlist-item-actions">
                        <form action="wishlist.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="remove_from_wishlist">Remove</button>
                        </form>
                        <a href="product details.php?pid=<?php echo $item['product_id']; ?>">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #777;">Your wishlist is empty.</p>
        <?php endif; ?>
         <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert-container show">';
                    echo '<span class="alert">' . $_SESSION['message'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['message']);
                }
                ?>
    </div>

    <script>
        
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert-container').addClass('hide');
            $('.alert-container').removeClass('show');
        }, 3000); // Change the duration as needed
    });

    $('.alert-container').click(function(){
        $(this).addClass('hide');
        $(this).removeClass('show');
    });

    </script>
    <?php include("footer.php"); ?>
</body>
</html>