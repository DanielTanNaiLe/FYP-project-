<?php
require '../admin_panel/config/dbconnect.php';

include("header.php"); 
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}

require '../admin_panel/wishlist_cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="general.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .product-details-container {
        display: flex;
        align-items: center;
        max-width: 75%;
        margin: auto;
        height: 80vh;
        background: white;
        box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
    }

    .option img {
        width: 85px;
        height: 75px;
        padding: 6px 2px;
    }

    .size-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .size-option img {
        margin-right: 10px;
    }

    .size-option small {
        margin-left: auto;
    }

    /* Your existing CSS styles */
    </style>
</head>
<body>
<section>
    <div class="product-details-container flex">
        <?php
        if(isset($_GET["pid"])) {
            $pid = $_GET["pid"];
            $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if($row) {
                ?>
                <form id="productForm" method="post" action="">
                    <!-- Your existing form inputs -->
                    <?php
                    echo "<div class='left'>";
                    echo "<div class='main_image'>";
                    echo "<img src='../uploads/" . $row['product_image'] . "' class='slide'>";
                    echo "</div>";
                    echo "<div class='option flex'>";
                    
                    // Fetch size variations and quantities
                    $size_stmt = $conn->prepare("SELECT sizes.size_name, psv.quantity_in_stock FROM product_size_variation psv
                                                INNER JOIN sizes ON psv.size_id = sizes.size_id
                                                WHERE psv.product_id = ?");
                    $size_stmt->bind_param("i", $pid);
                    $size_stmt->execute();
                    $size_result = $size_stmt->get_result();

                    while ($size_row = $size_result->fetch_assoc()) {
                        echo "<div class='size-option'>";
                        echo "<img src='image/size-icon.png'>";
                        echo "<span>" . $size_row['size_name'] . "</span>";
                        echo "<small>Stock: " . $size_row['quantity_in_stock'] . "</small>";
                        echo "</div>";
                    }
                    echo "</div>"; // closing .option
                    echo "</div>"; // closing .left

                    // Your remaining HTML code
                    ?>
                </form>
                <?php
            } else {
                echo '<p class="empty">No product found!</p>';
            }
        } else {
            echo '<p class="empty">No product ID provided!</p>';
        }
        ?>
    </div>
</section>
<script>
    // Your existing JavaScript code
</script>
<?php include("footer.php"); ?>
</body>
</html>
