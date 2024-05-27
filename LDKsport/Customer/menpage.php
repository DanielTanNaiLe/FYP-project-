<?php require '../admin_panel/config/dbconnect.php';

include("header.php"); 
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer men page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        /* Add your custom CSS here */

        .listproduct {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .item {
            width: calc(33.33% - 10px); /* Three items per row with a little margin */
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .item h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .price {
            font-size: 16px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .favourite {
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: #e74c3c;
            font-size: 20px;
        }

        .details-container {
            text-align: center;
        }

        .details {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .details:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1 class="m1">MEN</h1>
    <div class="nav3">
        <a href="">Shoes</a>
        <a href="">Clothing</a>
        <a href="">Hats</a>
    </div>
    <div class="container">
        <div class="slidershow middle">
            <div class="slides">
                <input type="radio" name="r" id="r1" checked>
                <input type="radio" name="r" id="r2">
                <input type="radio" name="r" id="r3">
                <div class="slide s1">
                    <img src="./image/shoes1.webp">
                </div>
                <div class="slide">
                    <img src="./image/Nike-print-ads-a-002.jpg">
                </div>
                <div class="slide">
                    <img src="./image/dri-fit-club-unstructured-featherlight-cap-b0cNxd.png">
                </div>
            </div>
            <div class="navigation">
                <label for="r1" class="bar"></label>
                <label for="r2" class="bar"></label>
                <label for="r3" class="bar"></label>
            </div>
        </div>
    </div>
    <?php
    // Fetch and display shoes
    $shoesResult = mysqli_query($conn, "SELECT * FROM product INNER JOIN category ON product.category_id = category.category_id WHERE category.category_name = 'Shoes'");
    displayProducts($shoesResult, "Shoes");

    // Fetch and display clothing
    $clothingResult = mysqli_query($conn, "SELECT * FROM product INNER JOIN category ON product.category_id = category.category_id WHERE category.category_name = 'Clothing'");
    displayProducts($clothingResult, "Clothing");

    // Fetch and display pants
    $pantsResult = mysqli_query($conn, "SELECT * FROM product INNER JOIN category ON product.category_id = category.category_id WHERE category.category_name = 'Pants'");
    displayProducts($pantsResult, "Pants");

    function displayProducts($result, $categoryName) {
        echo '<div class="subtitle_1"><h1>' . $categoryName . '</h1></div>';
        echo '<div class="listproduct">';
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <form action="" method="post" class="box">
        <input type="hidden" name="pid" value="<?= $row['product_id'];?>">
        <input type="hidden" name="product_name" value="<?= $row['product_name'];?>">
        <input type="hidden" name="price" value="<?= $row['price'];?>">
        <input type="hidden" name="product_image" value="<?= $row['product_image'];?>"> 
        <div class="item">
            <img src="../uploads/<?=$row['product_image'];?>">
            <h2><?=$row["product_name"];?></h2>
            <div class="price">RM <?=$row["price"];?></div>
            <button type="submit" name="add_to_wishlist" class="favourite"><i class='bx bxs-heart'></i></button>
            <div class="details-container"><a href="product details.php?pid=<?= $row['product_id']; ?>" class="details">View details</a></div>
        </div>
    </form>
    <?php 
            }
        }
        echo '</div>';
    }
    ?>
    <?php include("footer.php"); ?>
</body>
</html>
