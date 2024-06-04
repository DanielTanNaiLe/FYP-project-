<?php require '../admin_panel/config/dbconnect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer main page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <?php include("header.php"); ?>
    <section class="main-home">
        <div class="main-text">
            <h5>Summer Collection</h5>
            <h1>New Summer <br>Collection 2024</h1>
            <p>There's Nothing like Trend</p>
            <a href="menpage.php" class="main-btn">Shop now<i class='bx bx-right-arrow-alt'></i></a>
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
                        <a href="category.php?gender=Girls&type=Shoes" class="home2-btn">Girl's Shoes</a>
                        <a href="category.php?gender=Girls&type=Clothing" class="home2-btn">Girl's Clothing</a>
                        <a href="category.php?gender=Girls&type=Pants" class="home2-btn">Girl's Pants</a>
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
    $result = mysqli_query($conn, "SELECT brand_name, brand_img FROM product INNER JOIN brand ON product.brand_id = brand.brand_id");
    if (mysqli_num_rows($result) > 0) {
        echo '<section class="main-home3">';
        echo '<div class="home3-banner">';
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="image-container3">
                <a href="#"><img src='../uploads/<?= $row["brand_img"] ?>'></a>
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
    <div class="product-list-container"></div>
    <?php include("footer.php"); ?>
</body>
</html>
