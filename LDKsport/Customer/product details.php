<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="general.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        section {
            padding-top: 7%;
        }

        .product-details-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            max-width: 1200px;
            margin: auto;
            padding: 50px;
            background: white;
            box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        .left {
            width: 50%;
            padding-right: 20px;
            box-sizing: border-box;
        }

        .main_image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
        }

        .option {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .option img {
            width: 100px;
            height: auto;
            border-radius: 5px;
            cursor: pointer;
        }

        .right {
            width: 50%;
            padding-left: 20px;
            box-sizing: border-box;
        }

        .product-details-h3 {
            color: #af827d;
            margin: 0;
            font-size: 30px;
        }

        .product-details-h5, p, small {
            color: #837D7C;
            margin: 0;
        }

        .product-details-h4 {
            color: red;
            margin-top: 10px;
        }

        .product-details-h5 {
            font-size: 15px;
            margin-top: 10px;
        }

        .product-details-dropmenu {
            margin-top: 10px;
            font-size: medium;
            padding: 5px;
            border: solid 2px black;
            cursor: pointer;
            transition: all .42 ease;
        }

        .product-details-dropmenu:hover {
            transform: scale(1.1);
            border-color: rgb(242, 163, 45);
            color: #837d7c;
        }

        .button-container {
            margin-top: 40px;
        }

        .button {
            display: block;
            width: 100%;
            margin-top: 15px;
            font-size: 20px;
            text-align: center;
            padding: 12px;
            border: 2px solid black;
            color: black;
            text-decoration: none;
            background-color: transparent;
            cursor: pointer;
            transition: all .5s;
        }

        .button:hover {
            transform: scale(1.1);
            background-color: rgb(242, 163, 45);
            color: white;
        }

        @media only screen and (max-width: 768px) {
            .product-details-container {
                flex-direction: column;
                align-items: center;
                padding: 20px;
            }

            .left, .right {
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
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
<section>
    <div class="product-details-container">
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
                    <input type="hidden" name="pid" value="<?= $row['product_id'] ?>">
                    <input type="hidden" name="product_name" value="<?= $row['product_name'] ?>">
                    <input type="hidden" name="price" value="<?= $row['price'] ?>">
                    <input type="hidden" name="product_desc" value="<?= $row['product_desc'] ?>">
                    <input type="hidden" name="product_image" value="<?= $row['product_image'] ?>">
                    <div class="left">
                        <div class="main_image">
                            <img src="../uploads/<?= $row['product_image'] ?>" class="slide">
                        </div>
                        <div class="option">
                            <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                            <img src="image/jd_DV0831-108_a.webp" onclick="img('image/jd_DV0831-108_a.webp')">
                            <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                            <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                            <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                            <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                        </div>
                    </div>
                    <div class="right">
                        <h3 class="product-details-h3" name="product_name"><?= $row['product_name'] ?></h3>
                        <h5>men's shoes</h5>
                        <h4 class="product-details-h4" name="price"> <small>RM </small><?= $row['price'] ?></h4>
                        <p name="product_desc"><?= $row['product_desc'] ?></p>
                        <h5 class="product-details-h5">Size</h5>
                        <select class="product-details-dropmenu" id="sizes" name="size_name" >
                            <option disabled selected>Select Sizes</option>
                            <?php
                            $sql = "SELECT sizes.size_id, sizes.size_name FROM product_size_variation
                                    INNER JOIN sizes ON product_size_variation.size_id = sizes.size_id
                                    INNER JOIN product ON product_size_variation.product_id = product.product_id
                                    WHERE product.product_id = ?";
                            $size_stmt = $conn->prepare($sql);
                            $size_stmt->bind_param("i", $pid);
                            $size_stmt->execute();
                            $size_result = $size_stmt->get_result();
                            while ($size_row = $size_result->fetch_assoc()) {
                                echo "<option value='" . $size_row['size_id'] . "'>" . $size_row['size_name'] . "</option>";
                            }
                            ?>
                        </select>
                        <div class="button-container">
                            <input type="number" name="Quantity" value="1" class="form-control">
                            <input type="submit" name="add_to_cart" class="button" value="Add To Cart">
                            <input type="submit" name="add_to_wishlist" class="button" value="Wish List">
                        </div>
                    </div>
                </form>
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert-container show">';
                    echo '<span class="alert">' . $_SESSION['message'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['message']);
                }
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

    function img(anything) {
        document.querySelector('.slide').src = anything;
    }

    function validateFormForCart() {
        var sizes = document.getElementById("sizes");
        if (sizes.value === "Select Sizes") {
            alert("Please select a size.");
            return false;
        }
        return true;
    }

    $('#productForm').submit(function() {
        // Check if the form is for adding to cart
        if ($(this).find('[name="add_to_cart"]').length > 0) {
            return validateFormForCart();
        }
        // For wishlist, no validation needed, so return true
        return true;
    });

</script>
<?php include("footer.php"); ?>
</body>
</html>
