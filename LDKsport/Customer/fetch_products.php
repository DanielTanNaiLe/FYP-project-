<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require '../admin_panel/config/dbconnect.php';

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $query = "SELECT * FROM product 
              INNER JOIN category ON product.category_id = category.category_id 
              WHERE category.category_name = ? AND product.gender_id = (SELECT gender_id FROM gender WHERE gender_name = 'MEN')";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo '<div class="subtitle_1"><h1>' . $category . '</h1></div>';
            echo '<div class="listproduct">';
            while ($row = $result->fetch_assoc()) {
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
            echo '</div>';
        } else {
            echo '<div class="subtitle_1"><h1>No Products Found</h1></div>';
        }

        $stmt->close();
    } else {
        echo '<div class="subtitle_1"><h1>Error: Unable to prepare statement</h1></div>';
    }
} else {
    echo '<div class="subtitle_1"><h1>Error: No category specified</h1></div>';
}

$conn->close();
?>
