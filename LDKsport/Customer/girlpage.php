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

// Determine the current page and set the limit of items per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 8; // Set the number of items per page
$offset = ($current_page - 1) * $items_per_page;

// Determine the sorting option
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
$sort_query = "";
switch ($sort_option) {
    case 'name_asc':
        $sort_query = "ORDER BY product_name ASC";
        break;
    case 'name_desc':
        $sort_query = "ORDER BY product_name DESC";
        break;
    case 'price_asc':
        $sort_query = "ORDER BY price ASC";
        break;
    case 'price_desc':
        $sort_query = "ORDER BY price DESC";
        break;
    default:
        $sort_query = "ORDER BY product_id DESC"; // Default to latest
        break;
}

// Fetch and display products for women with pagination
$allProductsResult = mysqli_query($conn, "SELECT * FROM product 
                                          INNER JOIN category ON product.category_id = category.category_id 
                                          WHERE product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'WOMAN')
                                          $sort_query LIMIT $items_per_page OFFSET $offset");

$count_result = mysqli_query($conn, "SELECT COUNT(*) FROM product 
                                     INNER JOIN category ON product.category_id = category.category_id 
                                     WHERE product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'WOMAN')");
$total_items = mysqli_fetch_row($count_result)[0];
$total_pages = ceil($total_items / $items_per_page);

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
        <img src="../admin_panel/uploads/<?=$row['product_image'];?>">
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
    <title>Customer Women Page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!-- Ensure jQuery is loaded -->
</head>
<body>
    <h1 class="m1">WOMEN</h1>
    <div class="nav3">
        <a href="#" id="shoes-link">Shoes</a>
        <a href="#" id="clothing-link">Clothing</a>
        <a href="#" id="pants-link">Pants</a>
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
    <div class="sort-container">
        <label for="sort-by">Sort by:</label>
        <select id="sort-by">
            <option value="latest">Latest</option>
            <option value="name-asc">Name (A to Z)</option>
            <option value="name-desc">Name (Z to A)</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
        </select>
    </div>
    <div id="products-container">
        <?php displayProducts($allProductsResult, "All Women Products"); ?>
    </div>
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?sort=<?= $sort_option ?>&page=<?= $current_page - 1 ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?sort=<?= $sort_option ?>&page=<?= $i ?>" class="<?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?sort=<?= $sort_option ?>&page=<?= $current_page + 1 ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php include("footer.php"); ?>
    <script>
        $(document).ready(function() {
            let currentCategory = 'All';

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
                    url: 'fetch_products_girl.php',
                    method: 'GET',
                    data: { category: category, sort: sort },
                    success: function(response) {
                        $('#products-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        alert("Failed to load products. Please try again.");
                    }
                });
            }

            $('#shoes-link').click(function() {
                currentCategory = 'Shoes';
                loadProducts(currentCategory, $('#sort-by').val());
            });

            $('#clothing-link').click(function() {
                currentCategory = 'Clothing';
                loadProducts(currentCategory, $('#sort-by').val());
            });

            $('#pants-link').click(function() {
                currentCategory = 'Pants';
                loadProducts(currentCategory, $('#sort-by').val());
            });

            $('#sort-by').change(function() {
                loadProducts(currentCategory, $(this).val());
            });
        });
    </script>
</body>
</html>
