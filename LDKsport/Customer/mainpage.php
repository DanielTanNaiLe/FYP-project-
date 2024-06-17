<?php require '../admin_panel/config/dbconnect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer main page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <?php include("header.php"); ?>
    <section class="main-home">
        <div class="main-text">
            <h5>Summer Collection</h5>
            <h1>New Summer <br>Collection 2024</h1>
            <p>There's Nothing like Trend</p>
            <a href="products.php" class="main-btn">Shop now<i class='bx bx-right-arrow-alt'></i></a>
        </div>
    </section>
    <section class="main-home2">
        <div class="home2-text">
            <h1>Shop Men's, Women's & Kids'</h1>
        </div>
        <div class="home2-banner">
        <div class="main-home2-btn">
                <div class="image-container">
                    <img src="./image/banner1.jpg" alt="">
                    <div class="button-overlay">
                        <a href="category.php?gender=Men&type=Shoes" class="home2-btn">Men's Shoes</a>
                        <a href="category.php?gender=Men&type=Clothing" class="home2-btn">Men's Clothing</a>
                        <a href="category.php?gender=Men&type=Pants" class="home2-btn">Men's Pants</a>
                    </div>
                </div>
            </div>
            <div class="main-home2-btn">
                <div class="image-container">
                    <img src="./image/banner2.jpg" alt="">
                    <div class="button-overlay">
                        <a href="category.php?gender=WOMAN&type=Shoes" class="home2-btn">Girl's Shoes</a>
                        <a href="category.php?gender=WOMAN&type=Clothing" class="home2-btn">Girl's Clothing</a>
                        <a href="category.php?gender=WOMAN&type=Pants" class="home2-btn">Girl's Pants</a>
                    </div>
                </div>
            </div>
            <div class="main-home2-btn">
                <div class="image-container">
                    <img src="./image/banner4.jpg" alt="">
                    <div class="button-overlay">
                        <a href="category.php?gender=Kids&type=Shoes" class="home2-btn">Kids' Shoes</a>
                        <a href="category.php?gender=Kids&type=Clothing" class="home2-btn">Kids' Clothing</a>
                        <a href="category.php?gender=Kids&type=Pants" class="home2-btn">Kids' Pants</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="home2-text">
        <h1>Our Brands</h1>
    </div>
    <?php
    // Displaying brands
    $result = mysqli_query($conn, "SELECT DISTINCT brand.brand_name, brand.brand_img FROM brand INNER JOIN product ON product.brand_id = brand.brand_id");
    if (mysqli_num_rows($result) > 0) {
        echo '<section class="main-home3">';
        echo '<div class="home3-banner">';
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="image-container3">
                <a href="brand.php?brand=<?= urlencode($row["brand_name"]) ?>"><img src='../admin_panel/uploads/<?= $row["brand_img"] ?>' alt='<?= $row["brand_name"] ?>'></a>
                <h1><?= $row['brand_name'] ?></h1>
            </div>
            <?php
        }
        echo '</div>';
        echo '</section>';
    } else {
        echo "No brands found";
    }
    ?>
    <div class="home2-text">
        <h1>Latest Products</h1>
    </div>
    <section class="latest-products">
        <div class="products-banner">
            <?php
            // Displaying latest products
            $latest_products_query = "SELECT * FROM product ORDER BY product_id DESC LIMIT 4";
            $latest_products_result = mysqli_query($conn, $latest_products_query);

            if (mysqli_num_rows($latest_products_result) > 0) {
                echo '<div class="listproduct">';
                while ($product = mysqli_fetch_assoc($latest_products_result)) {
            ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $product['product_id'];?>">
                <input type="hidden" name="product_name" value="<?= $product['product_name'];?>">
                <input type="hidden" name="price" value="<?= $product['price'];?>">
                <input type="hidden" name="product_image" value="<?= $product['product_image'];?>">
                <div class="item">
                    <img src="../admin_panel/uploads/<?=$product['product_image'];?>" alt="<?=$product['product_name'];?>">
                    <h2><?=$product["product_name"];?></h2>
                    <div class="price">RM <?=$product["price"];?></div>
                    <div class="favourite" data-product-id="<?= $product['product_id']; ?>"><i class='bx bxs-heart'></i></div>
                    <div class="details-container"><a href="product details.php?pid=<?= $product['product_id']; ?>" class="details">View details</a></div>
                </div>
            </form>
            <?php
                }
                echo '</div>';
            } else {
                echo "No latest products found";
            }
            ?>
        </div>
    </section>

    <?php include("footer.php"); ?>

    <script>
        $(document).ready(function() {
            $('.favourite').click(function() {
                var productId = $(this).data('product-id');
                $.ajax({
                    url: 'add_to_wishlist.php',
                    method: 'POST',
                    data: { product_id: productId },
                    success: function(response) {
                        alert(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        alert("Failed to add to wishlist. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>
