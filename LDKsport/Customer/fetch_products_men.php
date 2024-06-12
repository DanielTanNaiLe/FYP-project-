<?php
require '../admin_panel/config/dbconnect.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Determine the sorting order
$sort_query = "";
switch ($sort) {
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

$query = "SELECT * FROM product 
          INNER JOIN category ON product.category_id = category.category_id 
          WHERE category.category_name = ? 
          AND product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'MEN') 
          $sort_query";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

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
    } else {
        echo "<p>No products found in this category.</p>";
    }
    echo '</div>';
}

displayProducts($result, $category);
?>
