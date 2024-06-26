<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require '../admin_panel/config/dbconnect.php';
session_start(); // Ensure session is started
include("header.php");

// Check if a brand filter is set
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';

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

if ($brandFilter) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM product WHERE brand_id = (SELECT brand_id FROM brand WHERE brand_name = ?) $sort_query LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $brandFilter, $items_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Get the total number of products for pagination
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = (SELECT brand_id FROM brand WHERE brand_name = ?)");
    $count_stmt->bind_param("s", $brandFilter);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_items = $count_result->fetch_row()[0];

    // Get the category name for the title
    $categoryName = ucfirst($brandFilter) . ' Products';
} else {
    // Fetch products without a brand filter
    $result = $conn->query("SELECT * FROM product $sort_query LIMIT $items_per_page OFFSET $offset");

    // Get the total number of products for pagination
    $count_result = $conn->query("SELECT COUNT(*) FROM product");
    $total_items = $count_result->fetch_row()[0];

    // Set the category name for the title
    $categoryName = 'All Products';
}

if ($result === false) {
    // Handle query error
    die("Error executing query: " . mysqli_error($conn));
}

$total_pages = ceil($total_items / $items_per_page); // Calculate the total number of pages
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($brandFilter) ? htmlspecialchars($brandFilter) . ' Products' : 'All Products' ?></title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!-- Ensure jQuery is loaded -->
</head>
<body>

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
    <label for="sort">Sort by:</label>
    <select id="sort" name="sort" onchange="sortProducts()">
        <option value="latest" <?= $sort_option == 'latest' ? 'selected' : '' ?>>Latest</option>
        <option value="name_asc" <?= $sort_option == 'name_asc' ? 'selected' : '' ?>>Name (A to Z)</option>
        <option value="name_desc" <?= $sort_option == 'name_desc' ? 'selected' : '' ?>>Name (Z to A)</option>
        <option value="price_asc" <?= $sort_option == 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
        <option value="price_desc" <?= $sort_option == 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
    </select>
</div>

<div class="subtitle_1">
    <h1><?= $categoryName ?></h1>
</div>

<div class="listproduct">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <div class="item">
        <img src="../admin_panel/uploads/<?= htmlspecialchars($row['product_image']) ?>" alt="">
        <h2><?= htmlspecialchars($row["product_name"]) ?></h2>
        <div class="price"><?= htmlspecialchars($row["price"]) ?></div>
        <div class="favourite" data-product-id="<?= $row['product_id']; ?>"><i class='bx bxs-heart'></i></div>
        <div class="details-container"><a href="product details.php?pid=<?= $row['product_id']; ?>" class="details">View details</a></div>
    </div>
    <?php
        }
    } else {
        echo "<p>No products found for the selected brand.</p>";
    }
    ?>
</div>

<div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="?<?= $brandFilter ? 'brand=' . urlencode($brandFilter) . '&' : '' ?>sort=<?= $sort_option ?>&page=<?= $current_page - 1 ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?<?= $brandFilter ? 'brand=' . urlencode($brandFilter) . '&' : '' ?>sort=<?= $sort_option ?>&page=<?= $i ?>" class="<?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?<?= $brandFilter ? 'brand=' . urlencode($brandFilter) . '&' : '' ?>sort=<?= $sort_option ?>&page=<?= $current_page + 1 ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>

<script>
    function sortProducts() {
        var sortOption = document.getElementById('sort').value;
        var url = new URL(window.location.href);
        url.searchParams.set('sort', sortOption);
        window.location.href = url.href;
    }

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
