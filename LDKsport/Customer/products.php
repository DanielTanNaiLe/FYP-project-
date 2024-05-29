<?php
require '../admin_panel/config/dbconnect.php';
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';

$query = "SELECT * FROM product";
if ($brandFilter) {
    $query .= " WHERE brand_id = (SELECT brand_id FROM brand WHERE brand_name = '$brandFilter')";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer main page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        body{
            padding: 80px;
        }
    </style>
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
            <img src="../uploads/<?= $row['product_image'] ?>" alt="">
            <h2><?= $row["product_name"] ?></h2>
            <div class="price"><?= $row["price"] ?></div>
            <div class="favourite"><i class='bx bxs-heart'></i></div>
            <div class="details-container"><a href="" class="details">View details</a></div>
        </div>
        <?php
            }
        } else {
            echo "<p>No products found for the selected brand.</p>";
        }
        ?>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
