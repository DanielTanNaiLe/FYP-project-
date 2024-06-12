<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require '../admin_panel/config/dbconnect.php';
session_start(); // Ensure session is started

include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

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
        <div class="favourite" data-product-id="<?= $row['product_id']; ?>"><i class='bx bxs-heart'></i></div>
        <div class="details-container"><a href="product details.php?pid=<?= $row['product_id']; ?>" class="details">View details</a></div>
    </div>
</form>
<?php 
        }
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Men Page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!-- Ensure jQuery is loaded -->
</head>
<body>
    <?php include("header.php"); ?>
    <h1 class="m1">MEN</h1>
    <div class="nav3">
        <a href="#" id="shoes-link">Shoes</a>
        <a href="#" id="clothing-link">Clothing</a>
        <a href="#" id="pants-link">Pants</a>
    </div>
    <div class="sort-container">
        <label for="sort">Sort by:</label>
        <select id="sort" name="sort">
            <option value="latest">Latest</option>
            <option value="name_asc">Name (A to Z)</option>
            <option value="name_desc">Name (Z to A)</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low)</option>
        </select>
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
    <div class="product-list-container">
        <?php
        // Fetch and display all products for men
        $allProductsResult = mysqli_query($conn, "SELECT * FROM product 
                                                  INNER JOIN category ON product.category_id = category.category_id 
                                                  WHERE product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'MEN')");
        displayProducts($allProductsResult, "All Men Products");
        ?>
    </div>
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

            function loadProducts(category, sort) {
                $.ajax({
                    url: 'fetch_products_men.php',
                    method: 'GET',
                    data: { category: category, sort: sort },
                    success: function(response) {
                        $('.product-list-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        alert("Failed to load products. Please try again.");
                    }
                });
            }

            $('#shoes-link').click(function() {
                loadProducts('Shoes', $('#sort').val());
            });

            $('#clothing-link').click(function() {
                loadProducts('Clothing', $('#sort').val());
            });

            $('#pants-link').click(function() {
                loadProducts('Pants', $('#sort').val());
            });

            $('#sort').change(function() {
                loadProducts($('.nav3 a.active').attr('id').replace('-link', ''), $(this).val());
            });
        });
    </script>
</body>
</html>

