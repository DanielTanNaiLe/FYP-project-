<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
session_start();
include '../admin_panel/config/dbconnect.php';

// Check if the search query parameter exists and is not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];

    // Prevent SQL injection
    $query = $conn->real_escape_string($query);

    // SQL query to search for products and join with the brand table
    $sql = "SELECT product.*, brand.brand_name 
            FROM product 
            JOIN brand ON product.brand_id = brand.brand_id
            WHERE product_name LIKE '%$query%' 
               OR product_desc LIKE '%$query%' 
               OR brand.brand_name LIKE '%$query%'";

    $result = $conn->query($sql);

    if ($result) {
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
            <div class="subtitle_1"><h1>Search Results for '<?= htmlspecialchars($query) ?>'</h1></div>
            <div class="listproduct">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <img src="../uploads/<?= $row['product_image'] ?>" alt="">
                    <h2><?= $row["product_name"] ?></h2>
                    <div class="price"><?= $row["price"] ?></div>
                    <div class="favourite"><i class='bx bxs-heart'></i></div>
                    <div class="details-container">
                        <a href="product_details.php?pid=<?= $row['product_id'] ?>" class="details">View details</a>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No results found for '" . htmlspecialchars($query) . "'.</p>";
                }
                ?>
            </div>
            <?php include("footer.php"); ?>
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
