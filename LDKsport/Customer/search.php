    <?php
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
        if ($result->num_rows > 0) {
            echo "<h2>Search Results for '$query':</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . $row['product_name'] . "</strong> - " . $row['product_desc'] . " (" . $row['brand_name'] . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<h2>No results found for '$query'.</h2>";
        }
    } else {
        echo "<h2>Error: " . $conn->error . "</h2>"; // Output SQL error
    }
} else {
    echo "<h2>Please enter a search query.</h2>";
}

$conn->close();
?>
