<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require '../admin_panel/config/dbconnect.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT * FROM product 
          INNER JOIN category ON product.category_id = category.category_id 
          WHERE product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'MEN')";

if (!empty($category)) {
    $query .= " AND category.category_name = '$category'";
}

$result = mysqli_query($conn, $query);

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
    echo '<p>No products found.</p>';
}
?>
