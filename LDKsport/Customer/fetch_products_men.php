<?php
require '../admin_panel/config/dbconnect.php';

$category = $_GET['category'];
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

$query = "SELECT * FROM product 
          INNER JOIN category ON product.category_id = category.category_id 
          WHERE category.category_name = ? 
          AND product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'MEN')";

if (!empty($brand)) {
    $query .= " AND product.brand = ?";
}

$stmt = $conn->prepare($query);

if (!empty($brand)) {
    $stmt->bind_param("ss", $category, $brand);
} else {
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();

function displayProducts($result, $categoryName) {
    echo '<div class="subtitle_1"><h1>' . htmlspecialchars($categoryName) . '</h1></div>';
    echo '<div class="listproduct">';
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
?>
<form action="" method="post" class="box">
    <input type="hidden" name="pid" value="<?= htmlspecialchars($row['product_id']); ?>">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['product_name']); ?>">
    <input type="hidden" name="price" value="<?= htmlspecialchars($row['price']); ?>">
    <input type="hidden" name="product_image" value="<?= htmlspecialchars($row['product_image']); ?>">
    <div class="item">
        <img src="../uploads/<?= htmlspecialchars($row['product_image']); ?>">
        <h2><?= htmlspecialchars($row["product_name"]); ?></h2>
        <div class="price">RM <?= htmlspecialchars($row["price"]); ?></div>
        <div class="favourite" data-product-id="<?= htmlspecialchars($row['product_id']); ?>"><i class='bx bxs-heart'></i></div>
        <div class="details-container"><a href="product details.php?pid=<?= htmlspecialchars($row['product_id']); ?>" class="details">View details</a></div>
    </div>
</form>
<?php 
        }
    }
    echo '</div>';
}

displayProducts($result, $category);
?>
