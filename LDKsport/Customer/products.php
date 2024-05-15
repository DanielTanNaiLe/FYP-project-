<?php
    require '../admin_panel/config/dbconnect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Customer main page</title>
        <link rel="stylesheet" href="general.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    </head>
    <body>
        <?php include("header.php"); 
        $result = mysqli_query($conn, "SELECT * FROM product");
        if (mysqli_num_rows($result) > 0) {
            ?>
        <div class="subtitle_1"><h1>Shoes</h1></div>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
        <div class="listproduct">
            <div class="item">
                <img src="../uploads/<?= $row['product_image'] ?>" alt="">
                <h2><?=$row["product_name"]?></h2>
                <div class="price"><?=$row["price"]?></div>
                <div class="favourite"><i class='bx bxs-heart'></i></div>
                <div class="details-container"><a href="" class="details">View details</a></div>
            </div>
        </div>
        <?php
        }
    }
        ?>
        <?php include("footer.php"); ?>
    </body>
        </html>