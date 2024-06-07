<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require '../admin_panel/config/dbconnect.php';
session_start(); // Ensure session is started

// Check if a brand filter is set
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';

$query = "SELECT * FROM product";
if ($brandFilter) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM product WHERE brand_id = (SELECT brand_id FROM brand WHERE brand_name = ?)");
    $stmt->bind_param("s", $brandFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, $query);
}

if ($result === false) {
    // Handle query error
    die("Error executing query: " . mysqli_error($conn));
}
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
    <?php include("header.php"); ?>
    <div class="subtitle_1"><h1><?= $brandFilter ? ucfirst($brandFilter) . ' Products' : 'All Products' ?></h1></div>
    <div class="listproduct">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="item">
            <img src="../uploads/<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row["product_name"]) ?>">
            <h2><?= htmlspecialchars($row["product_name"]) ?></h2>
            <div class="price">RM <?=$row["price"];?></div>
            <div class="favourite" data-product-id="<?= htmlspecialchars($row['product_id']); ?>"><i class='bx bxs-heart'></i></div>
            <div class="details-container"><a href="product details.php?pid=<?= htmlspecialchars($row['product_id']); ?>" class="details">View details</a></div>
        </div>
        <?php
            }
        } else {
            echo "<p>No products found for the selected brand.</p>";
        }
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
        });
    </script>
</body>
</html>
