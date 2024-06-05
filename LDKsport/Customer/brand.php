<?php
require '../admin_panel/config/dbconnect.php';

// Get the brand name from the URL parameters
$brand_name = isset($_GET['brand']) ? $_GET['brand'] : '';

if ($brand_name) {
    // Fetch products for the selected brand
    $query = "SELECT * FROM product WHERE brand_id = (SELECT brand_id FROM brand WHERE brand_name = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $brand_name);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Redirect to home page if no brand is specified
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($brand_name); ?> - Products</title>
    <link rel="stylesheet" href="general.css">
</head>
<body>
    <?php include("header.php"); ?>
    <div class="brand-products">
        <h1>Products for <?php echo htmlspecialchars($brand_name); ?></h1>
        <div class="listproduct">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <img src="../uploads/<?php echo htmlspecialchars($row['product_img']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
                    <p class="price">$<?php echo htmlspecialchars($row['product_price']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
