<?php
require '../admin_panel/config/dbconnect.php';
include("header.php");

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
        $_SESSION['message'] = 'Error removing product from wishlist';
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <div class="wishlist-container">
        <h2>My Wishlist</h2>
        <div class="wishlist-itemcontainer">
        <?php if (count($wishlist_items) > 0): 
             foreach ($wishlist_items as $item): 
             ?>
                <div class="wishlist-item">
                    <a href="product_details.php?pid=<?php echo $item['product_id']; ?>">
                        <img src="../uploads/<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>">
                    </a>
                    <div class="wishlist-item-info">
                        <h3><?php echo $item['product_name']; ?></h3>
                        <p class="wishlist-item-price">Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <div class="wishlist-item-actions">
                        <a href="product_details.php?pid=<?php echo $item['product_id']; ?>">View Details</a>
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
         <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert-container show">';
                    echo '<span class="alert">' . $_SESSION['message'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['message']);
                }
                ?>
    </div>
    <?php include("footer.php"); ?>
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
</body>
</html>
