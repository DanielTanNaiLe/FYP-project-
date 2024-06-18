<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
session_start();
include '../admin_panel/config/dbconnect.php';
include("header.php");

// Check if the search query parameter exists and is not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];

    // Prevent SQL injection
    $query = $conn->real_escape_string($query);

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

    // SQL query to search for products and join with the brand table
    $sql = "SELECT product.*, brand.brand_name 
            FROM product 
            JOIN brand ON product.brand_id = brand.brand_id
            WHERE product_name LIKE '%$query%' 
               OR product_desc LIKE '%$query%' 
               OR brand.brand_name LIKE '%$query%'
            $sort_query
            LIMIT $items_per_page OFFSET $offset";

    $result = $conn->query($sql);

    if ($result) {
        // Count total number of search results
        $count_sql = "SELECT COUNT(*) AS total_count 
                      FROM product 
                      JOIN brand ON product.brand_id = brand.brand_id
                      WHERE product_name LIKE '%$query%' 
                         OR product_desc LIKE '%$query%' 
                         OR brand.brand_name LIKE '%$query%'";
        $count_result = $conn->query($count_sql);
        $total_items = $count_result->fetch_assoc()['total_count'];
        $total_pages = ceil($total_items / $items_per_page);

        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Search Results</title>
            <link rel="stylesheet" href="general.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
            <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        </head>
        <body>
        <?php include("header.php"); ?>
        
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
            <select id="sort-by" onchange="sortProducts()">
                <option value="latest" <?= $sort_option == 'latest' ? 'selected' : '' ?>>Latest</option>
                <option value="name_asc" <?= $sort_option == 'name_asc' ? 'selected' : '' ?>>Name (A to Z)</option>
                <option value="name_desc" <?= $sort_option == 'name_desc' ? 'selected' : '' ?>>Name (Z to A)</option>
                <option value="price_asc" <?= $sort_option == 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                <option value="price_desc" <?= $sort_option == 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
            </select>
        </div>

        <div class="subtitle_1"><h1>Search Results for '<?= htmlspecialchars($query) ?>'</h1></div>
        <div class="listproduct">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
            <div class="item">
                <img src="../admin_panel/uploads/<?= $row['product_image'] ?>" alt="">
                <h2><?= $row["product_name"] ?></h2>
                <div class="price"><?= $row["price"] ?></div>
                <div class="favourite"><i class='bx bxs-heart'></i></div>
                <div class="details-container">
                    <a href="product details.php?pid=<?= $row['product_id'] ?>" class="details">View details</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No results found for '" . htmlspecialchars($query) . "'.</p>";
            }
            ?>
        </div>

        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?query=<?= urlencode($query) ?>&sort=<?= $sort_option ?>&page=<?= $current_page - 1 ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?= urlencode($query) ?>&sort=<?= $sort_option ?>&page=<?= $i ?>" class="<?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?query=<?= urlencode($query) ?>&sort=<?= $sort_option ?>&page=<?= $current_page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
        
        <?php include("footer.php"); ?>
        <script>
            function sortProducts() {
                var sortOption = document.getElementById('sort-by').value;
                var url = new URL(window.location.href);
                url.searchParams.set('sort', sortOption);
                window.location.href = url.href;
            }
        </script>
        </body>
        </html>
        <?php
    } else {
        echo "<h2>Error: " . $conn->error . "</h2>"; // Output SQL error
    }
} else {
    echo "<h2>Please enter a search query.</h2>";
}

$conn->close();
?>
